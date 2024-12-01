<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating an Admin user
        User::create([
            'first_name' => 'Admin',
            'middle_name' => 'Middle',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Use a secure password
            'role' => 'Admin',
        ]);
    }
}
