<?php

namespace Tests\Unit;

use \App\Venue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoutVenueTest extends TestCase
{

    /**
     * Verify the classes uses Laravel\Scout\Searchable
     *
     * @group  scout-venue-test
     * @test
     */
    public function uses_scout()
    {
        $this->assertTrue(in_array('Laravel\Scout\Searchable', class_uses('Venue')));
    }

    /**
     * Verify that a searchable array does exists, and contains
     * the values we desire to search on.
     *
     * @group scout-venue-test
     * @test
     */
    public function has_searchable_array()
    {
        $venue = factory(Venue::class)->create();
        $this->assertEmpty(array_diff([
            'name',
            'website',
            'description',
            'zip_code',
            'latitude',
            'longitude',
            'latlon',
            'city',
            'state',
        ], array_keys($venue->toSearchableArray())));
    }
}
