<?php namespace App\Providers;


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

        $this->app['validator']->extend('openHours', function ($attribute, $value, $parameters)
        {
            if ((new ReservationService())->openHours($value)) {
                return true;
            }
            return false;
        });
        $this->app['validator']->extend('tableAvailable', function ($attribute, $value, $parameters)
        {
            if ((new ReservationService())->isTableAvailable($value, $parameters[0], $parameters[1])) {
                return true;
            }
            return false;
        });

    }

    public function register()
    {

    }
}
