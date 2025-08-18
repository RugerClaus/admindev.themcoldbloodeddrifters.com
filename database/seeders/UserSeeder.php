<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'], // search criteria
            [
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'permission_level' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'user'], // search criteria
            [
                'password' => Hash::make('test'),
                'permission_level' => 'user',
            ]
        );
    }
}
