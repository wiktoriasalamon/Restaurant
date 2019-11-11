<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Http\Requests\Reservation\WorkerReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\OrderService;
use App\Services\ReservationService;
use App\Services\TableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{


    /**
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchTablesByDate(string $date)
    {
        try {
            return response()->json(['tables' => (new OrderService())->tablesByDate($date)], 200);
        } catch (\Exception $exception) {
            dd($exception);
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }


}
