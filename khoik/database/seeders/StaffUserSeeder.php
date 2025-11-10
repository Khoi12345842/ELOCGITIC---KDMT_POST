<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'khoidoan@genzexpress.vn'],
            [
                'name' => 'khoidoan',
                'password' => Hash::make('15042004'),
                'user_type' => 'individual',
                'role' => User::ROLE_STAFF,
                'phone' => '0900000000',
                'address' => 'Trụ sở GENZ EXPRESS',
            ]
        );

        User::updateOrCreate(
            ['email' => 'khoi@gmail.com'],
            [
                'name' => 'Khoi',
                'password' => Hash::make('15042004'),
                'user_type' => 'individual',
                'role' => User::ROLE_STAFF,
                'phone' => '0123456789',
                'address' => 'Bưu cục GENZ EXPRESS',
            ]
        );
    }
}
