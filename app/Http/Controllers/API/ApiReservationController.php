<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiReservationController extends Controller
{
    public function storeAsCitizen(Request $request)
    {
        dd(date('H:i:s', strtotime( config('reservation.timeForSameDay'))));
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


    public function update(Request $request)
    {

    }


    public function fetchResevationToEdit(int $id)
    {

    }

    public function delete(int $id)
    {

    }
}
