<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteEventTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSingleEvent()
    {
        $event = factory(\App\Event::class)->create();

        $response = $this->get(route('event.single', [
            'id'   => $event->id,
            'slug' => str_slug($event->title),
        ]));
        $response->assertJson([
            'id'                      => $event->id,
            'created_at'              => $event->created_at,
            'updated_at'              => $event->updated_at,
            'title'                   => $event->title,
            'type'                    => $event->type,
            'url'                     => $event->url,
            'fee'                     => $event->fee,
            'registration_start_time' => $event->registration_start_time,
            'registration_end_time'   => $event->registration_end_time,
            'start_date'              => $event->start_date,
            'end_date'                => $event->end_date,
            'flyer_uri'               => $event->flyer_uri,
            'event_schedule_uri'      => $event->event_schedule_uri,
            'hotel_uri'               => $event->hotel_uri,
            'usabmx_track_id'         => $event->usabmx_track_id,
            'usabmx_id'               => $event->usabmx_id,
            'venue_id'                => $event->venue_id,
            'venue' => [
                'id'              => $event->venue->id,
                'created_at'      => $event->venue->created_at,
                'updated_at'      => $event->venue->updated_at,
                'name'            => $event->venue->name,
                'district'        => $event->venue->district,
                'usabmx_id'       => $event->venue->usabmx_id,
                'website'         => $event->venue->website,
                'image_uri'       => $event->venue->image_uri,
                'description'     => $event->venue->description,
                'street_address'  => $event->venue->street_address,
                'zip_code'        => $event->venue->zip_code,
                'lat'             => $event->venue->lat,
                'long'            => $event->venue->long,
                'email'           => $event->venue->email,
                'primary_contact' => $event->venue->primary_contact,
                'phone_number'    => $event->venue->phone_number,
                'city_id'         => $event->venue->city_id,
                'city'            => [
                    'id'         => $event->venue->city->id,
                    'created_at' => $event->venue->city->created_at,
                    'updated_at' => $event->venue->city->updated_at,
                    'name'       => $event->venue->city->name,
                ]
            ]
        ]);
    }

    public function testPaginatorEvents()
    {
        $event = factory(\App\Event::class, 20)->create();

        $response = $this->get(route('events'));

        $response->assertJson([
            'total'         => 20,
            'per_page'      => 10,
            'current_page'  => 1,
            'last_page'     => 2,
            'next_page_url' => route('events', ['page' => 2]),
            'prev_page_url' => null,
            'data'          => [[
                'venue' => [
                    'city' => []
                ]
            ]]
        ]);
    }

    public function testPaginatorEventsPerState()
    {
        $cityState = factory(\App\CityState::class)->create();
        $venue     = factory(\App\Venue::class)->create(['city_id' => $cityState->city_id]);
        $events    = factory(\App\Event::class, 5)->create(['venue_id' => $venue->id]);

        $state = \App\State::find($cityState->state_id);

        $response = $this->get(route('events.state', [
            'state' => $state->abbr,
        ]));

        $response->assertJson([
            'total' => 5
        ]);
    }
}