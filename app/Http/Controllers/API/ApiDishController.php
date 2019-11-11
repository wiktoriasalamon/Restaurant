<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishRequest;
use App\Models\Dish;
use App\Models\DishCategory;
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
    public function load (Dish $dish)
    {
        try {
            return response()->json($dish);
        } catch (\Exception $e) {
            Log::notice("Error deleting data all:" . $e);
            Log::notice("Error deleting data msg:" . $e->getMessage());
            Log::notice("Error deleting data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
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

    /**
     * @param DishRequest $request [name,price,category_id]
     * @return JsonResponse
     */
    public function store(DishRequest $request)
    {
        try {
            $dish = new Dish();
            $dish->name = $request->name;
            $dish->price = $request->price;
            $dish->category()->associate(DishCategory::findOrFail($request->category_id));
            $dish->save();
            return response()->json(['message' => "Danie zostało pomyślnie zapisane."], 200);
        } catch (\Exception $e) {
            Log::notice("Error storing data all:" . $e);
            Log::notice("Error storing data msg:" . $e->getMessage());
            Log::notice("Error storing data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param DishRequest $request [id,name,price,category_id]
     * @return JsonResponse
     */
    public function update (DishRequest $request)
    {
        try {
            $dish = Dish::findOrFail($request->id);
            $dish->name = $request->name;
            $dish->price = $request->price;
            $dish->category()->associate(DishCategory::findOrFail($request->category_id));
            $dish->save();
            return response()->json(['message' => "Danie zostało pomyślnie zapisane."], 200);
        } catch (\Exception $e) {
            Log::notice("Error updating data all:" . $e);
            Log::notice("Error updating data msg:" . $e->getMessage());
            Log::notice("Error updating data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}
