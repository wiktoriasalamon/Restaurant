<?php

/** @var Factory $factory */

use App\Models\Table;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;


$factory->define(Table::class, function (Faker $faker) {
    return [
        'size' => $faker->numberBetween(1,6),
        'occupied_since'=>null,
    ];
});



