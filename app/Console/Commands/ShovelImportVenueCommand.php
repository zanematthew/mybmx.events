<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Venue;
use App\City;
use App\State;
use App\CityState;

class ShovelImportVenueCommand extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:import-venue
                            {--f|file_path= : The full file path.}
                            {--s|save : Save to the database.}
                            {--r|remove_file : Remove import from file when done.}
                            {--o|overwrite : If the venue already exists, overwrite the existing item.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports a venue into the database based on the full file path.';

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
        // ask for file
        $filePath = $this->option('file_path') ?? $this->ask("Please enter file path:");

        // verify file
        $exists = Storage::disk('local')->exists($filePath);
        if ($exists === false) {
            $this->error("File not found: {$filePath}.");
            return 0;
        }

        // read contents of file
        $contents = Storage::get($filePath);

        // display contents of file
        $contentsArray = json_decode($contents, true);
        if (empty($contentsArray)) {
            $this->error("Empty file: {$filePath}.");
            return 0;
        }
        $venue = \App\Venue::where('name', $contentsArray['name'])->first();

        // Handle overwrite
        if ($venue) {
            $overwrite = $this->option('overwrite') ?: $this->choice("Venue {$venue->name} ({$venue->id}) exists, overwrite?", ['Y', 'N'], 1);
            if ($overwrite === 'N') {
                $this->info('Done.');
                return 1;
            }
        } else {
            $venue = new Venue;
        }

        // Venue has State
        if (empty($contentsArray['stateAbbr'])) {
            $this->error('Missing state.');
            return 0;
        }

        if ($this->isStateValid($contentsArray['stateAbbr']) === false) {
            $this->error("State invalid: {$contentsArray['stateAbbr']}");
            return 0;
        }

        // Display info
        $this->info("Ready to import.");
        foreach ($contentsArray as $k => $v) {
            $this->comment("{$k}: {$v}");
        }

        // Create state if not exists.
        $state = \App\State::where('abbr', $contentsArray['stateAbbr'])->first();
        if ($state === null) {
            $this->comment("State does not exists, creating state...");
            $state = new State;
            $state->name = $this->states()[ $contentsArray['stateAbbr'] ];
            $state->abbr = $contentsArray['stateAbbr'];
            $state->save();
            $this->info("State ID: {$state->id}.");
        }

        // Create city if not exists.
        $city = \App\City::where('name', $contentsArray['city'])->first();
        if ($city === null) {
            $this->comment("City does not exists, creating city...");
            $city = new City;
            $city->name = $contentsArray['city'];
            $city->save();
            $this->info("City ID: {$city->id}.");
        }

        // Link CityState.
        $state->cities()->syncWithoutDetaching([ $city->id ]);
        $state->save();

        // Assign venue.
        $venue->name            = $contentsArray['name'];
        $venue->district        = $contentsArray['district'] ?? null;
        $venue->usabmx_id       = $contentsArray['id'];
        $venue->website         = $contentsArray['websiteUri'] ?? null;
        $venue->image_uri       = $contentsArray['logoUri'] ?? null;
        $venue->description     = $contentsArray['description'] ?? null;
        $venue->streetAddress   = $contentsArray['street'] ?? null;
        $venue->zipCode         = $contentsArray['zipCode'] ?? null;
        $venue->lat             = $contentsArray['lat'] ?? null;
        $venue->long            = $contentsArray['long'] ?? null;
        $venue->email           = $contentsArray['email'] ?? null;
        $venue->primaryContact  = $contentsArray['primaryContact'] ?? null;
        $venue->phoneNumber     = $contentsArray['phone'] ?? null;

        // Associate city.
        $venue->city()->associate($city->id);

        // Update DB record.
        $saved = $venue->save();

        // $venue->country = $contentsArray['country'];
        // $venue->scheduleUri = $contentsArray['scheduleUri'];
        // $venue->mapUri = $contentsArray['mapUri'];

        // Handle saved.
        if ($saved === false) {
            $this->error("Failed to save: {$venue->name}.");
            return 0;
        }

        $this->info('Saved.');
        $remove_file = $this->option('remove_file') ?: $this->choice('Remove file?', ['Y', 'N'], 1);
        if ($remove_file === 'N') {
            $this->info('Done.');
            return 1;
        }

        // Handle delete.
        $deleted = Storage::delete($filePath);
        if ($deleted === false) {
            $this->error("Failed to delete: {$filePath}.");
            return 0;
        }

        $this->info('Deleted file.');
        return 1;
    }
}
