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
            'Kategoria 1',
            'Kategoria 2',
            'Kategoria 3',
            'Kategoria 4'
        ];

        foreach ($categories as $category) {
            $dishCategory = new \App\Models\DishCategory();
            $dishCategory->name = $category;
            $dishCategory->save();
        }
    }
}
