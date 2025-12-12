<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MarkAbsentForPastEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-absent-for-past-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark registered users as absent for past events that have ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $events = \App\Models\Event::where('status', 'active')->get();

        $updatedCount = 0;

        foreach ($events as $event) {
            $endDateTime = \Carbon\Carbon::parse($event->time_end);

            if ($endDateTime->isPast()) {
                $event->update(['status' => 'passed']);

                $updated = \App\Models\EventRegistration::where('event_id', $event->id)
                    ->where('status', 'registered')
                    ->update(['status' => 'absent']);

                $updatedCount += $updated;
            }
        }

        $this->info("Marked {$updatedCount} registrations as absent and updated event statuses for past events.");
    }
}
