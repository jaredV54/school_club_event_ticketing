<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\AttendanceLog;
use App\Models\User;
use App\Models\PendingEventRegistration;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create additional clubs
        $clubs = [
            ['name' => 'Computer Science Club', 'description' => 'Programming and tech enthusiasts'],
            ['name' => 'Art & Design Club', 'description' => 'Creative minds and visual artists'],
            ['name' => 'Sports Club', 'description' => 'Athletes and sports lovers'],
            ['name' => 'Music Club', 'description' => 'Musicians and music appreciation'],
            ['name' => 'Debate Club', 'description' => 'Public speaking and debate enthusiasts'],
        ];

        foreach ($clubs as $clubData) {
            Club::firstOrCreate(
                ['name' => $clubData['name']],
                ['description' => $clubData['description']]
            );
        }

        // Create sample events
        $events = [
            [
                'club_name' => 'Computer Science Club',
                'title' => 'Hackathon 2024',
                'description' => '24-hour coding competition',
                'venue' => 'Computer Lab A',
                'date' => now()->addDays(7)->format('Y-m-d'),
                'time_start' => '09:00',
                'time_end' => '09:00',
                'capacity' => 50,
            ],
            [
                'club_name' => 'Art & Design Club',
                'title' => 'Digital Art Workshop',
                'description' => 'Learn digital art techniques',
                'venue' => 'Art Studio',
                'date' => now()->addDays(14)->format('Y-m-d'),
                'time_start' => '14:00',
                'time_end' => '16:00',
                'capacity' => 25,
            ],
            [
                'club_name' => 'Sports Club',
                'title' => 'Basketball Tournament',
                'description' => 'Inter-class basketball competition',
                'venue' => 'Gymnasium',
                'date' => now()->addDays(21)->format('Y-m-d'),
                'time_start' => '15:00',
                'time_end' => '18:00',
                'capacity' => 100,
            ],
            [
                'club_name' => 'Music Club',
                'title' => 'Music Jam Session',
                'description' => 'Casual music performance and collaboration',
                'venue' => 'Music Room',
                'date' => now()->addDays(28)->format('Y-m-d'),
                'time_start' => '16:00',
                'time_end' => '18:00',
                'capacity' => 30,
            ],
            [
                'club_name' => 'Debate Club',
                'title' => 'Debate Championship',
                'description' => 'School-wide debate competition',
                'venue' => 'Auditorium',
                'date' => now()->addDays(35)->format('Y-m-d'),
                'time_start' => '10:00',
                'time_end' => '12:00',
                'capacity' => 200,
            ],
        ];

        foreach ($events as $eventData) {
            $club = Club::where('name', $eventData['club_name'])->first();
            if ($club) {
                Event::firstOrCreate(
                    ['title' => $eventData['title'], 'club_id' => $club->id],
                    [
                        'description' => $eventData['description'],
                        'venue' => $eventData['venue'],
                        'date' => $eventData['date'],
                        'time_start' => $eventData['time_start'],
                        'time_end' => $eventData['time_end'],
                        'capacity' => $eventData['capacity'],
                    ]
                );
            }
        }

        // Create sample registrations
        $students = User::where('role', 'student')->whereDoesntHave('pendingUserAccount')->get(); // Only approved students
        $events = Event::all();

        if ($students->count() > 0 && $events->count() > 0) {
            for ($i = 0; $i < 10; $i++) {
                $student = $students->random();
                $event = $events->random();
                EventRegistration::firstOrCreate(
                    ['event_id' => $event->id, 'user_id' => $student->id],
                    [
                        'ticket_code' => Str::random(10),
                        'status' => 'registered',
                    ]
                );
            }
        }

        // Create pending event registrations
        if ($students->count() > 0 && $events->count() > 0) {
            for ($i = 0; $i < 10; $i++) {
                $student = $students->random();
                $event = $events->random();
                PendingEventRegistration::firstOrCreate(
                    ['event_id' => $event->id, 'user_id' => $student->id],
                    [
                        'role' => 'student',
                        'status' => 'pending',
                    ]
                );
            }
        }

        // Create attendance logs
        $registrations = EventRegistration::all();
        if ($registrations->count() > 0) {
            for ($i = 0; $i < 10; $i++) {
                $registration = $registrations->random();
                AttendanceLog::firstOrCreate(
                    ['registration_id' => $registration->id],
                    [
                        'timestamp' => now()->subHours(rand(1, 5)),
                    ]
                );
            }
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Created ' . Club::count() . ' clubs');
        $this->command->info('Created ' . Event::count() . ' events');
        $this->command->info('Created ' . EventRegistration::count() . ' registrations');
        $this->command->info('Created ' . PendingEventRegistration::count() . ' pending registrations');
        $this->command->info('Created ' . AttendanceLog::count() . ' attendance logs');
    }
}
