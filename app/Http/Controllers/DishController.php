<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\DishCategory;
use Illuminate\Http\Request;

class DishController extends Controller
{

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
        $categories = DishCategory::all();
        $dishes = Dish::all()->load('category');
        return view('menuLayouts/menu', compact('dishes', 'categories'));
    }


    /**
     * Show the restaurant menuLayouts for admin
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminMenu()
    {
        return view('menuLayouts/adminMenu');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dish = Dish::find($id);
        return view('dish/edit', compact(['dish', 'id']));
    }

    /**
     *
     */
    public function create()
    {
        return view('dish.create');
    }
}
