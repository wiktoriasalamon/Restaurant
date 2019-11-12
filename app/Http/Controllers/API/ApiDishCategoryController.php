<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishCategoryRequest;
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

    /**
     * @param DishCategoryRequest $request [name]
     * @return JsonResponse
     */
    public function store(DishCategoryRequest $request)
    {
        try {
            $category = new DishCategory();
            $category->name = $request->name;
            $category->save();
            return response()->json(['message' => "Kategoria została pomyślnie zapisana."], 200);
        } catch (\Exception $e) {
            Log::notice("Error storing data all:" . $e);
            Log::notice("Error storing data msg:" . $e->getMessage());
            Log::notice("Error storing data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @param DishCategoryRequest $request [id,name]
     * @return JsonResponse
     */
    public function update (DishCategoryRequest $request)
    {
        try {
            $category = DishCategory::findOrFail($request->id);
            $category->name = $request->name;
            $category->save();
            return response()->json(['message' => "Kategoria została pomyślnie zapisana."], 200);
        } catch (\Exception $e) {
            Log::notice("Error updating data all:" . $e);
            Log::notice("Error updating data msg:" . $e->getMessage());
            Log::notice("Error updating data code:" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }
}
