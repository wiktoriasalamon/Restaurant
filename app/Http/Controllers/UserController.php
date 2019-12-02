<?php

namespace App\Http\Controllers;

class UserController extends Controller
{

    public function myAccount(){
        return view('users/myAccount');
    }
}