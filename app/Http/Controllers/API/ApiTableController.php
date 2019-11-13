<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TableRequest;
use App\Models\Dish;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiTableController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Table[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return response()->json(Table::all()->load(['reservation', 'order']));
    }

    /**
     * @param Table $table
     * @return JsonResponse
     */
    public function delete(Table $table)
    {
        try {
            if ($table->occupied_since) {
                return response()->json('Obecnie stolik jest zajęty', 419);
            }
            if ($reservation = Reservation::where('table_id', $table->id)->first()) {
                return response()->json('Stolik posiada rezerwacje', 419);
            }
            $table->delete();
            return response()->json("Stolik usunięty", 201);
        } catch (\Exception $e) {
            Log::notice("Error deleting data all:" . $e);
            Log::notice("Error deleting data msg:" . $e->getMessage());
            Log::notice("Error deleting data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * Show for waiter
     * @param Table $table
     * @return JsonResponse
     */
    public function loadTableForWaiter(Table $table)
    {
        try {
            return response()->json((new OrderService)->tableByDate(Carbon::now()->format('Y-m-d'),
                $table->load('order')), 200);
        } catch (\Exception $e) {
            dd($e);
            Log::notice("Error deleting data all:" . $e);
            Log::notice("Error deleting data msg:" . $e->getMessage());
            Log::notice("Error deleting data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
    /**
     * @param Table $table
     * @return JsonResponse
     */
    public function load(Table $table)
    {
        try {
            return response()->json($table, 200);
        } catch (\Exception $e) {
            Log::notice("Error deleting data all:" . $e);
            Log::notice("Error deleting data msg:" . $e->getMessage());
            Log::notice("Error deleting data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param TableRequest $request [name]
     * @return JsonResponse
     */
    public function store(TableRequest $request)
    {
        try {
            $table = new Table();
            $table->size = $request->size;
            $table->occupied_since = null;
            $table->save();
            return response()->json(['message' => "Stolik został pomyślnie zapisany."], 200);
        } catch (\Exception $e) {
            Log::notice("Error storing data all:" . $e);
            Log::notice("Error storing data msg:" . $e->getMessage());
            Log::notice("Error storing data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param TableRequest $request [id,name]
     * @return JsonResponse
     */
    public function update (TableRequest $request)
    {
        try {
            $table = Table::findOrFail($request->id);
            $table->size = $request->size;
            $table->occupied_since = null;
            $table->save();
            return response()->json(['message' => "Stolik został pomyślnie zapisany."], 200);
        } catch (\Exception $e) {
            Log::notice("Error updating data all:" . $e);
            Log::notice("Error updating data msg:" . $e->getMessage());
            Log::notice("Error updating data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * Return array of tables served by current logged user
     * @return JsonResponse
     */
    public function myTables()
    {
        return response()->json((new OrderService())->myTablesWithReservation(), 200);
    }

    /**
     * @param Table $table
     * @return JsonResponse
     */
    public function openTable (Table $table)
    {
        try {
            if ($table->occupied_since != null) {
                return response()->json('stolik jest zajęty', 422);
            }
            $table->occupied_since = Carbon::now();
            $table->save();
            return response()->json(['message' => "Stolik został pomyślnie otworzony."], 200);
        } catch (\Exception $e) {
            Log::notice("Error updating data all:" . $e);
            Log::notice("Error updating data msg:" . $e->getMessage());
            Log::notice("Error updating data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param Table $table
     * @return JsonResponse
     */
    public function closeTable (Table $table)
    {
        try {
            $table->occupied_since = null;
            (new OrderService())->closeTable($table->id);
            $table->save();
            return response()->json(['message' => "Stolik został pomyślnie zamknięty."], 200);
        } catch (\Exception $e) {
            Log::notice("Error updating data all:" . $e);
            Log::notice("Error updating data msg:" . $e->getMessage());
            Log::notice("Error updating data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}
