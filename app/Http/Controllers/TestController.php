<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'showLogin']);
    }

    public function showHome()
    {
        return view('test.home');
    }

    public function showLogin()
    {
        return view('test.login');
    }
}