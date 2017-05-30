<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelBulkVenueByState as VenueByState;

class ShovelBulkVenueByStateCommand extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:bulk-venue-id-by-state
                            {--state= : State Abbreviation.}
                            {--s|save : Save to disk.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve a list of Venue IDs (and names) by state abbreviation.';

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
        $state = $this->option('state') ?? $this->ask("Enter state by abbreviation, i.e., 'MD'");

        $this->line("Sending request...");
        $venue        = new VenueByState($state);
        $httpResponse = $venue->getHttpResponse();

        if ($httpResponse !== 200) {
            $this->error("Error.");
            $this->error("HTTP Response: {$httpResponse}.");
            $this->error("URL: {$venue->url()}.");
            return;
        }

        $id = $venue->parseVenueId();
        $results = [
            'url'    => $venue->url(),
            'count'  => count($id),
            'venues' => $id,
        ];

        $this->line("Requested results.");
        $this->line("State: {$state}");
        $this->line("Count: {$results['count']}");
        $this->line("URL: {$results['url']}");
        $this->table(['Venue(s)'], [
            ['Venue' => implode(PHP_EOL, array_pluck($results['venues'], 'title'))]
        ]);

        $save = $this->option('save') ?: $this->choice("Save to disk?", ['Y', 'N'], 1);
        if ($save === "N") {
            $this->info('Done.');
            return;
        }

        $filename = sprintf(
            '%s-%s',
            date('d-M-Y-H:i:s'),
            str_slug("{$state} venue ids", '-')
        );

        if (!$this->saveToJson($filename, $results, 'venues/bulk')) {
            $this->error("Failed to save file: {$filename}.");
            return;
        }
        $this->info('Saved.');
    }
}
