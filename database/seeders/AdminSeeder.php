<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Asean Admin',
            'email' => 'admin@aseana.com',
            'password' => Hash::make('password'), // password
            'email_verified_at' => now(),
            'is_admin' => 1
        ])->assignRole('admin', 'researcher');
    }
}
