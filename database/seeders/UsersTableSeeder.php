<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use DateTime;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'name' . 1,
            'email' => 'example@test.com',
            'email_verified_at' => new DateTime(),
            'password' => 'example',
        ]);
    }
}
