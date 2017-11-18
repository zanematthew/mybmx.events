<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoutEventTest extends TestCase
{
    /**
     * Verify the classes uses Laravel\Scout\Searchable
     *
     * @group  scout-event-test
     * @test
     */
    public function uses_scout()
    {
        $this->assertTrue(in_array('Laravel\Scout\Searchable', class_uses('App\Event')));
    }

    /**
     * Verify that a searchable array does exists, and contains
     * the values we desire to search on.
     *
     * @group scout-event-test
     * @test
     */
    public function has_searchable_array()
    {
        $event = factory(\App\Event::class)->create();
        $this->assertEmpty(array_diff([
            'title',
            'type',
            'datetime',
            'latitude',
            'longitude',
            'latlon',
            'city',
            'state',
        ], array_keys($event->toSearchableArray())));
    }
}
