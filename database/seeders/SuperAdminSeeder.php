<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.super_admin.email');
        $password = config('admin.super_admin.password');
        $name = config('admin.super_admin.name') ?: 'Super Admin';

        if (! is_string($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Set a valid SUPER_ADMIN_EMAIL before seeding.');
        }

        if (! is_string($password) || strlen($password) < 16) {
            throw new RuntimeException('Set a unique SUPER_ADMIN_PASSWORD of at least 16 characters before seeding.');
        }

        $email = strtolower(trim($email));
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            if (! $existingUser->isSuperAdmin()) {
                throw new RuntimeException('The configured SUPER_ADMIN_EMAIL is already assigned to a non-admin account.');
            }

            $this->command->info('The configured privileged account already exists; no account changes were made.');

            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->command->info('The initial privileged account was created.');
    }
}
