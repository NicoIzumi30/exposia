<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'id' => Str::uuid(),
            'name' => 'Super Admin',
            'email' => 'admin@exposia.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create Regular User 1 (Contoh UMKM Kuliner)
        User::create([
            'id' => Str::uuid(),
            'name' => 'Heru Kristanto',
            'email' => 'heru@exposia.com',
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create Regular User 2 (Contoh UMKM Fashion)
        User::create([
            'id' => Str::uuid(),
            'name' => 'Syafiq',
            'email' => 'syafiq@exposia.com',
            'phone' => '081234567891',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create Regular User 3 (Contoh UMKM Kerajinan)
        User::create([
            'id' => Str::uuid(),
            'name' => 'Nuha Salimah',
            'email' => 'nuha@exposia.com',
            'phone' => '081234567892',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);
    }
}