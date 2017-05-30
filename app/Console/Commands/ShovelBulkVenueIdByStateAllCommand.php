<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelBulkVenueByState as VenueByState;

class ShovelBulkVenueIdByStateAllCommand extends Command
{

    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:bulk-venue-ids-by-state
                            {--s|save : Save the results to disk.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will retrieve ALL venue IDs by state. Optionally will save ALL venue IDs as a JSON file, with a single field i.e., 1,12,89,234,236.';

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
        // $states = $this->states();
        $states = array_keys([
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
        ]);

        $this->line('Starting request...');
        $bar = $this->output->createProgressBar(count($states));
        foreach ($states as $state) {
            $venue        = new VenueByState($state);
            $httpResponse = $venue->getHttpResponse();

            // @TODO log these to disk.
            if ($httpResponse !== 200) {
                $this->error("Error.");
                $this->error("HTTP Response: {$httpResponse}.");
                $this->error("URL: {$venue->url()}.");
                continue;
            }
            $id = $venue->parseVenueId();
            $venuesArray[ $state ] = [
                'url'    => $venue->url(),
                'count'  => count($id),
                'venues' => array_pluck($id, 'id'),
            ];
            $bar->advance();
        }
        $bar->finish();

        // Total venues
        $totalVenues = 0;
        foreach ($venuesArray as $venueArray) {
            $totalVenues += count($venueArray['venues']);
        }

        $venuesFlatArray = [];
        foreach ($venuesArray as $k => $v) {
            $venuesFlatArray = array_merge($venuesFlatArray, $v['venues']);
        }
        sort($venuesFlatArray);

        $this->line("\n");
        $this->info("Total venues: {$totalVenues}.");
        $this->info(
            sprintf(
                "Results: %s.",
                implode(',', $venuesFlatArray)
            )
        );

        $save = $this->option('save') ?: $this->choice("Save to disk?", ['Y', 'N'], 1);
        if ($save === 'N') {
            $this->info('Done.');
            // @TODO use exit codes vs. return;
            return;
        }

        $filename = sprintf(
            '%s-%s',
            date('d-M-Y-H:i:s'),
            str_slug("all venue ids", '-')
        );
        if (!$this->saveToJson($filename, $venuesFlatArray, 'venues/bulk')) {
            $this->error("Failed to save file: {$filename}.");
            return;
        }
        $this->info('Saved.');
    }
}
