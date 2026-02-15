<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@oussama.com';
        $password = '97999799';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin Oussama',
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Always update password and role to ensure they are correct (in case of manual changes or hashing issues)
        $user->update([
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);
        
        $this->command->info("Admin user setup complete: {$email}");
    }
}
