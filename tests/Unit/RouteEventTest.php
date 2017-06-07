<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Event as Event;

class RouteEventTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEvents()
    {
        $event = factory(Event::class)->create();
        $response = $this->get(route('event.single', [
            'id'   => $event->id,
            'slug' => str_slug($event->title),
        ]));
        $response->assertViewHas([
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
}
