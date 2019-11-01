<?php


namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ReservationService
{
    public function ifEnoughTime(string $time, string $date): bool
    {

    }

    public function oneADay(string $date, string $email):bool
    {

    }

    public function fetchAvailableTables(string $date, int $size)
    {
        $tables=[];
        if(Carbon::now()->format('Y-m-d')==$date){
           $table=Table::where('size',$size)->where('occupied_since',null)->whereDoesntHave('reservation', function ($query) use ($date) {
                $query->where('date', 'like', $date)->where('start_time','>=','21:00:00');
            })->first();
           dd($table);
           for($i=9; $i<=24; $i++)
           {
             array_push($tables,['time'=>$i.':00', 'table'=>$table]);
           }
           return $tables;

        }else{
            $table=Table::where('size',$size)->whereDoesntHave('reservation', function ($query) use ($date) {
                $query->where('date', 'like', $date);
            })->first()->id;
            for($i=9; $i<=24; $i++)
            {
                array_push($tables,['time'=>$i.':00', 'table'=>$table]);
            }
            return $tables;
        }

    }

}
