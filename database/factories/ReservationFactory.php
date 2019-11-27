<?php

/** @var Factory $factory */

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;


$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'start_time'=>$faker->time($format = 'H:i:s', $max = 'now'),
        'email'=>$faker->email,
        'phone'=>$faker->phoneNumber,
        'table_id'=>$faker->numberBetween(1,6)
    ];
});



