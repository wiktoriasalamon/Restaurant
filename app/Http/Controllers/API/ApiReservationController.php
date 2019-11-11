<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Http\Requests\Reservation\WorkerReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\ReservationService;
use App\Services\TableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiReservationController extends Controller
{
    /**
     * @param CustomerReservationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAsCustomer(CustomerReservationRequest $request)
    {
        try {
            $reservation = new Reservation();
            $reservation->date = $request->date;
            $reservation->start_time = $request->startTime;
            $reservation->setCustomer($request->email, $request->phone);
            if ($reservation->findTable($request->tableSize)) {
                $reservation->save();
                return response()->json(['message' => "Rezerwacja została pomyślnie zapisana."], 200);
            }
            return response()->json(['message' => "Brak dostępnego stolika w podanym terminie.", 500]);
        } catch (\Exception $exception) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param WorkerReservationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAsWorker(WorkerReservationRequest $request)
    {
        try {
            foreach ($request->tables as $tableId) {
                $reservation = new Reservation();
                $reservation->setWorkerReservation($request, $tableId);
                $reservation->save();
            }
            return response()->json(['message' => "Rezerwacja została pomyślnie zapisana."], 200);

        } catch (\Exception $exception) {
            dd($exception);
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param WorkerReservationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAsWorker(WorkerReservationRequest $request)
    {
        try {
            $reservation = Reservation::findOrFail($request->id);
            $reservation->setWorkerReservation($request);
            $reservation->update();
            return response()->json(['message' => "Rezerwacja została pomyślnie zapisana."], 200);

        } catch (\Exception $exception) {
            dd($exception);
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerIndex()
    {
        try {
            return response()->json(['reservations' => (new ReservationService())->reservationWithStatus()], 200);
        } catch (\Exception $exception) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchTablesByDate(string $date)
    {
        try {
            return response()->json(['tables' => (new ReservationService())->freeTablesByDate($date)], 200);
        } catch (\Exception $exception) {
            dd($exception);
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchReservation(int $id)
    {
        return response()->json(['reservation' => Reservation::with('table')->findOrFail($id)], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        try {
            Reservation::findOrFail($id)->delete();
            return response()->json("Rezerwacja została anulowana", 201);
        } catch (\Exception $e) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}
