<?php

use Illuminate\Database\Seeder;

class DishCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Dania dnia',
            'Pizza',
            'Pasta',
            'Ravioli',
            'Napoje bez alkoholowe'
        ];

        foreach ($categories as $category) {
            $dishCategory = new \App\Models\DishCategory();
            $dishCategory->name = $category;
            $dishCategory->save();
        }
    }
}
