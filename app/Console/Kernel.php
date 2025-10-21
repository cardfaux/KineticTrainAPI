<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\FetchWmataTrainData::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Run WMATA fetch command every thirty seconds
        $schedule->command('wmata:fetch-train-data')->everyThirtySeconds();
        //$schedule->command('stations:fetch')->daily(); // or every hour
    }
}
