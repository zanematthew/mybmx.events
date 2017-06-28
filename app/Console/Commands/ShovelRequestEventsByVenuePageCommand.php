<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\ShovelEventsSchedule;

class ShovelRequestEventsByVenuePageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:request-events-by-venue-page
                            {--venue_id= : Venue id.}
                            {--p|page_id= : Venue page id.}
                            {--m|month= : Month, i.e., 01, 12}
                            {--y|year= : Year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // -m 06 -venue_id 359 -page_id 1623        
        $venuePageId = $this->option('page_id') ?? $this->ask('Page ID?');
        $venueId     = $this->option('venue_id') ?? $this->ask('Venue ID?');
        $year        = $this->option('year') ?? $this->ask('Year?');
        $month       = $this->option('month') ?? $this->ask('Month?');

        $events = new ShovelEventsSchedule($venuePageId, $venueId, $year, $month);
        $httpResponse = $events->getHttpResponse();

        if ($httpResponse !== 200) {
            $this->error("Error.");
            $this->error("HTTP Response: {$httpResponse}.");
            $this->error("URL: {$events->url()}.");
            return 0;
        }
        dd($events->events());
        return 1;
    }
}
