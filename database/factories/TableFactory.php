<?php

/** @var Factory $factory */

use App\Models\Table;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;


$factory->define(Table::class, function (Faker $faker) {
    return [
        'size' => $faker->numberBetween(1,6),
        'occupied_since'=>$faker->word,
        'description'=>$faker->word,
        'topic'=>$faker->word,
        'residential_area_id'=>$faker->numberBetween(1,6),
        'council_form_category_id'=>$faker->numberBetween(1,6)

    ];
});



