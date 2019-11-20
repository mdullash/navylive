<?php

namespace App\Console;

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
        Commands\NsdKhulnaSuppliers::class,
        Commands\BsdKhulnaSuppliers::class,
        Commands\NsdDhakaSuppliers::class,
        Commands\BsdDhakaSuppliers::class,
        Commands\NsdDgdpSuppliers::class,
        Commands\BsdDhakaItems::class,
        Commands\BsdKhulnaItems::class,
        Commands\NsdDhakaItems::class,
        Commands\NsdKhulnaItems::class,
        Commands\NsdDgdpItems::class,
        // Tender
        Commands\BsdDhakaTenders::class,
        Commands\NsdKhulnaTenders::class,
        Commands\BsdKhulnaTenders::class,
        Commands\NsdDhakaTenders::class,
        Commands\NsdDgdpTenders::class,
        // Item To Tender
        Commands\NsdKhulnaItemToTenders::class,
        Commands\BsdDhakaItemToTenders::class,
        Commands\BsdKhulnaItemToTenders::class,
        Commands\NsdDgdpItemToTenders::class,
        Commands\NsdDhakaItemToTenders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('nsdkhulna:suppliers')
                ->dailyAt('04:00'); 
        $schedule->command('bsdkhulna:suppliers')
                ->dailyAt('04:00');
        $schedule->command('nsddhaka:suppliers')
                ->dailyAt('04:00'); 
        $schedule->command('bsddhaka:suppliers')
                ->dailyAt('04:00');
        $schedule->command('nsddgdp:suppliers')
                ->dailyAt('04:00');
        $schedule->command('bsddhaka:items')
                ->dailyAt('04:00');
        $schedule->command('bsdkhulna:items')
                ->dailyAt('04:00');
        $schedule->command('nsddgdp:items')
                ->dailyAt('04:00');
        $schedule->command('nsddhaka:items')
                ->dailyAt('04:00');
        $schedule->command('nsdkhulna:items')
                ->dailyAt('04:00');
        // Tneder  
        $schedule->command('bsddhaka:tenders')
                ->dailyAt('04:00');
        $schedule->command('bsdkhulna:tenders')
                ->dailyAt('04:00');
        $schedule->command('nsddgdp:tenders')
                ->dailyAt('04:00');
        $schedule->command('nsddhaka:tenders')
                ->dailyAt('04:00');
        $schedule->command('nsdkhulna:tenders')
                ->dailyAt('04:00');
        // Item To Tneder  
        $schedule->command('nsdkhulna:itemtotenders')
                ->dailyAt('04:00');
        $schedule->command('bsddhaka:itemtotenders')
                ->dailyAt('04:00');
        $schedule->command('bsdkhulna:itemtotenders')
                ->dailyAt('04:00');
        $schedule->command('nsddgdp:itemtotenders')
                ->dailyAt('04:00');
        $schedule->command('nsddhaka:itemtotenders')
                ->dailyAt('04:00');                               
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
