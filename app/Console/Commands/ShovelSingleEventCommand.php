<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShovelSingleEvent as ShovelEvent;

class ShovelSingleEventCommand extends Command
{
    use \App\ShovelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shovel:single-event-by-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve Event detail based on an Event ID.';

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

        $eventIdAsk = $this->ask("Enter Event ID?");

        $event        = new ShovelEvent($eventIdAsk);
        $httpResponse = $event->getHttpResponse();

        if ($httpResponse !== 200) {
            $this->error("HTTP Response: {$httpResponse}.");
            $this->error("URL: {$event->url()}.");
            $this->error("Event ID: {$eventIdAsk} may not exists. Needs manual inspection.");
            return;
        }

        $title = $event->title();

        $this->info("HTTP Response: {$httpResponse}.");
        $this->info("URL: {$event->url()}.");
        $this->info("Retrieved detail for: {$title}.");

        $detail = [
            'eventId'               => $event->idFromShareLinks(),
            'venueId'               => $event->venueId(),
            'fee'                   => $event->fee(),
            'registrationStartTime' => $event->registrationStartTime(),
            'registrationEndTime'   => $event->registrationEndTime(),

            // National
            'startDate'             => $event->startDate(),
            'endDate'               => $event->endDate(),
            'flyerUri'              => $event->flyerUri(),
            'eventScheduleUri'      => $event->eventScheduleUri(),
            'hotelUri'              => $event->hotelUri(),
        ];

        $this->info("\nDetail:");
        $this->table(array_keys($detail), [$detail]);

        $saveAsk = $this->choice("Save to disk?", ['Y','N'], 1);

        if ($saveAsk === "N") {
            $this->info("Done.");
            return;
        }

        $filename = sprintf(
            '%s-%s-%d',
            date('d-M-Y-H:i:s'),
            str_slug($title, '-'),
            $eventIdAsk
        );

        $saved = $this->saveToJson($filename, $detail, 'events/detail');
        if ($saved !== true) {
            $this->error("Error, failed to save: {$filename}.");
        }
        $this->info("Saved to file: {$filename}.");
    }
}
