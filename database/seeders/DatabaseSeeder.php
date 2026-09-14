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
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin NemuKos',
        'password' => bcrypt('password'), // Harus di-hash agar Auth Laravel bisa verifikasi
    ]
);


    }
}
