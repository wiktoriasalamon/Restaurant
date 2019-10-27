<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Http\Request;

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
}
