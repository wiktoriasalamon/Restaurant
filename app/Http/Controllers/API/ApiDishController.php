<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiDishController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Dish[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return response()->json(Dish::all()->load('category'));
    }

    /**
     * @param Dish $dish
     * @return JsonResponse
     */
    public function delete(Dish $dish)
    {
        try {
            $dish->delete();
            return response()->json("Danie usunięte", 201);
        } catch (\Exception $e) {
            Log::notice("Error deleting data all:" . $e);
            Log::notice("Error deleting data msg:" . $e->getMessage());
            Log::notice("Error deleting data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}
