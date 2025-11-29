<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\AttendanceLog;
use App\Models\User;
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
        $student = User::where('email', 'student@gmail.com')->first();
        $events = Event::all();

        if ($student && $events->count() > 0) {
            foreach ($events->take(2) as $event) { // Register student for first 2 events
                EventRegistration::firstOrCreate(
                    ['event_id' => $event->id, 'user_id' => $student->id],
                    [
                        'ticket_code' => Str::random(10),
                        'status' => 'registered',
                    ]
                );
            }
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Created ' . Club::count() . ' clubs');
        $this->command->info('Created ' . Event::count() . ' events');
        $this->command->info('Created ' . EventRegistration::count() . ' registrations');
    }
}
