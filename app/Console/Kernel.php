<?php

namespace App\Console;

use App\Jobs\SyncActivityTracker22Job;
use App\Jobs\SyncBudgetTracker22Job;
use App\Jobs\SyncWorkOrdersJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new SyncBudgetTracker22Job())
            ->everyFiveMinutes()
            ->weekdays()
            ->timezone('America/Chicago')
            ->between('07:00', '18:00')
            ->withoutOverlapping();
        $schedule->job(new SyncActivityTracker22Job())
            ->everyFiveMinutes()
            ->weekdays()
            ->timezone('America/Chicago')
            ->between('07:00', '18:00')
            ->withoutOverlapping();
        $schedule->job(new SyncWorkOrdersJob())
            ->everyFiveMinutes()
            ->timezone('America/Chicago')
            //->between('05:00', '23:00')
            ->withoutOverlapping();
        $schedule->command('horizon:snapshot')
            ->everyFiveMinutes();
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
