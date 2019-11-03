<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiReservationController extends Controller
{
    /**
     * @param CustomerReservationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAsCitizen(CustomerReservationRequest $request)
    {
        $reservation = new Reservation();
        $reservation->date = $request->date;
        $reservation->start_time = $request->startTime;
        $reservation->setCustomer($request->email, $request->phone);
        if ($reservation->findTable($request->tableSize)) {
            $reservation->save();
            return response()->json(['message' => "Rezerwacja została pomyślnie zapisana."], 200);
        }
        return response()->json(['message' => "Brak dostępnego stolika w podanym terminie.", 500]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerIndex()
    {
        return response()->json(['reservations' =>  (new ReservationService())->reservationWithStatus()], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchReservation(int $id)
    {
        return response()->json(['reservation' => Reservation::with('table')->findOrFail($id)], 200);
    }

    public function delete(int $id)
    {

    }
}
