<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWT;

class TableController extends Controller
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
//        dd(Auth::user());
//        dd(Illuminate\Support\Facades\Auth::user());
//        dd(Auth::id());
//        dd(auth()->tAuth::id());
//        $token = auth()->login(Illuminate\Support\Facades\Auth::user());
        $newToken = auth()->tokenById(1);
        dd($newToken);
        dd($user = auth()->user());
//        dd(JWT::fromUser(User::find(Auth::id())));
        dd(Table::all()->load(['reservation', 'order']));
        return view('home');
    }
}
