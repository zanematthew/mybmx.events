<?php

namespace Tests\Feature\tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteVenueTest extends TestCase
{

    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testSingleVenue()
    {
        $venue = factory(\App\Venue::class)->create();
        $events = factory(\App\Event::class, 6)->create([
            'venue_id' => $venue->id,
        ]);

        $response = $this->get(route('venue.single', [
            'id' => $venue->id,
        ]));

        $this->assertCount(6, $response->decodeResponseJson()[0]['events']);
    }

    public function testStateVenues()
    {
        factory(\App\Venue::class, 4)->create();

        $cityState = factory(\App\CityState::class)->create();
        factory(\App\Venue::class, 2)->create([
            'city_id' => $cityState->city_id,
        ]);

        // 6 venues total.
        $response = $this->get(route('venues.state'));
        $this->assertCount(6, $response->decodeResponseJson()['data']);

        // 2 are in MD
        $state = \App\State::find($cityState->state_id);
        $response = $this->get(route('venues.state', [
            'state' => $state->abbr,
        ]));
        $this->assertCount(2, $response->decodeResponseJson()['data']);
    }
}
