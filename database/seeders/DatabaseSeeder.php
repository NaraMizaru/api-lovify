<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'fullname' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'email' => 'admin@gmail.com',
            'phone_number' => '08123456789',
            'role' => 'admin',
        ]);

        User::create([
            'fullname' => 'client',
            'username' => 'client',
            'password' => bcrypt('client'),
            'email' => 'client@gmail.com',
            'phone_number' => '08123987654',
            'role' => 'client',
        ]);
    }
}
