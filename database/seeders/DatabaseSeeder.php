<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => env('SUPER_ADMIN_EMAIL', 'admin@farco.local'),
        ], [
            'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
            'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'ChangeMe123!')),
            'role' => 'super_admin',
        ]);
    }
}
