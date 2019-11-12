<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Http\Requests\Reservation\WorkerReservationRequest;
use App\Interfaces\StatusTypesInterface;
use App\Models\Order;
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

    /**
     * Return orders ordered in restaurant by customer in given table_id which status is ordered
     * @param $id table
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchNoPrepareOrderByGivenTable($id)
    {
        try {
            return response()->json(Order::status(StatusTypesInterface::TYPE_ORDERED)
                ->orderedLocal()
                ->where("table_id",$id)
                ->get()
                ->load("check"),
                200);
        } catch (\Exception $exception) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * WIP
     * todo (transformer check to dish [id, name] + amount in order
     *
     * For kitchen list of open orders
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchNoPrepareOrder()
    {
        try {
            return response()->json(Order::status(StatusTypesInterface::TYPE_ORDERED)->get()->load("check"), 200);
        } catch (\Exception $exception) {
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }



}
