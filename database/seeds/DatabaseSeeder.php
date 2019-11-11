<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(RolePermTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(RoleUserTableSeeder::class);
         $this->call(DishCategoryTableSeeder::class);
         $this->call(DishTableSeeder::class);
         $this->call(TableTableSeeder::class);
         $this->call(ReservationTableSeeder::class);
         $this->call(OrderTableSeeder::class);
         $this->call(CheckTableSeeder::class);
    }
}
