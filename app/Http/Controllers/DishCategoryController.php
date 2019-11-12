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
        return view('dishCategory.index');
    }
}
