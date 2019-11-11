<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPassword()
    {
        return view('auth.passwords.email');
    }

    public function myAccount(){
        return view('users/myAccount');
    }
}