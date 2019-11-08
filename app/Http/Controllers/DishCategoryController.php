<?php

namespace App\Http\Controllers;

use App\Models\DishCategory;
use Illuminate\Http\Request;

class DishCategoryController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(DishCategory::all());
        return view('home');
    }
}
