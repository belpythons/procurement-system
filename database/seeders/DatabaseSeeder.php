<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'name' => 'Admin KMI',
            'email' => 'admin@outlook.com',
            'password' => bcrypt('adminkmi123'),
        ]);
    }
}