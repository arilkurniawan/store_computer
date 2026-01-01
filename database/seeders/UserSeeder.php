<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek jika sudah ada admin, skip
        if (User::where('email', 'admin@admin.com')->exists()) {
            $this->command->info('⏭️  Users already exist, skipping...');
            return;
        }

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Customer 1
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Customer 2
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Customer 3
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Users seeded: 1 admin, 3 customers');
    }
}
