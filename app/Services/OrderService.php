<?php


namespace App\Services;

use App\Interfaces\StatusTypesInterface;
use App\Models\Check;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * @param string $date
     * @param $tables
     * @return array
     */
    public function tablesByDate(string $date, $tables): array
    {
        $tableArray = [];
        foreach ($tables as $table) {
            $reservationSince = null;
            $reservations = Reservation::where('table_id', $table->id)->get();
            foreach ($reservations as $reservation) {
                if ($reservation->date == $date) {
                    $reservationSince = $reservation->start_time;
                }
            }
            array_push($tableArray, [
                'table' => $table,
                'reservation_since' => $reservationSince
            ]);
        }
        return $tableArray;
    }

    /**
     * @param string $date
     * @param $table
     * @return array
     */
    public function tableByDate(string $date, $table): array
    {
        $reservationSince = null;
        $reservations = Reservation::where('table_id', $table->id)->get();
        foreach ($reservations as $reservation) {
            if ($reservation->date == $date) {
                $reservationSince = $reservation->start_time;
            }
        }
        return [
            'table' => $table,
            'reservation_since' => $reservationSince
        ];
    }

    /**
     * Add items to give order
     * @param Order $order
     * @param $items [[ammount, dishId],[],..
     */
    public function addItems(Order $order, $items)
    {
        foreach ($items as $item) {
            $check = new \App\Models\Check();
            $check->amount = $item['amount'];
            $check->dish()->associate(\App\Models\Dish::find($item['dishId']));
            $check->order()->associate($order);
            $check->save();
        }
    }

    /**
     * @return array of tables with order served by auth user
     * and empty tables without any orders
     */
    public function myTablesWithReservation()
    {
        //stoliki kelnera
        $waiterTables = Table::whereHas("order", function ($q) {
            $q->where("worker_id", Auth::id());
        })->get()->load('order');

        //stoliki bez zamówień
        $tables = Table::doesnthave('order')->get();

        return $this->tablesByDate(Carbon::now()->format('Y-m-d'), $waiterTables->merge($tables));
    }

    /**
     * Function to change all order i nthis table to finisched
     * @param $tableId
     */
    public function closeTable($tableId)
    {
        $orders = Order::where('table_id', $tableId)->get();

        foreach ($orders as $order){
            $order->status = StatusTypesInterface::TYPE_FINISHED;
            $order->save();
        }
    }

    /**
     * Function to delete all check of order
     * (for delete order purpose)
     * @param $orderId
     */
    public function deleteCheck($orderId)
    {
        $items = Check::where('order_id', $orderId)->get();

        foreach ($items as $item){
            $item->delete();
        }
    }
}
