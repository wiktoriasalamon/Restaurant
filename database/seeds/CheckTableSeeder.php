<?php

use Illuminate\Database\Seeder;

class CheckTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = \App\Models\Order::all();

        try {
            foreach ($orders as $order) {
                for ($i = 1; $i < random_int(1, 5); $i++) {
                    $check = new \App\Models\Check();
                    $check->amount = random_int(1, 3);
                    $check->dish()->associate(\App\Models\Dish::find(random_int(1, \App\Models\Dish::all()->count())));
                    $check->order()->associate($order);
                    $check->save();
                }
            }
        } catch (Exception $e) {
            echo $e."\n";
        }
    }
}
