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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        dd(DishCategory::all());
    }
}
