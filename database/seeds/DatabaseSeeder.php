<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Iz S.',
            'username' => env('USERNAME'),
            'password' => bcrypt(env('PASSWORD')),
            'internet_pin' => encrypt(env('INTERNET_PIN'), false),
            'mobile_pin' => encrypt(env('MOBILE_PIN'), false)
        ]);
    }
}
