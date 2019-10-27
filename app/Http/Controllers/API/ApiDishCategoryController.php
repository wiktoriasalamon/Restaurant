<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DishCategory;
use Illuminate\Http\Request;

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
}
