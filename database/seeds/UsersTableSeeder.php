<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        if (User::all()->count() == 0) {
            $domain = "@gmail.com";
            $pass = "123456";

            $users = ['admin', 'user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7'];

            foreach ($users as $user) {
                factory(User::class)->create(
                    [
                        'address' => json_encode(' '),
                        'email' => $user . $domain,
                        'password' => bcrypt($pass)
                    ]);
            }
        }
    }
}
