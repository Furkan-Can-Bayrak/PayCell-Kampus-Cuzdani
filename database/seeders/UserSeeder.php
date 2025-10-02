<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test',
            'surname' => 'User',
            'phone' => '05551234567',
            'email' => 'test@test.com',
            'password' => Hash::make('1'),
            'email_verified_at' => now(),
        ]);
    }
}
