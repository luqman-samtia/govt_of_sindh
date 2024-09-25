<?php

namespace App\Console;

use App\Console\Commands\DueDateInvoiceReminderCommand;
use App\Console\Commands\RecurringInvoiceCommand;
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
        RecurringInvoiceCommand::class,
        DueDateInvoiceReminderCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(RecurringInvoiceCommand::class)
            ->daily()
            ->appendOutputTo(storage_path('logs/commands.log'));
        $schedule->command(DueDateInvoiceReminderCommand::class)
            ->daily()
            ->appendOutputTo(storage_path('logs/commands.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
