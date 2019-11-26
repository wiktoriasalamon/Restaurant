<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Table;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * reservation for today, no other reservation for the table and table is not occupied
     */
    public function testTableWithoutReservationAndNotOccupiedIsAvailable()
    {
        $this->fakeModel(Table::class,[]);
        $now = Carbon::now();
        $free = (new ReservationService())->isTableAvailable(1, $now->format('Y-m-d'), $now->format('H:i'));
        self::assertTrue($free);
    }

    /**
     * reservation for today, table had reservation 3 hours earlier but now table is not occupied
     */
    public function testTableWithReservationTodayAndNotOccupiedIsAvailableToday()
    {
        $this->fakeModel(Table::class,[]);
        $tableId = 1;
        $date =Carbon::now()->format('Y-m-d');
        $startTime =Carbon::now()->subHours(3)->format('H:i');
            $this->fakeModel(Reservation::class, ['table_id' => $tableId, 'date' => $date, 'start_time' => $startTime]);
        $free = (new ReservationService())->isTableAvailable($tableId, $date, Carbon::now()->format('H:i'));
        self::assertTrue($free);
    }



    /**
     * reservation for today, table has reservation in 3 hours, now table is not occupied
     */
    public function testTableWithReservationTodayAndNotOccupiedIsNotAvailableToday()
    {
        $this->fakeModel(Table::class,[]);
        $tableId = 1;
        $date =Carbon::now()->format('Y-m-d');
        $startTime =Carbon::now()->addHours(3)->format('H:i');
        $this->fakeModel(Reservation::class, ['table_id' => $tableId, 'date' => $date, 'start_time' => $startTime]);
        $free = (new ReservationService())->isTableAvailable($tableId, $date, Carbon::now()->format('H:i'));
        self::assertFalse($free);
    }

    /**
     * reservation not for today, table doesn't have reservation for the day
     */
    public function testTableWithReservationIsAvailableNotToday()
    {
        $tableId=1;
        $this->fakeTableReservationNotToday($tableId);
        $now=Carbon::now();
        $free = (new ReservationService())->isTableAvailable($tableId, $now->addDay()->format('Y-m-d'), $now->format('H:i'));
        self::assertTrue($free);
    }

    /**
     * reservation not for today, table has reservation for the day
     */
    public function testTableWithReservationIsNotAvailableNotToday()
    {
        $tableId=1;
        $this->fakeTableReservationNotToday($tableId);
        $now=Carbon::now();
        $free = (new ReservationService())->isTableAvailable($tableId, $now->subDay()->format('Y-m-d'), $now->format('H:i'));
        self::assertFalse($free);
    }


    /**
     * fake reservation not for today
     * @param int $tableId
     */
    private function fakeTableReservationNotToday(int $tableId):void
    {
        $this->fakeModel(Table::class,[]);
        $date =Carbon::now()->subDay()->format('Y-m-d');
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


}
