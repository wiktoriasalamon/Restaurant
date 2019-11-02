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

    /**
     * @param string $date
     * @param string $time
     * @param int $size
     * @return mixed
     */
    public function fetchAvailableTable(string $date, string $time,int $size)
    {
        if(Carbon::now()->format('Y-m-d')==$date){
           return Table::where('size',$size)->where('occupied_since',null)->whereDoesntHave('reservation', function ($query) use ($date, $time) {
                $query->where('date', 'like', $date)->where('start_time','>=',$time);
            })->first();

        }else{
            return Table::where('size',$size)->whereDoesntHave('reservation', function ($query) use ($date) {
                $query->where('date', 'like', $date);
            })->first()->id;
        }

    }

}
