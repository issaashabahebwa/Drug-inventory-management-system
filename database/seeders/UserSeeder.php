<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'       => 'Dr. Sarah Nakamya',
                'email'      => 'admin@kiswahealth.ug',
                'password'   => Hash::make('admin@123'),
                'role'       => User::ROLE_ADMIN,
                'phone'      => '+256 701 234 567',
                'is_active'  => true,
            ],
            [
                'name'       => 'Mr. Joseph Ssekandi',
                'email'      => 'stores@kiswahealth.ug',
                'password'   => Hash::make('stores@123'),
                'role'       => User::ROLE_STORES,
                'phone'      => '+256 772 345 678',
                'is_active'  => true,
            ],
            [
                'name'       => 'Ms. Grace Namusoke',
                'email'      => 'pharmacy@kiswahealth.ug',
                'password'   => Hash::make('pharmacy@123'),
                'role'       => User::ROLE_PHARMACIST,
                'phone'      => '+256 752 456 789',
                'is_active'  => true,
            ],
            [
                'name'       => 'Mr. David Kyagaba',
                'email'      => 'pharmacy2@kiswahealth.ug',
                'password'   => Hash::make('pharmacy@123'),
                'role'       => User::ROLE_PHARMACIST,
                'phone'      => '+256 783 567 890',
                'is_active'  => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
