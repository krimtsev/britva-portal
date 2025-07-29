<?php

namespace App\Console;

use App\Http\Services\ReportService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('blacklist:update')
            ->hourly()
            ->onFailure(function (Stringable $output) {
                ReportService::error('[command] mango blacklist update', $output);
            });

        $schedule->command('staff:update')
            ->withoutOverlapping()
            ->cron('30 10,14,18,22 * * *')
            ->onFailure(function (Stringable $output) {
                ReportService::error('[command] staff update', $output);
            });

        $schedule->command('notifications:video')
            ->withoutOverlapping()
            ->cron('0 10 * * 1')
            ->onFailure(function (Stringable $output) {
                ReportService::error('[command] notification video', $output);
            });

        $schedule->command('notifications:whatsapp')
            ->withoutOverlapping()
            ->cron('30 12 * * *')
            ->onFailure(function (Stringable $output) {
                ReportService::error('[command] notification whatsapp', $output);
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
