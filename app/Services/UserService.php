<?php


namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class UserService
{
    /**
     * @param string $role
     * @return mixed
     */
   public function getUsersByRole(string $role)
   {
       return User::whereHas('roles', function ($query) use ($role) {
           $query->where('name', 'like', $role);
       })->get();
   }


}
