<?php

namespace App;

class ShovelVenueIdByStateAbbr extends Shovel
{
    use ShovelTrait;

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
