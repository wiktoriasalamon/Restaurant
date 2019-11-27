<?php


namespace App\Services;

use App\Events\ReservationChanged;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Http\Requests\Reservation\WorkerReservationRequest;
use App\Mails\ReservationMail;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ReservationService
{
    private const STATUS_CURRENT = 'current';
    private const STATUS_FINISHED = 'finished';

    /**
     * Check if time for new reservation is after now
     * @param string $time
     * @param string $date
     * @return bool
     * @codeCoverageIgnore
     */
    public function timeForSameDay(string $time, string $date): bool
    {
        if (Carbon::now()->format('Y-m-d') < $date) {
            return true;
        } elseif ($time >= date('H:i', strtotime(config('reservation.timeForSameDay')))) {
            return true;
        }
        return false;

    }

    /**
     * One reservation a day for customer
     * @param string $date
     * @param string $email
     * @return bool
     */
    public function oneADay(string $date, string $email): bool
    {
        if (Reservation::where('email', $email)->where('date', $date)->first()) {
            return false;
        }
        return true;
    }

    /**
     * @param string $time
     * @return bool
     * @codeCoverageIgnore
     */
    public function openHours(string $time): bool
    {
        if ($time < config('reservation.openTime') || $time > config('reservation.lastReservation')) {
            return false;
        }
        return true;
    }

    /**
     * @param $id
     * @param $date
     * @param $time
     * @return bool
     */
    public function isTableAvailable($id, $date, $time): bool
    {
        try {
            if (Carbon::now()->format('Y-m-d') == $date) {
                $table = Table::where('id', $id)->where('occupied_since', null)->whereDoesntHave('reservation', function ($query) use ($date, $time) {
                    $query->where('date', 'like', $date)->where('start_time', '>=', $time);
                })->first();

            } else {
                $table = Table::where('id', $id)->whereDoesntHave('reservation', function ($query) use ($date) {
                    $query->where('date', 'like', $date);
                })->first();
            }
            if ($table) {
                return true;
            }
        } catch (\Exception $exception) {
        }
        return false;
    }

    /**
     * @param $reservations
     * @return array
     */
    private function reservationWithStatus($reservations): array
    {
        $reservationWithStatus = [];
        foreach ($reservations as $reservation) {
            $status = self::STATUS_CURRENT;
            $nowDate = Carbon::now()->format('Y-m-d');
            if ($nowDate > $reservation->date) {
                $status = self::STATUS_FINISHED;
            }
            if ($nowDate == $reservation->date && Carbon::now()->format('H:i') > $reservation->start_time) {
                $status = self::STATUS_FINISHED;
            }
            $tableSize=Table::findOrFail($reservation->table_id)->size;
            array_push($reservationWithStatus, ['reservation' => $reservation, 'status' => $status, 'size'=>$tableSize]);
        }
        return $reservationWithStatus;
    }

    /**
     * @param string $date
     * @return array
     */
    public function workerReservations(string $date): array
    {
        return $this->reservationWithStatus(Reservation::where('date', $date)->get());
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function customerReservations(): array
    {
        $auth = Auth::user();
        return $this->reservationWithStatus(Reservation::where('email', $auth->email)->get());
    }


    /**
     * @param string $date
     * @return mixed
     */
    public function freeTablesByDate(string $date)
    {
        try {
            if (Carbon::now()->format('Y-m-d') == $date) {
                $time=Carbon::now()->format('H:i:s');
                $tables = Table::where('occupied_since', null)->whereDoesntHave('reservation', function ($query) use ($date, $time) {
                    $query->where('date', 'like', $date)->where('start_time', '>=', $time);
                })->get();

            } else {
                $tables = Table::whereDoesntHave('reservation', function ($query) use ($date) {
                    $query->where('date', 'like', $date);
                })->get();

            }
                return $tables;
        } catch (\Exception $exception) {

        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     * @param WorkerReservationRequest $request
     */
    public function storeWorkerReservations(WorkerReservationRequest $request)
    {
        foreach ($request->tables as $tableId) {
            $reservation = new Reservation();
            $reservation->date = $request->date;
            $reservation->start_time = $request->startTime;
            $reservation->phone = $request->phone;
            $reservation->email = $request->email;
            $reservation->table()->associate(Table::findOrFail($tableId));
            $reservation->save();
        }
        broadcast(new ReservationChanged())->toOthers();
    }

    /**
     * @param CustomerReservationRequest $request
     * @codeCoverageIgnore
     * @return bool
     */
    public function storeCustomerReservation(CustomerReservationRequest $request): bool
    {
        $reservation = new Reservation();
        $reservation->date = $request->date;
        $reservation->start_time = $request->startTime;
        $reservation->setCustomer($request->email, $request->phone);
        if ($reservation->findTable($request->tableSize) && $reservation->save()) {
            broadcast(new ReservationChanged())->toOthers();
            (new ReservationMail($reservation))->sendMail();
            return true;
        }

        return false;
    }

    public function fetchReservation(int $id):array
    {
        $reservation= Reservation::with('table')->findOrFail($id);
        return[
            "date"=>$reservation->date,
            "startTime"=>$reservation->start_time,
            'tableSize'=>$reservation->table->size
        ];
    }
}
