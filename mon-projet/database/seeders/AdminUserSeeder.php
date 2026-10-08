<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'mohamedkhalif593@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Password123'),
            ]
        );
    }
}