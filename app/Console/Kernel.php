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

        Commands\ShovelRequestEventIdByTypeCommand::class,
        Commands\ShovelRequestEventCommand::class,
        Commands\ShovelRequestVenueIdByStateCommand::class,
        Commands\ShovelRequestVenueIdByStateAllCommand::class,
        Commands\ShovelRequestVenueDetailCommand::class,
        Commands\ShovelRequestDetailBulkCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('shovel:detail-bulk', [
        //     '--type'  => 'venue',
        //     '--count' => 5,
        //     '--file'  => 'public/venues/bulk/all-venue-ids.json',
        //     '--save',
        //     ])
        //     ->everyFiveMinutes()
        //     ->appendOutputTo(storage_path('logs/venue-detail-bulk.log'));

        // $schedule->command('shovel:detail-bulk', [
        //     '--type'  => 'event',
        //     '--count' => 1,
        //     '--file'  => 'public/events/bulk/2017-national-page-2-of-3-event-ids.json',
        //     '--save',
        //     ])
        //     ->everyMinute()
        //     ->appendOutputTo(storage_path('logs/event-detail-bulk.log'));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
