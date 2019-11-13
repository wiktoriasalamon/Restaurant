<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Dish;
use App\Models\DishCategory;

class OrderController extends Controller
{
    public function createOrder()
    {
        $categories = DishCategory::all();
        $dishes = Dish::all()->load('category');
        return view('orders.customerOrder', compact('dishes', 'categories'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('order/waiterIndex');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createWaiterOrder($tableId)
    {
        return view('order/createWaiter', compact(['tableId']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
