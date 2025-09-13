<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'full_name'     => 'Admin User',
                'national_id'   => '12345678901234',
                'username'      => 'admin',
                'password'      => Hash::make('123456789'),
                'role'          => 'admin',
            ]
        );
    }
}
