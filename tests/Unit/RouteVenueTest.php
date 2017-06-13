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
}
