<?php


namespace App\Services;

use App\Interfaces\StatusTypesInterface;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{


    /**
     * @param string $date
     * @return array
     */
    public function tablesByDate(string $date): array
    {
        $tables = Table::all();
        $tableArray = [];
        foreach ($tables as $table) {
            $reservationSince = null;
            $reservations=Reservation::where('table_id',$table->id)->get();
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
     * Add items to give order
     * @param Order $order
     * @param $items [[ammount, dishId],[],..
     */
    public function addItems(Order $order, $items)
    {
        foreach ($items as $item){
            $check = new \App\Models\Check();
            $check->amount = $item['amount'];
            $check->dish()->associate(\App\Models\Dish::find($item['dishId']));
            $check->order()->associate($order);
            $check->save();
        }
    }
}
