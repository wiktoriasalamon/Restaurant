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
//                    'Dania dnia',
//            'Pizza',
//            'Pasta',
//            'Ravioli',
//            'Napoje bez alkoholowe'
        $menu = [
            [
                ['name' => 'Schabowy Drwala', 'price' => 20],
                ['name' => 'Pierogi ruskie', 'price' => 10],
                ['name' => 'Makaron z kurczakiem i szpinakiem', 'price' => 12],
                ['name' => 'Lassagne', 'price' => 15],
                ['name' => 'Sałatka z łososiem', 'price' => 15],
            ],
            [
                ['name' => 'Pizza Margherita', 'price' => 25],
                ['name' => 'Pizza Prosciutto', 'price' => 32],
                ['name' => 'Pizza Quattro formaggi', 'price' => 29],
                ['name' => 'Pizza Capriciosa', 'price' => 27],
                ['name' => 'Pizza Salame', 'price' => 25],
            ],
            [
                ['name' => 'Pesto di prezzemolo', 'price' => 24],
                ['name' => 'Spaghetti Bolognese', 'price' => 25],
                ['name' => 'Tagliatelle szpinakowe al Tonno', 'price' => 28],
                ['name' => 'Spaghetti Frutti di mare', 'price' => 40]
            ],
            [
                ['name' => 'Ravioli Szpinakowe', 'price' => 24],
                ['name' => 'Ravioli Szafran', 'price' => 30],
                ['name' => 'Ravioli Spianata picante', 'price' => 28]
            ],
            [
                ['name' => 'Woda filtrowana 500 ml', 'price' => 5],
                ['name' => 'Woda filtrowana 1000 ml', 'price' => 9],
                ['name' => 'Bio organiczna Cola Premium 335ml', 'price' => 10],
                ['name' => 'Bio lemoniada Cytryna 335ml', 'price' => 14],
                ['name' => 'Bio herbata mrożona zielona 335ml', 'price' => 14],
            ]
        ];
        $categories = \App\Models\DishCategory::all();
        $i = 0;
        foreach ($categories as $category) {
            foreach ($menu[$i] as $item) {
                try {
                    $dish = new \App\Models\Dish();
                    $dish->name = $item['name'];
                    $dish->price = $item['price'];
                    $dish->category()->associate($category);
                    $dish->save();
                } catch (Exception $e) {
                    echo $e . "\n";
                }
            }
            $i++;
        }
    }
}
