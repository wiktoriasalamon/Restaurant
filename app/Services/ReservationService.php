<?php


namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ReservationService
{
    /**
     * Check if time for new reservation is after now
     * @param string $time
     * @param string $date
     * @return bool
     */
    public function timeForSameDay(string $time, string $date): bool
    {
        if (Carbon::now()->format('Y-m-d') < $date) {
            return true;
        } elseif ($time >=date('H:i:s', strtotime(config('reservation.timeForSameDay')))) {
            return true;
        }
        return false;

    }

    public function oneADay(string $date, string $email): bool
    {

    }


}
