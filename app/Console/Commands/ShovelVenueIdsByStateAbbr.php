<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelVenueIdByStateAbbr as VenueByState;

class ShovelVenueIdsByStateAbbr extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:venue-ids-by-state-abbr';

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
        $stateAsk       = $this->ask("Enter state by abbreviation, i.e., 'CA', 'MD PA', 'All'");
        $statesAskArray = array_map('strtoupper', explode(' ', $stateAsk));
        $allStates      = $this->states();

        if ($stateAsk == 'All') {
            $states = array_keys($allStates);
        } else {
            $states = array_intersect($statesAskArray, array_keys($allStates));
        }

        $statesData = [];
        $venueCount = [];
        $venues     = [];
        $bar        = $this->output->createProgressBar(count($states));

        foreach ($states as $state) {
            $venueIdByStateAbbr = new VenueByState($this->buildVenueStateUrl($state));
            $results             = $venueIdByStateAbbr->parseVenueId($state);

            if (!empty($results)) {
                $statesData[] = $state;
                $venues       = array_merge($results, $venues);
                $status       = count($results);

                $filename = sprintf('%s-%s',
                    date('d-M-Y-H:i:s'),
                    str_slug("{$state} venue ids", '-')
                );
                $this->saveToJson($filename, $results, 'venues');
            }

            $savedTo[]  = $filename;
            $statuses[] = $status;

            $bar->advance();
        }

        $bar->finish();
        $this->line("\n");

        $data = [
            'State'       => implode(PHP_EOL, $statesData),
            'File(s)'     => implode(PHP_EOL, $savedTo),
            'Count'       => implode(PHP_EOL, $statuses),
        ];

        $this->table(array_keys($data), [$data]);
    }
}
