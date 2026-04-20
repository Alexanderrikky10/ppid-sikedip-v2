<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert(
            [
                [
                    'name' => 'Administrator',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'staff',
                    'email' => 'staff@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

            ],

        );
    }
}