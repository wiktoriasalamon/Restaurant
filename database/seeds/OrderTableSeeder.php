<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = \App\Models\Table::all();
//      zamówienia na miejscu zgodne z zajętymi stolikami w danym momencie
        try {
            foreach ($tables as $table){
                if ($table->occupied_since) {
                    for ($i = 0; $i < random_int(1, $table->size); $i++) {
                        $order = new \App\Models\Order();
                        $order->token = uniqid();
                        $order->takeaway = false;
                        $order->worker()->associate(\App\Models\User::find(1));
                        $order->table()->associate($table);
                        $order->address = null;
                        $order->save();
                    }
                }
            }
//zamówienie online zalogowany
            for ($i = 0; $i < random_int(2, 4); $i++) {
                $order = new \App\Models\Order();
                $user = \App\Models\User::find(3);
                $order->token = uniqid();
                $order->takeaway = false;
                $order->email = $user->email;
                $order->customer()->associate($user);
                $order->address = json_encode("adres");
                $order->save();
            }

        } catch (Exception $e) {
            echo $e."\n";
        }
    }
}
