<?php

namespace App\Http\Controllers\API;

use App\Events\ReservationChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Http\Requests\Reservation\WorkerReservationRequest;
use App\Mails\ReservationMail;
use App\Models\Reservation;
use App\Services\ReservationService;

class ApiReservationController extends Controller
{
    /**
     * @param CustomerReservationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAsCustomer(CustomerReservationRequest $request)
    {
        try {
           if($this->getReservationService()->storeCustomerReservation($request)){
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
            $this->getReservationService()->storeWorkerReservations($request);
            return response()->json(['message' => "Rezerwacja została pomyślnie zapisana."], 200);

        } catch (\Exception $exception) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerIndex()
    {
        try {
            return response()->json(['reservations' => $this->getReservationService()->customerReservations()], 200);
        } catch (\Exception $exception) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function workerIndex(string $date)
    {
        try {
            return response()->json(['reservations' => $this->getReservationService()->workerReservations($date)], 200);
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
            return response()->json(['tables' => $this->getReservationService()->freeTablesByDate($date)], 200);
        } catch (\Exception $exception) {
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
            broadcast(new ReservationChanged())->toOthers();
            return response()->json("Rezerwacja została anulowana", 201);
        } catch (\Exception $e) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @return ReservationService
     */
    private function getReservationService():ReservationService
    {
        return new ReservationService();
    }
}
