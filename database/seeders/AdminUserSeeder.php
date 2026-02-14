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

        $user = User::where('email', $email)->first();

        if (!$user) {
            User::create([
                'name' => 'Admin Oussama',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin', // Ensure you have a role column or logic handling this
            ]);
            $this->command->info("Admin user created: {$email}");
        } else {
            $user->update([
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);
            $this->command->info("Admin user updated: {$email}");
        }
    }
}
