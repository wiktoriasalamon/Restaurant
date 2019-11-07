<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emails = User::groupBy('email')->pluck('email');
        try {
            for ($day = 0; $day < 10; $day++) {
                for ($i = 1; $i < sizeof($emails) && $i < \App\Models\Table::all()->count(); $i++) {
                    if (random_int(0, 1)) {
                        $reservation = new \App\Models\Reservation();
                        $reservation->date = \Carbon\Carbon::today();
                        $reservation->start_time = rand(9,24).':00';
                        $reservation->table()->associate(\App\Models\Table::find($i));
                        $reservation->email = $emails[$i];
                        $reservation->save();
                    }
                }
            }
        } catch (Exception $e) {
            echo $e."\n";
        }
    }
}
