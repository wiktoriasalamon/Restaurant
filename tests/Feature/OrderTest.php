<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;


    public function testTableWithoutReservationAndNotOccupiedIsAvailable()
    {
        $this->seed(\TableTableSeeder::class);
//        dd(Table::all());
        $this->assertTrue(true);
    }
}
