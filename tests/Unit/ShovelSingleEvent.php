<?php

namespace Tests\Unit;

use Tests\TestCase;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use App\ShovelSingleEvent;

class ShovelSingleEventTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $eventHtml = file_get_contents(base_path('tests/Unit/data/event.html'));

        $mock = new MockHandler([
            new Response(200, ['Content-type' => 'text/html'], $eventHtml),
        ]);

        $httpClient = new GuzzleClient([
            'handler' => HandlerStack::create($mock),
        ]);

        $goutte = new GoutteClient();
        $goutte->setClient($httpClient);

        $event = new ShovelSingleEvent(123);
        $event->setClient($goutte);
        $this->event = $event;
    }

    public function testIdFromShareLinks()
    {
        $this->assertEquals(334979, $this->event->idFromShareLinks());
    }

    public function testTitle()
    {
        $this->assertEquals('State Race Double', $this->event->title());
    }

    public function testVenueId()
    {
        $this->assertEquals(196, $this->event->venueId());
    }

    public function testUrl()
    {
        $this->assertEquals('http://usabmx.com/site/bmx_races/123', $this->event->url());
    }
}
