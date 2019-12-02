<?php namespace App\Providers;


use App\Services\ReservationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->reservation();

    }

    /**
     * Rules for reservations
     */
    private function reservation()
    {
        $this->app['validator']->extend('timeForSameDayReservation', function ($attribute, $value, $parameters) {
            if ((new ReservationService())->timeForSameDay($value, $parameters[0])) {
                return true;
            }
            return false;
        });

        $this->app['validator']->extend('oneADayReservation', function ($attribute, $value, $parameters) {
            $email = $parameters[0];
            if (!$parameters[0]) {
                $email = Auth::user()->email;
            }
            if ((new ReservationService())->oneADay($value, $email)) {
                return true;
            }
            return false;
        });

        $this->app['validator']->extend('openHours', function ($attribute, $value, $parameters) {
            if ((new ReservationService())->openHours($value)) {
                return true;
            }
            return false;
        });
        $this->app['validator']->extend('tableAvailable', function ($attribute, $value, $parameters) {
            if ((new ReservationService())->isTableAvailable($value, $parameters[0], $parameters[1])) {
                return true;
            }
            return false;
        });
        $this->app['validator']->extend('tablesAvailable', function ($attribute, $value, $parameters) {
            foreach ($value as $tableId) {
                if (!(new ReservationService())->isTableAvailable($tableId, $parameters[0], $parameters[1])) {
                    return false;
                }
            }
            return true;
        });

    }

    public function register()
    {

    }
}
