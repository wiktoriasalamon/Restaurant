<?php


namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;
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



}
