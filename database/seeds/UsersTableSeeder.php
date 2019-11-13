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
            $areaCode='+48';

            $users = ['admin', 'worker','worker2','customer','user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7'];

            foreach ($users as $user) {
                $phoneNumber = $areaCode . random_int(1, 9);
                for ($i = 0; $i < 8; $i++) {
                    $phoneNumber .= random_int(0, 9);
                }
                factory(User::class)->create(
                    [
                        'address' => json_encode(' '),
                        'email' => $user . $domain,
                        'phone' => $phoneNumber,
                        'password' => bcrypt($pass)
                    ]);
            }

        }
    }
}
