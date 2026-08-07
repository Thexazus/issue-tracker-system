<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users for each role with realistic names
        $admin = User::create([
            'name' => 'Raecia',
            'email' => 'rae@ticketing.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $dev1 = User::create([
            'name' => 'Alex Dev',
            'email' => 'alex@ticketing.com',
            'password' => bcrypt('password'),
            'role' => 'developer',
        ]);

        $dev2 = User::create([
            'name' => 'John Dev',
            'email' => 'john@ticketing.com',
            'password' => bcrypt('password'),
            'role' => 'developer',
        ]);

        $qa1 = User::create([
            'name' => 'Sarah QA',
            'email' => 'sarah@ticketing.com',
            'password' => bcrypt('password'),
            'role' => 'qa',
        ]);

        $qa2 = User::create([
            'name' => 'Emily QA',
            'email' => 'emily@ticketing.com',
            'password' => bcrypt('password'),
            'role' => 'qa',
        ]);

        // 2. Create sample tickets reported by QAs
        $ticket1 = \App\Models\Ticket::create([
            'title' => 'Login page crashes on empty email input',
            'description' => 'When submitting the login form without entering an email address, the server throws a 500 error instead of a validation warning. Screenshot attached shows the stack trace.',
            'status' => 'open',
            'priority' => 'high',
            'reporter_id' => $qa1->id,
            'assigned_to_id' => null, // Unassigned initially
        ]);

        $ticket2 = \App\Models\Ticket::create([
            'title' => 'Dashboard widgets showing wrong counter values',
            'description' => 'The total counter for In Progress tickets is displaying 5 instead of the actual count of 3. Database is correct, UI logic seems to filter incorrectly.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'reporter_id' => $qa2->id,
            'assigned_to_id' => $dev1->id, // Assigned to Alex
        ]);

        $ticket3 = \App\Models\Ticket::create([
            'title' => 'Critical: Database connection timeout under load',
            'description' => 'During load testing of the ticket search API, the application frequently timed out while establishing connection to the SQL server.',
            'status' => 'open',
            'priority' => 'critical',
            'reporter_id' => $qa1->id,
            'assigned_to_id' => $dev2->id, // Assigned to John
        ]);

        // 3. Create sample activity logs
        \App\Models\ActivityLog::create([
            'ticket_id' => $ticket1->id,
            'user_id' => $qa1->id,
            'action' => 'created',
            'description' => "Sarah QA created ticket {$ticket1->ticket_number}",
        ]);

        \App\Models\ActivityLog::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $qa2->id,
            'action' => 'created',
            'description' => "Emily QA created ticket {$ticket2->ticket_number}",
        ]);

        \App\Models\ActivityLog::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $admin->id,
            'action' => 'assigned',
            'description' => "Raecia assigned ticket {$ticket2->ticket_number} to Alex Dev",
        ]);

        \App\Models\ActivityLog::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $dev1->id,
            'action' => 'status_changed',
            'description' => "Alex Dev updated status of {$ticket2->ticket_number} to in_progress",
            'changes' => ['old' => 'open', 'new' => 'in_progress'],
        ]);

        // 4. Create sample comments
        \App\Models\Comment::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $dev1->id,
            'content' => 'I am looking into this. It seems to be a caching issue in the Repository layer query.',
        ]);

        \App\Models\Comment::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $qa2->id,
            'content' => 'Thanks, let me know if you need another database dump for reproduction.',
        ]);
    }
}
