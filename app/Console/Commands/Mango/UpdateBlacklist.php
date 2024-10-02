<?php

namespace App\Console\Commands\Mango;

use Illuminate\Console\Command;
use App\Http\Controllers\Mango\MangoController;

class UpdateBlacklist extends Command
{
	// Define command name
	protected $signature = 'blacklist:update';

	// Add description to your command
	protected $description = 'Update blacklist of phone numbers';

	// Create your own custom command
	public function handle() {
        MangoController::updateBlacklist();
	}
}


