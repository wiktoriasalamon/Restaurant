<?php

namespace App\Http\Controllers\API;

use App\Events\OrderChanged;
use App\Events\ReservationChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\NewOrderFromWorkerRequest;
use App\Http\Requests\Order\NewOrderOnlineRequest;
use App\Http\Requests\Order\OrderChangeStatusRequest;
use App\Interfaces\StatusTypesInterface;
use App\Mails\OrderOnlineMail;
use App\Models\Check;
use App\Models\Order;
use App\Models\Table;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiOrderController extends Controller
{

    /**
     * @param string $date
     * @return JsonResponse
     */
    public function fetchTablesByDate(string $date)
    {
        try {
            return response()->json(['tables' => (new OrderService())->tablesByDate($date)], 200);
        } catch (Exception $e) {
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
     * @return JsonResponse
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
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * All open order with given status
     * @param $type [ordered, ...]
     * @return JsonResponse
     */
    public function orderWithStatus($type)
    {
        try {
            return response()->json(Order::status($type)
                ->statusNotEqual(StatusTypesInterface::TYPE_FINISHED)
                ->with("check")
                ->get()
                , 200);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * All open order with worker_id = auth::id()
     * @return JsonResponse
     */
    public function myOrder()
    {
        try {
            return response()->json(Order::statusNotEqual(StatusTypesInterface::TYPE_FINISHED)
                ->where('worker_id', Auth::id())
                ->with("check")
                ->get()
                , 200);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * All orders customer_id = auth::id()
     * @return JsonResponse
     */
    public function customerOrder()
    {
        try {
            return response()->json(Order::where('customer_id', Auth::id())
                ->with("check")
                ->get()
                , 200);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }


    /**
     * To change status of order
     * @param OrderChangeStatusRequest $request
     * @return JsonResponse
     */
    public function changeStatusOrder(OrderChangeStatusRequest $request)
    {
        try {
            $types = StatusTypesInterface::TYPES;
            if (!in_array($request->status, $types))
                return response()->json('Błędnyy status', 422);
            if ($order = Order::where('token', $request->token)->first()) {
                $order->status = $request->status;
                $order->save();
                broadcast(new OrderChanged())->toOthers();
                return response()->json("Status zmieniony", 200);
            }
            return response()->json('Błędne id zamówienia', 500);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * Get possible order status types
     * @return JsonResponse
     */
    public function fetchOrderStatusTypes()
    {
        try {
            return response()->json(StatusTypesInterface::TYPES, 200);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param NewOrderFromWorkerRequest $request ['table_id', items]
     * @return JsonResponse
     */
    public function storeNewOrderFromWorker(NewOrderFromWorkerRequest $request)
    {
        try {
            $order = new Order();
            $order->token = uniqid();
            $order->takeaway = false;
            $order->table()->associate($request->table_id);
            $order->status = StatusTypesInterface::TYPE_ORDERED;
            $order->worker()->associate(Auth::user());
            $order->save();
            (new OrderService())->addItems($order, $request->items);
            broadcast(new OrderChanged())->toOthers();
            return response()->json("Zamówienie złożone", 200);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param NewOrderOnlineRequest $request
     * @return JsonResponse
     */
    public function storeNewOrderOnline(NewOrderOnlineRequest $request)
    {
        try {
            $order = new Order();
            $order->token = uniqid();
            if ($user = Auth::user()) {
                $order->email = $user->email;
                $order->customer()->associate($user);
            } else {
                if ($request->email) {
                    $order->email = $request->email;
                } else {
                    return response()->json("Mail wymagany", 422);
                }
            }
            $order->takeaway = $request->takeaway;
            if (!$request->takeaway) {
                $order->address = json_encode($request->address);
            }
            $order->status = StatusTypesInterface::TYPE_ORDERED;
            $order->save();
            (new OrderService())->addItems($order, $request->items);
            broadcast(new OrderChanged())->toOthers();
            (new OrderOnlineMail($order->email, $order->token))->sendMail();
            return response()->json("Zamówienie złożone", 200);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * Show of order
     * @param $token
     * @return JsonResponse
     */
    public function loadOrder($token)
    {
        try {
            $tokens = Order::pluck('token')->toArray();
            if (!in_array($token, $tokens)) {
                abort(403);
            }
            $dishes = [];
            $sum = 0;
            if ($order = Order::where('token', $token)->first()) {
                $items = Check::where('order_id', $order->id)->with('dish')->get();
                foreach ($items as $item) {
                    array_push($dishes, [
                        "id" => $item->dish_id,
                        "name" => $item->dish->name,
                        'price' => (float)$item->dish->price,
                        'amount' => $item->amount]);
                    $sum += (float)$item->dish->price * (float)$item->amount;
                }
                return response()->json(["dishes" => $dishes, 'sum' => $sum, 'status' => $order->status], 200);
            }
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    public function deleteOrder($token)
    {
        try {
            $tokens = Order::pluck('token')->toArray();
            if (!in_array($token, $tokens)) {
                abort(403);
            }
            if ($order = Order::where('token', $token)->first()) {
                (new OrderService())->deleteCheck($order->id);
                $order->delete();
                broadcast(new OrderChanged())->toOthers();
                return response()->json("Zamówienie usunięte", 200);
            }
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param NewOrderFromWorkerRequest $request
     * @return JsonResponse
     */
    public function updateOrderFromWorker(NewOrderFromWorkerRequest $request)
    {
        try {
            $tokens = Order::pluck('token')->toArray();
            if (!in_array($request->token, $tokens)) {
                abort(403);
            }
            if ($order = Order::where('token', $request->token)->first()) {
                $items = Check::where('order_id', $order->id)->with('dish')->get();
                foreach ($items as $item) {
                    $item->delete();
                }
                (new OrderService())->addItems($order, $request->items);
                broadcast(new OrderChanged())->toOthers();
                return response()->json("Zamówienie pomyślnie edytowane", 200);
            }
            dd("here");
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        } catch (Exception $e) {
            dd($e);
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param NewOrderOnlineRequest $request
     * @return JsonResponse
     */
    public function updateOnlineOrder(NewOrderOnlineRequest $request)
    {
        try {
            $tokens = Order::pluck('token')->toArray();
            if (!in_array($request->token, $tokens)) {
                abort(403);
            }
            if ($order = Order::where('token', $request->token)->first()) {
                $items = Check::where('order_id', $order->id)->with('dish')->get();
                if ($order->status != StatusTypesInterface::TYPE_ORDERED && $order->status !=
                    StatusTypesInterface::TYPE_IN_PROGRESS) {
                    return response()->json("Zamówieniezostało już wykonane, edycja nie jest możliwa", 422);
                }
                $order->takeaway = $request->takeaway;
                if (!$request->takeaway) {
                    $order->address = json_encode($request->address);
                }
                $order->save();
                foreach ($items as $item) {
                    $item->delete();
                }
                (new OrderService())->addItems($order, $request->items);
                broadcast(new OrderChanged())->toOthers();
                return response()->json("Zamówienie pomyślnie edytowane", 200);
            }
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        } catch (Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}

