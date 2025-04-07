<?php

namespace App\Console\Commands\Notifications;

use Illuminate\Console\Command;
use App\Http\Controllers\Notifications\NotificationsController;

class Notifications extends Command
{
    // Define command name
    protected $signature = 'reports:video';

    // Add description to your command
    protected $description = 'Send video reports';

    // Create your own custom command
    public function handle() {
        (new NotificationsController())->videoReports();
    }
}


