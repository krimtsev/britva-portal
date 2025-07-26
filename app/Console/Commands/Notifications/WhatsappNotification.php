<?php

namespace App\Console\Commands\Notifications;

use Illuminate\Console\Command;
use App\Http\Controllers\Notifications\NotificationsController;

class WhatsappNotification extends Command
{
    // Define command name
    protected $signature = 'notifications:whatsapp';

    // Add description to your command
    protected $description = 'Send whatsapp message';

    // Create your own custom command
    public function handle() {
        (new NotificationsController())->whatsappBotMessage();
    }
}


