<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\DishCategory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder()
    {
        $categories = DishCategory::all();
        $dishes = Dish::all()->load('category');
        return view('orders.customerOrder', compact('dishes', 'categories'));
    }
}
