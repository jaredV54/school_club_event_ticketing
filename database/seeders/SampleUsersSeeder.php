<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Club;

class SampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample club for the officer
        $club = Club::firstOrCreate(
            ['name' => 'Computer Science Club'],
            ['description' => 'A club for computer science enthusiasts']
        );

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('12341234'),
                'role' => 'admin',
                'club_id' => null,
            ]
        );

        // Create Officer User
        User::firstOrCreate(
            ['email' => 'officer@gmail.com'],
            [
                'name' => 'Club Officer',
                'password' => Hash::make('12341234'),
                'role' => 'officer',
                'club_id' => $club->id,
            ]
        );

        // Create Student Users
        User::firstOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'name' => 'Regular Student',
                'password' => Hash::make('12341234'),
                'role' => 'student',
                'club_id' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'student2@gmail.com'],
            [
                'name' => 'Student Two',
                'password' => Hash::make('12341234'),
                'role' => 'student',
                'club_id' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'student3@gmail.com'],
            [
                'name' => 'Student Three',
                'password' => Hash::make('12341234'),
                'role' => 'student',
                'club_id' => null,
            ]
        );

        $this->command->info('Sample users created successfully!');
        $this->command->info('Admin: admin@gmail.com / 12341234');
        $this->command->info('Officer: officer@gmail.com / 12341234');
        $this->command->info('Student: student@gmail.com / 12341234');
        $this->command->info('Student: student2@gmail.com / 12341234');
        $this->command->info('Student: student3@gmail.com / 12341234');
    }
}
