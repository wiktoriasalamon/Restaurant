<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderChangeStatusRequest;
use App\Http\Requests\Reservation\CustomerReservationRequest;
use App\Http\Requests\Reservation\WorkerReservationRequest;
use App\Interfaces\StatusTypesInterface;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\OrderService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * Return orders ordered in restaurant by customer in given table_id which status is ordered
     * For edit order purpose
     * @param $id table
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchOpenOrderByGivenTable($id)
    {
        try {
            return response()->json(Order::statusNotEqual(StatusTypesInterface::TYPE_FINISHED)
                ->orderedLocal()
                ->where("table_id", $id)
                ->get()
                ->load("check"),
                200);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * WIP
     * todo (transformer check to dish [id, name] + amount in order
     * todo uzgodnić postać array
     * For kitchen list of open orders
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchNoPrepareOrder()
    {
        try {
            return response()->json(Order::status(StatusTypesInterface::TYPE_ORDERED)
                ->with("check")
                ->get()
                , 200);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     *
     * For waiters list of open orders
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchReadyToPickUpOrder()
    {
        try {
            return response()->json(Order::status(StatusTypesInterface::TYPE_COMPLETED)->get()->load("check"), 200);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * To change status of order
     * @param OrderChangeStatusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusOrder(OrderChangeStatusRequest $request)
    {
        try {
            dd($request->order_id);
            $types = StatusTypesInterface::TYPES;
            if (!in_array($request->status, $types))
                return response()->json('Błędnyz status', 422);
            if ($order = Order::findOrFail($request->order_id)->first()) {
                $order->status = $request->status;
                $order->save();
                return response()->json("Status zmieniony", 200);
            }
            return response()->json('Błędne id zamówienia', 500);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * Get possible order status types
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchOrderStatusTypes()
    {
        try {
            return response()->json(StatusTypesInterface::TYPES, 200);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }


}
