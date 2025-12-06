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
        // Create 5 clubs
        $clubs = [
            ['name' => 'Computer Science Club', 'description' => 'A club for computer science enthusiasts'],
            ['name' => 'Art Club', 'description' => 'Creative arts and painting club'],
            ['name' => 'Sports Club', 'description' => 'Sports and athletics club'],
            ['name' => 'Music Club', 'description' => 'Music and singing club'],
            ['name' => 'Debate Club', 'description' => 'Public speaking and debate club'],
        ];

        foreach ($clubs as $clubData) {
            Club::firstOrCreate(
                ['name' => $clubData['name']],
                ['description' => $clubData['description']]
            );
        }

        $club = Club::where('name', 'Computer Science Club')->first();

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin1!'),
                'role' => 'admin',
                'club_id' => null,
            ]
        );

        // Create Officer User
        User::firstOrCreate(
            ['email' => 'officer@gmail.com'],
            [
                'name' => 'Club Officer',
                'password' => Hash::make('Officer1!'),
                'role' => 'officer',
                'club_id' => $club->id,
            ]
        );

        // Create Student User
        User::firstOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'name' => 'Regular Student',
                'password' => Hash::make('Student1!'),
                'role' => 'student',
                'club_id' => $club->id,
            ]
        );

        $this->command->info('Sample users and clubs created successfully!');
        $this->command->info('Admin: admin@gmail.com / Admin1!');
        $this->command->info('Officer: officer@gmail.com / Officer1!');
        $this->command->info('Student: student@gmail.com / Student1!');
        $this->command->info('Created 5 clubs: Computer Science Club, Art Club, Sports Club, Music Club, Debate Club');
    }
}
