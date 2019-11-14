<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domain = "@gmail.com";
        $users=User::all();
        foreach ($users as $user){
            $user->assignRole('customer');
        }
        $admin=User::where('email', 'admin'.$domain)->first();
        $admin->assignRole('admin');
        $mobile=User::where('email', 'worker'.$domain)->first();
        $mobile->assignRole('worker');
        $mobile=User::where('email', 'worker2'.$domain)->first();
        $mobile->assignRole('worker');
    }
}
