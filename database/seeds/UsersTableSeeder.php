<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $secret = bcrypt('secret');
        DB::table('users')->insert([
            'name'     => 'ADMIN USER',
            'email'    => 'admin@mail.me',
            'password' => $secret,
        ]);

        $user = User::where('email', 'admin@mail.me')->first();
        $user->generateApiToken();
    }
}
