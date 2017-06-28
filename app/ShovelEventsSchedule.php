<?php

namespace App;

class ShovelEventsSchedule extends AbstractShovelClient
{
    use ShovelTrait;

    public function __construct($venuePageId = null, $venueId = null, $year = null, $month = null)
    {
        $this->venuePageId = $venuePageId;
        $this->venueId = $venueId;
        $this->year    = $year ?? date('Y');
        $this->month   = $month;

        parent::__construct($this->buildUrl());
    }

    public function buildUrl(): string
    {
        return 'http://usabmx.com/tracks/'.$this->venuePageId.'/events/schedule?'.http_build_query([
            'mode'  => 'calendar',
            'month' => $this->month,
            'year'  => $this->year,
        ]);
    }

    public function eventIds(): array
    {
        return $this->filter('.calendar-event')->each(function ($node) {
            return explode('_', $node->filter('a')->attr('href'))[1];
        });
    }

    public function events(): array
    {
        // We need to loop over the `calendar_day_inner`, because this contains the date.
        // We can't just loop over `calendar_day_content`.
        $events = $this->filter('.calendar_day_inner')->each(function ($node) {
            // Bail if there is no content, skip over days with no events.
            if ($this->aggressiveTrim($node->filter('.calendar_day_content')->text()) == '') {
                return [];
            }
            // Not all have a title
            if ($node->filter('h6')->count() == 1) {
                $event['title'] = $node->filter('h6')->text();
            }

            $eventId = explode('_', $node
                ->filter('a')
                ->attr('href'))[1];

            if ($node->filter('p')->count() == 1) {
                $contentsArray = array_filter(
                    explode('<br>', str_replace(
                        ['<p>','</p>'],
                        '',
                        $this->aggressiveTrim($node->filter('p')->html())
                    ))
                );
                
                foreach ($contentsArray as $contentArray) {
                    list($key, $value) = explode(': ', $contentArray); // Use ': ', to avoid time issues.
                    $event[ str_slug(strip_tags($key)) ] = $value;
                }
                $event['event_id'] = $eventId;
                $event['start_date']     = sprintf(
                    '%d-%d-%d',
                    $this->year,
                    $this->month,
                    $node->filter('.calendar_day_title')->text()
                );                
            }

            return $event;
        });
        
        $events = array_values(array_filter($events));
        
        $events = array_map(function ($event) {

            unset($event['status']);
            unset($event['race-time']);
            
            if (empty($event['registration-start'])) {
                $event['registration_start_time'] = '';
            } else {
                $event['registration_start_time'] = $this->timeFormat(
                    $this->wtfTimeFix(
                        $event['registration-start'], $event['type']
                    )
                );
                unset($event['registration-start']);
            }

            if (empty($event['registration-end'])) {
                $event['registration_end_time'] = '';
            } else {
                $event['registration_end_time'] = $this->timeFormat(
                    $this->wtfTimeFix(
                        $event['registration-end'], $event['type']
                    )
                );
                unset($event['registration-end']);
            }
            
            $event['title'] = empty($event['title']) ? '' : $event['title'];
            $event['fee'] = empty($event['fee']) ? '' : $this->convertToCents($this->feeFix($event['fee']));
            $event['venue_id'] = $this->venueId;

            return $event;

        }, $events);        
        return $events;
    }

    public function feeFix($fee = null): string
    {
        return str_replace([
            '$',
            'USD',
            ' ',
        ], '', $fee);
    }

    // Most practices and clinics are held in the evening, so if its missing the PM
    // we add it.
    public function wtfTimeFix($time = null, $eventType = null): string
    {
        $time = strtoupper($time);
        preg_match('#\d+M#', $time, $matches);
        if ($matches) {
            $time = str_replace('M', 'PM', $time);
        } elseif (in_array(strtolower($eventType), ['practice', 'clinic'])
            && ! str_contains($time, ['M', 'PM']) ) {
            $time = $time .'PM';
        }
        return $time;
    }

// public function name(): string {}
// public function date(): int {}
// public function registrationStart(): int{}
// public function registrationEnd(): int{}
// public function raceTime(): int{}
// public function type(): string{}
// public function fee(): string{}
// public function status(): string{}
}