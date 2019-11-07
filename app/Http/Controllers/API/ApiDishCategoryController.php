<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\DishCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiDishCategoryController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return DishCategory[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return response()->json(DishCategory::all());
    }

    /**
     * @param DishCategory $dishCategory
     * @return JsonResponse
     */
    public function delete(DishCategory $dishCategory)
    {
        try {
            if($dishes = Dish::where('category_id', $dishCategory->id)->first()){
                return response()->json('Przed usunięciem kategorii usuń dania', 419);
            }
            $dishCategory->delete();
            return response()->json("Kategoria usunięta", 201);
        } catch (\Exception $e) {
            Log::notice("Error deleting data all:" . $e);
            Log::notice("Error deleting data msg:" . $e->getMessage());
            Log::notice("Error deleting data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}
