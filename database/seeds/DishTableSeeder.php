<?php

use Illuminate\Database\Seeder;

class DishTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Models\DishCategory::all();
        foreach ($categories as $category) {
            try {
                for ($i = 1; $i < random_int(4, 8); $i++) {
                    $dish = new \App\Models\Dish();
                    $dish->name = "Danie " . $i;
                    $dish->price = random_int(500,5000) / 100;
                    $dish->category()->associate($category);
                    $dish->save();
                }
            } catch (Exception $e) {
                echo $e."\n";
            }
        }
    }
}
