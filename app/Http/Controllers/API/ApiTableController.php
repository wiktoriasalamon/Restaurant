<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            if($table->occupied_since){
                return response()->json('Obecnie stolik jest zajęty', 419);
            }
            if($reservation = Reservation::where('table_id', $table->id)->first()){
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
}
