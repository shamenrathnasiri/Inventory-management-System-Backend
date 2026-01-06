<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@mail.com',
                'password' => bcrypt('123456789'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
