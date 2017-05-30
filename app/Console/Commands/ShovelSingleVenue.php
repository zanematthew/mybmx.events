<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelSingleVenue as VenueDetail;

class ShovelSingleVenue extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:single-venue-by-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'You give me venue ID, I give you venue detail.';

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
        $venueIdAsk = $this->ask("Enter Venue ID?");

        $venue        = new VenueDetail($venueIdAsk);
        $httpResponse = $venue->getHttpResponse();

        if ($httpResponse !== 200) {
            $this->error("HTTP Response: {$httpResponse}.");
            $this->error("URL: {$venue->url()}.");
            $this->error("Venue ID: {$venueIdAsk} may not exists. Needs manual inspection.");
            return;
        }

        $name = $venue->parseName();

        $this->info("HTTP Response: {$httpResponse}.");
        $this->info("URL: {$venue->url()}.");
        $this->info("Retrieved detail for: {$name}.");

        $detail = [
            'name'        => $name,
            'description' => str_limit($venue->parseDescription().'[...]', 25),
            'district'    => $venue->parseDistrict()
        ];

        foreach ($venue->contact() as $key => $value) {
            $tmp[ $key ] = $value;
        }
        $detail = array_merge($detail, $tmp);

        $location = [
            'street'    => $venue->getStreet(),
            'city'      => $venue->getCity(),
            'stateAbbr' => $venue->getStateAbbr(),
            'zipCode'   => $venue->getZipCode(),
            'country'   => $venue->getCountry(),
            'lat'       => $venue->parseLatLongMapsUri()['lat'],
            'long'      => $venue->parseLatLongMapsUri()['long'],
        ];

        $links = [
            'scheduleUri' => $venue->parseScheduleUri(),
            'mapUri'      => $venue->parseMapUri(),
            'websiteUri'  => $venue->parseWebsiteUri(),
            'logoUri'     => $venue->parseLogo(),
        ];

        $this->info("\nDetail:");
        $this->table(array_keys($detail), [$detail]);
        $this->info("\nLocation:");
        $this->table(array_keys($location), [$location]);
        $this->info("\nLinks:");
        $this->table(array_keys($links), [$links]);

        $saveAsk = $this->choice("Save to disk?", ['Y','N'], 1);

        if ($saveAsk === "N") {
            $this->info("Done.");
            return;
        }

        $venueDetail = array_merge($detail, $location, $links);

        $filename = sprintf(
            '%s-%s-%d',
            date('d-M-Y-H:i:s'),
            str_slug($name, '-'),
            $venueIdAsk
        );

        $saved = $this->saveToJson($filename, $venueDetail, 'venues/detail');
        if ($saved !== true) {
            $this->error("Error, failed to save: {$filename}.");
        }
        $this->info("Saved to file: {$filename}.");
    }
}
