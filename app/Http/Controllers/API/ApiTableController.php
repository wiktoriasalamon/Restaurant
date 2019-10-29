<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class ApiTableController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Table[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return response()->json(Table::all()->load(['reservation', 'order']));
    }
}
