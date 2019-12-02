<?php

namespace Tests\Feature;

use App\Interfaces\StatusTypesInterface;
use App\Models\Check;
use App\Models\Order;
use App\Models\Table;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;


    public function testTablesByDate()
    {
        $this->seed(\TableTableSeeder::class);
        $this->seed(\ReservationTableSeeder::class);
        $service = \Mockery::mock(OrderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $response = $service->tablesByDate(Carbon::today(), Table::all());
        $this->assertIsArray($response);
        foreach ($response as $item) {
            $this->assertArrayHasKey('reservation_since', $item);
            $this->assertArrayHasKey('table', $item);
        }
    }

    public function testTableByDate()
    {
        $this->seed(\TableTableSeeder::class);
        $this->seed(\ReservationTableSeeder::class);
        $service = \Mockery::mock(OrderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $response = $service->tableByDate(Carbon::today(), Table::first());
        $this->assertIsArray($response);
        $this->assertArrayHasKey('reservation_since', $response);
        $this->assertArrayHasKey('table', $response);
    }

    public function testAddItems()
    {
        $this->seed(\DatabaseSeeder::class);
        $service = \Mockery::mock(OrderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $initialSize = Check::all()->count();
        $service->addItems(Order::first(), [['amount'=>"111",'dishId'=>"2"], ['amount'=>"555",'dishId'=>"1"], ['amount'=>"333",'dishId'=>"3"]]);
        $checks = Check::all()->toArray();

        $this->assertCount($initialSize + 3, $checks);
    }

    public function testCloseTable()
    {
        $this->seed(\DatabaseSeeder::class);
        $service = \Mockery::mock(OrderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $tables = Table::all();
        foreach ($tables as $table){
            $service->closeTable($table->id);
        }
        $tables = Table::all()->load('order');
        foreach ($tables as $table){
            foreach ($table->order as $item){
                $this->assertEquals(StatusTypesInterface::TYPE_FINISHED, $item->status);
            }
        }
    }

    public function testDeleteOrder()
    {
        $this->seed(\DatabaseSeeder::class);
        $service = \Mockery::mock(OrderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $orders = Order::all();
        foreach ($orders as $order){
            $service->deleteCheck($order->id);
        }
        $this->assertEquals([],Check::all()->toArray());
    }
}
