<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElasticsearchEventTest extends TestCase
{
    /**
     * @group elasticsearch-event-test
     * @test
     */
    public function has_static_mapping()
    {
        $map = [
            'title'    => ['type' => 'text'],
            'type'     => ['type' => 'keyword'],
            'datetime' => ['type' => 'date'],
        ];
        $this->assertTrue($map === \App\Event::elasticsearchMapping());
    }

    /**
     * Both the searchable array and index mapping must contain the same keys
     *
     * @group elasticsearch-event-test
     * @test
     */
    public function searchable_array_mapping()
    {
        $event = factory(\App\Event::class)->create();
        $this->assertEmpty(array_diff(
            array_keys($event->toSearchableArray()),
            array_keys(\App\Event::elasticsearchMapping())
        ));
    }
}
