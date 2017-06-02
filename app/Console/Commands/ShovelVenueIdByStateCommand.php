<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelVenueByState as VenueByState;
use Illuminate\Support\Facades\Storage;

class ShovelVenueIdByStateCommand extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:venue-id-by-state
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

        $id      = $venue->parseVenueId();
        $idCount = count($id);
        $result = [
            'url'    => $venue->url(),
            'venues' => $id,
        ];

        $this->line("Results.");
        $this->line("State: {$state}");
        $this->line("Count: {$idCount}");
        $this->line("URL: {$result['url']}");
        $this->line(sprintf('ID(s) received: %s', implode(',', $id)));

        $save = $this->option('save') ?: $this->choice("Save to disk?", ['Y', 'N'], 1);
        if ($save === "N") {
            $this->info('Done.');
            return;
        }

        $filename = str_slug("{$state} venue ids", '-');
        $saved    = Storage::disk('local')->put(
            "public/venues/bulk/{$filename}.json",
            json_encode($id, JSON_FORCE_OBJECT)
        );

        if ($saved === false) {
            $this->error("Failed to save file: {$filename}.");
            return false;
        }
        $this->info('Saved.');
        return true;
    }
}
