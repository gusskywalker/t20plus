<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        // google_id is left null on purpose — AuthController's login flow
        // matches by email when google_id doesn't match anything yet, then
        // attaches google_id to this same row, so the next real Google
        // login merges into this seeded row instead of creating a second
        // user.
        User::create([
            'id' => 1,
            'name' => 'Gus Ziliotto',
            'email' => 'gusziliotto@gmail.com',
            'google_id' => null,
            'password' => null,
        ]);
    }
}
