<?php

namespace App;

class ShovelVenueIdByStateAbbr extends AbstractShovel
{
    use ShovelTrait;

    private $stateAbbr;

    public function __construct($stateAbbr = null)
    {
        $this->stateAbbr = $stateAbbr;
        parent::__construct($this->url());
    }

    public function url()
    {
        // is valid state
        return 'http://usabmx.com/site/bmx_tracks/by_state?section_id=12&state='.$this->stateAbbr;
    }

    /**
     * Parses for the Venue ID, and Venue Title.
     *
     * @return array    An array containing;
     *                     'id'    => 123,
     *                     'title' => 'venue title'
     */
    public function parseVenueId(): array
    {
        return $this->filter('#track_results li')->each(function ($node) {
            return [
                'id' => explode('?', explode('/', $node->filter('.track_title a')->attr('href'))[3])[0],
                'title' => $node->filter('.track_title')->text(),
            ];
        });
    }
}
