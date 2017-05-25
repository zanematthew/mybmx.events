<?php

namespace Tests\Unit;

use Tests\TestCase;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use App\ShovelEventNonUsaBmxHosted;

class ShovelEventNonUsaBmxHostedTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $eventHtml = file_get_contents(base_path('tests/Unit/data/event-gcq.html'));

        $mock = new MockHandler([
            new Response(200, ['Content-type' => 'text/html'], $eventHtml),
        ]);

        $httpClient = new GuzzleClient([
            'handler' => HandlerStack::create($mock),
        ]);

        $goutte = new GoutteClient();
        $goutte->setClient($httpClient);

        $eventNonUsaBmxHosted = new ShovelEventNonUsaBmxHosted('https://www.usabmx.com/site/bmx_races');
        $eventNonUsaBmxHosted->setClient($goutte);
        $this->eventNonUsaBmxHosted = $eventNonUsaBmxHosted;
    }

    public function testFee()
    {
        $this->assertEquals(30, $this->eventNonUsaBmxHosted->fee());
    }

    public function testRegistrationStartTime()
    {
        $this->assertEquals('3:00 PM', $this->eventNonUsaBmxHosted->registrationStartTime());
    }

    public function testRegistrationEndTime()
    {
        $this->assertEquals('4:00 PM', $this->eventNonUsaBmxHosted->registrationEndTime());
    }
}
