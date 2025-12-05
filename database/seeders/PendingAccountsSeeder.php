<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PendingUserAccount;

class PendingAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendingUsers = [
            [
                'name' => 'Pending Student 1',
                'email' => 'pending1@gmail.com',
                'password' => Hash::make('12341234'),
            ],
            [
                'name' => 'Pending Student 2',
                'email' => 'pending2@gmail.com',
                'password' => Hash::make('12341234'),
            ],
            [
                'name' => 'Pending Student 3',
                'email' => 'pending3@gmail.com',
                'password' => Hash::make('12341234'),
            ],
            [
                'name' => 'Pending Student 4',
                'email' => 'pending4@gmail.com',
                'password' => Hash::make('12341234'),
            ],
            [
                'name' => 'Pending Student 5',
                'email' => 'pending5@gmail.com',
                'password' => Hash::make('12341234'),
            ],
        ];

        foreach ($pendingUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role' => 'student',
                    'club_id' => null,
                ]
            );

            PendingUserAccount::firstOrCreate(
                ['user_id' => $user->id],
                ['status' => 'pending']
            );
        }

        $this->command->info('Pending user accounts created successfully!');
        $this->command->info('Created 5 pending user accounts');
        $this->command->info('Pending Users: pending1@gmail.com to pending5@gmail.com / 12341234');
    }
}