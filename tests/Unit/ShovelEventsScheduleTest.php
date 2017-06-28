<?php

namespace Tests\Unit;

use Tests\TestCase;
use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use App\ShovelEventsSchedule;

class ShovelEventsScheduleTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        // @TODO Move this into its own custom parent setUp(); ?
        $venueHtml = file_get_contents(base_path('tests/Unit/data/venue-schedule-1623.html'));
        $mock      = new MockHandler([
            new Response(404, ['Content-type' => 'text/html'], $venueHtml),
        ]);

        $httpClient = new GuzzleClient([
            'handler' => HandlerStack::create($mock),
        ]);

        $goutte = new GoutteClient();
        $goutte->setClient($httpClient);

        $venueSchedule = new ShovelEventsSchedule(1623, 359, 2017, 7);
        $venueSchedule->setClient($goutte);
        $this->venueSchedule = $venueSchedule;
    }

    public function testBuildUlr(){
        $this->assertEquals(
            'http://usabmx.com/tracks/1623/events/schedule?mode=calendar&month=7&year=2017',
            $this->venueSchedule->buildUrl()
        );
    }

    public function testEventIds()
    {
        $this->assertTrue(in_array(330379, $this->venueSchedule->eventIds()));
    }    

    public function testFeeFix()
    {
        $this->assertEquals(10, $this->venueSchedule->feeFix('$$10'));
        $this->assertEquals(10, $this->venueSchedule->feeFix('$10'));
        $this->assertEquals(10, $this->venueSchedule->feeFix('$10.00'));
        $this->assertEquals('10.50', $this->venueSchedule->feeFix('$10.50 USD'));
    }

    public function testWtfTimeFix()
    {
        $this->assertEquals('7PM', $this->venueSchedule->wtfTimeFix('7M'));
        $this->assertEquals('7PM', $this->venueSchedule->wtfTimeFix('7', 'practice'));
        $this->assertEquals('7', $this->venueSchedule->wtfTimeFix('7'));
        $this->assertEquals('7PM', $this->venueSchedule->wtfTimeFix('7PM', 'clinic'));
    }

    public function testEvents()
    {
        $this->assertCount(14, $this->venueSchedule->events());

        $this->assertArraySubset([
            'registration_start_time' => '19:00',
            'registration_end_time'   => '20:00',
            'type'                    => 'Local Race',
            'fee'                     => 1000,
            'event_id'                => '330382',
            'start_date'              => '2017-7-16',
            'title'                   => '',
            'venue_id'                => 359,
        ], $this->venueSchedule->events()[7]);
    }
}
