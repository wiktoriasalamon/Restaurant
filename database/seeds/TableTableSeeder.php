<?php

use Illuminate\Database\Seeder;

class TableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            for ($i = 0; $i < 10; $i++) {
                $table = new \App\Models\Table();
                $table->size = random_int(1, 4) * 2;
                $table->occupied_since = random_int(0, 1) ? null : "15:00";
                $table->save();
            }
        } catch (Exception $e) {
            echo $e."\n";
        }
    }
}
