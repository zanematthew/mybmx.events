<?php

namespace App;

class ShovelSingleEvent extends AbstractShovel
{
    use ShovelTrait;

    private $eventId;

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
}
