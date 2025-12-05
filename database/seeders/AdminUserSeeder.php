<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản admin cố định
        User::firstOrCreate(
            ['email' => 'xuandat@gmail.com'],
            [
                'name' => 'Xuân Đạt',
                'password' => Hash::make('10022004'),
                'role' => 'admin',
                'user_type' => 'individual',
                'phone' => '0900000001',
                'address' => 'Trụ sở chính',
            ]
        );

        // Tạo tài khoản admin demo (optional)
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('15042004'),
                'role' => 'admin',
                'user_type' => 'individual',
                'phone' => '0900000000',
                'address' => 'Trụ sở chính',
            ]
        );
    }
}
