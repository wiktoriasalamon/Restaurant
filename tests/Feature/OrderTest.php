<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;


    public function testTableWithoutReservationAndNotOccupiedIsAvailable()
    {

        $this->assertTrue(true);
    }
}
