<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElasticsearchVenueTest extends TestCase
{
    /**
     * @group elasticsearch-venue-test
     * @test
     */
    public function has_static_mapping()
    {
        $map = [
            'name'        => ['type' => 'text'],
            'website'     => ['type' => 'text'],
            'description' => ['type' => 'text'],
            'zip_code'    => ['type' => 'integer'],
            'latitude'    => ['type' => 'float'],
            'longitude'   => ['type' => 'float'],
            'latlon'      => ['type' => 'geo_point'],
            'city'        => ['type' => 'keyword'],
            'state'       => ['type' => 'keyword'],
        ];
        $this->assertTrue($map === \App\Venue::elasticsearchMapping());
    }

    /**
     * Both the searchable array and index mapping must contain the same keys
     *
     * @group elasticsearch-venue-test
     * @test
     */
    public function searchable_array_mapping()
    {
        $venue = factory(\App\Venue::class)->create();
        $this->assertEmpty(array_diff(
            array_keys($venue->toSearchableArray()),
            array_keys(\App\Venue::elasticsearchMapping())
        ));
    }
}
