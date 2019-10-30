<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiReservationController extends Controller
{
    public function storeAsCitizen(Request $request)
    {
        $reservation=new Reservation();
        $reservation->date=$request->date;
        $reservation->start_time=$request->startTime;
        $reservation->setCustomer($request->email,$request->phone);
        if($reservation->findTable($request->tableSize)) {
            $reservation->save();
        }
    }

    public function update(Request $request)
    {

    }

    public function index(Request $request)
    {

    }

    public function fetchResevationToEdit(int $id)
    {

    }

    public function delete(int $id)
    {

    }
}
