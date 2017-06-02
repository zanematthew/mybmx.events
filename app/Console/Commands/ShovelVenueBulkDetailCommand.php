<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ShovelVenueBulkDetailCommand extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:venue-detail-bulk
                            {--c|count= : Amount of IDs to process.}
                            {--f|file= : File name to retrieve list of IDs from.}
                            {--s|save : Save to disk.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request venue detail for previously saved venue IDs.';

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
        $requestedCount = $this->option('count') ?? $this->ask('Number of IDs to request detail for?');
        if (is_numeric($requestedCount) === false) {
            $this->error("Not a number: {$requestedCount}.");
            return;
        }

        $venueIdsJsonfile = array_values(array_filter(array_map(function ($dirFile) {
            if (str_contains($dirFile, '.json')) {
                return $dirFile;
            }
        }, Storage::files('public/venues/bulk/'))));

        $fileToProcess = $this->option('file') ?? $this->choice('Select a file to process?', $venueIdsJsonfile);

        if (empty($venueIdsJsonfile)) {
            $this->error('No venue ID file(s) found. Please create venue ID file(s).');
            return;
        }

        $contents      = json_decode(Storage::get($fileToProcess), true);
        $contentsCount = count($contents);

        if ($requestedCount > $contentsCount) {
            $this->error('Requested to process more IDs then in file.');
            $this->comment("Requested: {$requestedCount}.");
            $this->comment("Allowed:  {$contentsCount}.");
            return;
        }

        // array rand returns an int when only one value is found.
        $randomIdKeys = (array) array_rand($contents, $requestedCount);
        foreach ($randomIdKeys as $randomIdkey) {
            $randomIdsToProcess[] = $contents[ $randomIdkey ];
        }
        $this->comment("Processing: {$requestedCount} random ID(s) from: {$fileToProcess}.");

        // For each random ID request detail
        $failedIdsToProcess = [];
        foreach ($randomIdsToProcess as $randomIdToProcess) {
            // run artisan command.
            // $exitCode = rand(0,1);// php artisan shovel:venue-by-id -i $randomIdToProcess -s
            // $this->callSilent()
            $exitCode = $this->call('shovel:venue-by-id', [
                '--venue_id' => $randomIdToProcess,
                '--save'   => true,
            ]);
            if ($exitCode === false) {
                $failedIdsToProcess[] = $randomIdToProcess;
                continue;
            }
            $processedIds[] = $randomIdToProcess;
        }

        // $processedIds      = array_diff($randomIdsToProcess, $failedIdsToProcess);
        $stillToProcessIds = array_diff($contents, $processedIds);

        if (empty($stillToProcessIds)) {
            $this->info('All IDs are now processed.');
            $this->info('File removed.');
            Storage::delete($fileToProcess);
            $this->info('Done.');
            return false;
        }

        $fileInfo = pathinfo(basename($fileToProcess));
        $result   = array_values($stillToProcessIds);
        $filename = $fileInfo['filename'];
        $saved    = Storage::disk('local')->put(
            "public/venues/bulk/{$filename}.json",
            json_encode($result, JSON_FORCE_OBJECT)
        );
        if ($saved === false) {
            $this->error("Failed to save file: {$filename}.");
            return false;
        }

        $this->info("Done processing: {$requestedCount} ID(s).");
        $this->info(sprintf(
            'There are: %s remaining ID(s) that will need processing.',
            count($stillToProcessIds)
        ));
    }
}
