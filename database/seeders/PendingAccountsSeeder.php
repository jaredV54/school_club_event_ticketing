<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PendingUserAccount;
use App\Models\Club;

class PendingAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club = Club::where('name', 'Art Club')->first();

        PendingUserAccount::firstOrCreate(
            ['email' => 'pending@gmail.com'],
            [
                'name' => 'Pending Student',
                'password' => Hash::make('Pending1!'),
                'club_id' => $club->id,
            ]
        );

        $this->command->info('Pending user account created successfully!');
        $this->command->info('Pending User: pending@gmail.com / Pending1!');
    }
}