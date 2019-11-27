<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * reservation for today, no other reservation for the table and table is not occupied
     */
    public function testTableWithoutReservationAndNotOccupiedIsAvailable()
    {
        $this->fakeModel(Table::class, []);
        $now = Carbon::now();
        $free = $this->getReservationService()->isTableAvailable(1, $now->format('Y-m-d'), '10:00:00');
        self::assertTrue($free);
    }

    /**
     * reservation for today, table had reservation 3 hours earlier but now table is not occupied
     */
    public function testTableWithReservationTodayAndNotOccupiedIsAvailableToday()
    {
        $this->fakeModel(Table::class, []);
        $tableId = 1;
        $date = Carbon::now()->format('Y-m-d');
        $startTime = '08:00:00';
        $this->fakeModel(Reservation::class, ['table_id' => $tableId, 'date' => $date, 'start_time' => $startTime]);
        $free = $this->getReservationService()->isTableAvailable($tableId, $date, '10:00:00');
        self::assertTrue($free);
    }


    /**
     * reservation for today, table has reservation in 3 hours, now table is not occupied
     */
    public function testTableWithReservationTodayAndNotOccupiedIsNotAvailableToday()
    {
        $this->fakeModel(Table::class, []);
        $tableId = 1;
        $date = Carbon::now()->format('Y-m-d');
        $startTime = '13:00:00';
        $this->fakeModel(Reservation::class, ['table_id' => $tableId, 'date' => $date, 'start_time' => $startTime]);
        $free = $this->getReservationService()->isTableAvailable($tableId, $date, '10:00:00');
        self::assertFalse($free);
    }

    /**
     * reservation not for today, table doesn't have reservation for the day
     */
    public function testTableWithReservationIsAvailableNotToday()
    {
        $tableId = 1;
        $this->fakeTableReservationNotToday($tableId);
        $now = Carbon::now();
        $free = $this->getReservationService()->isTableAvailable($tableId, $now->addDay()->format('Y-m-d'), '10:00:00');
        self::assertTrue($free);
    }

    /**
     * reservation not for today, table has reservation for the day
     */
    public function testTableWithReservationIsNotAvailableNotToday()
    {
        $tableId = 1;
        $this->fakeTableReservationNotToday($tableId);
        $now = Carbon::now();
        $free = $this->getReservationService()->isTableAvailable($tableId, $now->subDay()->format('Y-m-d'), '10:00:00');
        self::assertFalse($free);
    }


    /**
     * fake reservation not for today
     * @param int $tableId
     */
    private function fakeTableReservationNotToday(int $tableId): void
    {
        $this->fakeModel(Table::class, []);
        $date = Carbon::now()->subDay()->format('Y-m-d');
        $this->fakeModel(Reservation::class, ['table_id' => $tableId, 'date' => $date]);
    }

    /**
     * @param string $class
     * @param array $fields
     * @return mixed
     */
    private function fakeModel(string $class, array $fields)
    {
        return (factory($class)->create($fields));
    }


    private function fakeTables(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            $this->fakeModel(Table::class, ['size' => $i]);
        }
    }

    /*
     * get reservation service
     */
    private function getReservationService()
    {
        return (new ReservationService());
    }


    /*
     * test function freeTablesByDate
     */
    public function testFreeTablesByDate()
    {
        $this->fakeTables();
        $this->fakeModel(Reservation::class, ['table_id' => 3, 'date' => '2019-11-21']);
        $this->fakeModel(Reservation::class, ['table_id' => 4, 'date' => '2019-11-23']);
        $tables = $this->getReservationService()->freeTablesByDate('2019-11-22');
        $this->assertEquals($tables[0]->id, 1);
        $this->assertEquals($tables[1]->id, 2);
        $this->assertEquals($tables[2]->id, 3);
        $this->assertEquals($tables[3]->id, 4);
    }


    /*
     * test function fetchReservation
     */
    public function testFetchReservation()
    {
        $tableSize = 6;
        $tableId = 1;
        $date = '2019-11-26';
        $startTime = '10:00:00';
        $this->fakeModel(Table::class, ['size' => $tableSize]);
        $this->fakeModel(Reservation::class, ['table_id' => $tableId, 'date' => $date, 'start_time' => $startTime]);
        $reservation = $this->getReservationService()->fetchReservation(1);
        $this->assertIsArray($reservation);
        $this->assertArrayHasKey('date', $reservation);
        $this->assertArrayHasKey('startTime', $reservation);
        $this->assertArrayHasKey('tableSize', $reservation);
        $this->assertEquals($reservation['date'], $date);
        $this->assertEquals($reservation['startTime'], $startTime);
        $this->assertEquals($reservation['tableSize'], $tableSize);

    }

    /*
     * test if user can have only one reservation a day
     */
    public function testOneADayReservation()
    {
        $email = 'test@test.pl';
        $date = '2019-11-26';
        $this->fakeModel(User::class, ['email' => $email]);
        $this->fakeModel(Reservation::class, ['email' => $email, 'date' => $date]);
        $one = $this->getReservationService()->oneADay($date, $email);
        $this->assertFalse($one);
    }

    public function testFindTableBySizeTableAvailable()
    {
        $this->fakeTables();
        $first = $this->fakeModel(Reservation::class, ['date' => '2019-11-26', 'table_id' => 1]);
        $second = $this->fakeModel(Reservation::class, ['date' => '2019-11-24', 'table_id' => 3]);
        $this->assertTrue($first->findTable(2));
        $this->assertFalse($first->findTable(1));
        $this->assertFalse($second->findTable(3));
        $this->assertTrue($second->findTable(4));
    }

    public function testWorkerReservationsReturnsArray()
    {
        $this->assertIsArray($this->getReservationService()->workerReservations('2019-11-26'));
    }

    public function testWorkerReservationsStatusFinished()
    {
        $this->fakeModel(Table::class, []);
        $this->fakeModel(Reservation::class, ['date' => '2019-11-21', 'table_id' => 1]);
        $this->fakeModel(Reservation::class, ['date' => '2019-11-21', 'table_id' => 1]);
        $this->fakeModel(Reservation::class, ['date' => '2019-11-21', 'table_id' => 1]);
        $this->fakeModel(Reservation::class, ['date' => '2019-11-23', 'table_id' => 1]);
        $reservations = $this->getReservationService()->workerReservations('2019-11-21');
        $this->assertIsArray($reservations);
        $this->assertCount(3,$reservations);
        $this->assertArrayHasKey('status',$reservations[0]);
        $this->assertEquals($reservations[0]['status'],'finished');
        $this->assertArrayHasKey('status',$reservations[1]);
        $this->assertEquals($reservations[0]['status'],'finished');
        $this->assertArrayHasKey('status',$reservations[2]);
        $this->assertEquals($reservations[0]['status'],'finished');
    }
}
