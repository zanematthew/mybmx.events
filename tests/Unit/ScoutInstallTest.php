<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoutInstallTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify the class uses Laravel Scout
     *
     * @group scout-install
     * @test
     */
    public function event_uses_scout()
    {
        $this->assertTrue(in_array('Laravel\Scout\Searchable', class_uses('App\Event')));
    }

    /**
     * Verify that a searchable array does exists, and contains
     * the values we desire to search on.
     *
     * @group scout-install
     * @test
     */
    public function event_has_valid_searchable_array()
    {
        $event = factory(\App\Event::class)->create();

        $this->assertTrue([
            'title',
            'type',
            'registration',
            'latitude',
            'longitude',
            'latlon',
            'city',
            'state',
            'z_type',
            'created_at',
        ] === array_keys($event->toSearchableArray()));
    }

    /**
     * Verify the classes uses Laravel\Scout\Searchable
     *
     * @group  scout-install
     * @test
     */
    public function venue_uses_scout()
    {
        $this->assertTrue(in_array('Laravel\Scout\Searchable', class_uses('App\Venue')));
    }

    /**
     * Verify that a searchable array does exists, and contains
     * the values we desire to search on.
     *
     * @group scout-install
     * @test
     */
    public function venue_has_valid_searchable_array()
    {
        $venue = factory(\App\Venue::class)->create();

        $this->assertTrue([
            'name',
            'website',
            'description',
            'zip_code',
            'latitude',
            'longitude',
            'latlon',
            'city',
            'state',
            'z_type',
            'created_at',
        ] === array_keys($venue->toSearchableArray()));
    }
}
