<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(Dish::all()->load('category'));
        return view('home');
    }

    /**
     * Show the restaurant menuLayouts for customer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu()
    {
        return view('menuLayouts/menu');
    }

}
