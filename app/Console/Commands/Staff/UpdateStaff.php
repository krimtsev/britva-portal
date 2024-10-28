<?php

namespace App\Console\Commands\Staff;

use Illuminate\Console\Command;
use App\Http\Controllers\Staff\StaffController;

class UpdateStaff extends Command
{
    // Define command name
    protected $signature = 'staff:update';

    // Add description to your command
    protected $description = 'Update staff';

    // Create your own custom command
    public function handle() {
        StaffController::setJobs();
    }
}
