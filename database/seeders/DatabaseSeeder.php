<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
<<<<<<< HEAD
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin NemuKos',
        'password' => bcrypt('password'), // Harus di-hash agar Auth Laravel bisa verifikasi
    ]
);
=======
            ['email' => 'admin@example.com'],
            [
                'name'     => 'SuperAdmin',
                'password' => 'password',
                'role'     => 'super_admin',
            ]
        );
>>>>>>> 7e0eeb6fa02a38cfbb9854e8a343e3b3311b9978


    }
}
