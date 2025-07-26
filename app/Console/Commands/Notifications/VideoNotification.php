<?php

namespace App\Console\Commands\Notifications;

use Illuminate\Console\Command;
use App\Http\Controllers\Notifications\NotificationsController;

class VideoNotification extends Command
{
    // Define command name
    protected $signature = 'notifications:video';

    // Add description to your command
    protected $description = 'Send video message';

    // Create your own custom command
    public function handle() {
        (new NotificationsController())->videoMessage();
    }
}


