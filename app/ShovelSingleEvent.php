<?php

namespace App;

class ShovelSingleEvent extends AbstractShovelClient
{
    use ShovelTrait;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
        parent::__construct($this->url());
    }

    public function url()
    {
        return 'http://usabmx.com/site/bmx_races/'.$this->eventId;
    }

    public function idFromShareLinks()
    {
        $uri = $this->filter('.share_race a')->eq(0)->attr('href');
        return current(explode('?', last(explode('/', $uri))));
    }

    public function title()
    {
        return $this->filter('#event_title')->text();
    }

    // Most events just say "There is no description for this race."
    public function description(){}

    public function venueId()
    {
        return last(explode('/', $this->filter('#venue_title a')->eq(0)->attr('href')));
    }

    // Only for Nationals/USABMX hosted events
    // These have a START DATE, and END DATE.
    public function date()
    {
        $date = $this->filter('#event_date')->eq(0)->text();
        if (strpos($date, '-') !== false) {
            $date = array_map('trim', explode('-', $date));
        } else {
            $date = trim($date);
        }
        return $date;
    }

    public function startDate()
    {
        return current($this->date());
    }

    public function endDate()
    {
        return last($this->date());
    }

    public function parseResource($resource = null)
    {
        $resources = $this->filter('.race_resources li')->each(function ($node) use ($resource) {
            // Note, can't use `snake_case()`, because that makes BMX into b_m_x
            return [str_replace(' ', '_', strtolower($node->text())) => $node->filter('a')->attr('href')];
        });

        $tmp = [];
        // TODO figure out why the array returned is nested(?)
        foreach ($resources as $r){
            foreach ($r as $k => $v) {
                $tmp[$k] = $v;
            }
        }
        return $tmp[$resource] ?? null;
    }

    public function flyerUri()
    {
        return $this->parseResource('event_flyer');
    }

    public function eventScheduleUri()
    {
        return $this->parseResource('event_schedule');
    }

    public function hotelUri()
    {
        return $this->parseResource('bmx_hotels');
    }

    // public function preSignUri(){}
    // Only for earned double, rfl, gcq, state race)(){}
    public function parseDescription($text = null): string
    {
        if (empty($text)) {
            return '';
        }

        $textArray = $this->filter('#event_description li')->each(function ($node) use ($text) {
            return $node->filter('li')->text();
        });

        $found = null;
        foreach ($textArray as $item) {
            if (str_contains($item, $text)) {
                $found = $item;
            }
        }
        return trim(last(explode(': ', $found)));
    }

    public function fee()
    {
        return $this->parseDescription('Entry Fee');
    }

    public function registrationStartTime()
    {
        return $this->parseDescription('Registration Begins');
    }

    public function registrationEndTime()
    {
        return $this->parseDescription('Registration Ends');
    }
}
