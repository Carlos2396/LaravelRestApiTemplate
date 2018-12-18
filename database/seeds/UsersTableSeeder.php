<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password and 
        // let's hash it before the loop, or else our seeder 
        // will be too slow.
        $password = Hash::make('secret');

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => $password,
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('user');
    }
}
