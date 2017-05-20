<?php
/**
 * @todo Needs UnitTet
 * @todo Separate this into two commands(?); get Events by Type, and get Events by Year.
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelEvent as ShovelEvent;

class ShovelEventsIndexToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:index-events-to-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index a series of Events, and save as JSON in the storage directory.';

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
    public function handle(ShovelEvent $shovel)
    {
        $event     = $this->choice('Event type? Press enter for:', array_keys($shovel->eventTypes), 0);
        $year      = $this->choice('Event year? Press enter for:', $shovel->validYears(), 0);
        $pageRange = $this->ask('Page range? [1, 1-5, 5-10, etc.]');

        list($initialPage, $endPage) = $shovel->parsedPageRange($pageRange);
        if (in_array($event, array_keys($shovel->eventTypes))) {
            $bar      = $this->output->createProgressBar($endPage);
            $urls     = [];
            $savedTo  = [];
            $statuses = [];

            $this->info('Starting request...');

            while ($initialPage <= $endPage) {
                $url      = $shovel->buildUrl($event, $initialPage, $year);
                $urls[]   = $url;
                $results  = $shovel->getEvents($url);
                $status   = "No results";
                $filename = "--";

                if (!empty($results)) {
                    $status = count($results);
                    $filename = sprintf('%s-%s',
                        date('d-M-Y-H:i:s'),
                        str_slug("{$event} {$year} page {$initialPage} events", '-')
                    );
                    $shovel->saveToJson($filename, $results, 'events');
                }

                $savedTo[]  = $filename;
                $statuses[] = $status;

                $bar->advance();
                $initialPage++;
            }

            $bar->finish();
            $this->line("\n");

            $data = [
                'Type'    => $event,
                'Year'    => $year,
                'URL(s)'  => implode(PHP_EOL, $urls),
                'File(s)' => implode(PHP_EOL, $savedTo),
                'Count'   => implode(PHP_EOL, $statuses),
            ];

            $this->table(array_keys($data), [$data]);
        }
    }
}
