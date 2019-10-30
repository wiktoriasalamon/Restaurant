<?php namespace App\Providers;

use App\Services\LDAPUserService;
use App\Services\ReservationService;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->app['validator']->extend('enoughTimeBefore', function ($attribute, $value, $parameters)
        {
            if ((new ReservationService())->ifEnoughTime($value, $parameters[0])) {
                return true;
            }
            return false;
        });

        $this->app['validator']->extend('oneADay', function ($attribute, $value, $parameters)
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
