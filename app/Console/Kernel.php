<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * If you created commands with `php artisan make:command`,
     * you may list them here or rely on automatic discovery.
     *
     * @var array
     */
    protected $commands = [
        // If you want, register commands explicitly:
        // \App\Console\Commands\UpdateBookingStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // run our booking status updater hourly
        $schedule->command('bookings:update-status')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load commands automatically in app/Console/Commands
        $this->load(__DIR__ . '/Commands');

        // If you have routes/console.php, include it:
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}
