<?php namespace App\Providers;

use App\Services\LDAPUserService;
use App\Services\ReservationService;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->reservation();

    }

    /**
     * Rules for reservations
     */
    private function reservation()
    {
        $this->app['validator']->extend('timeForSameDayReservation', function ($attribute, $value, $parameters)
        {
            if ((new ReservationService())->timeForSameDay($value, $parameters[0])) {
                return true;
            }
            return false;
        });

        $this->app['validator']->extend('oneADayReservation', function ($attribute, $value, $parameters)
        {
            if ((new ReservationService())->oneADay($value, $parameters[0])) {
                return true;
            }
            return false;
        });

    }

    public function register()
    {

    }
}
