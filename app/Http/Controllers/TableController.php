<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWT;

class TableController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(Table::all()->load(['reservation', 'order']));
        return view('home');
    }
}
