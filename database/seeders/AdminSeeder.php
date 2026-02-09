<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'oussama.oubaha24@ump.ac.ma'],
            [
                'name' => 'Oussama Oubaha',
                'password' => Hash::make('Admin@Oussama2026!'),
            ]
        );
    }
}
