<?php

namespace Tests\Unit;

use Tests\TestCase;
use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use App\ShovelBulkVenueByState;

class ShovelBulkVenueByStateTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        // @TODO Move this into its own custom parent setUp(); ?
        $venueHtml = file_get_contents(base_path('tests/Unit/data/venues-by-state-abbr.html'));
        $mock      = new MockHandler([
            new Response(404, ['Content-type' => 'text/html'], $venueHtml),
        ]);

        $httpClient = new GuzzleClient([
            'handler' => HandlerStack::create($mock),
        ]);

        $goutte = new GoutteClient();
        $goutte->setClient($httpClient);

        $venueIdByStateAbbr = new ShovelBulkVenueByState('MD');
        $venueIdByStateAbbr->setClient($goutte);
        $this->venueIdByStateAbbr = $venueIdByStateAbbr;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testParseVenueIdsByStateAbbr()
    {
        $this->assertArraySubset([
            [
                'id'    => '572',
                'title' => 'Hagerstown BMX',
            ],
            [
                'id'    => '410',
                'title' => 'Southern Maryland BMX',
            ],
            [
                'id'    => '589',
                'title' => 'Riverside BMX',
            ],
            [
                'id'    => '359',
                'title' => 'Chesapeake BMX',
            ],
        ], $this->venueIdByStateAbbr->parseVenueId());
    }

    public function testUrl()
    {
        $this->assertEquals(
            'http://usabmx.com/site/bmx_tracks/by_state?section_id=12&state=MD',
            $this->venueIdByStateAbbr->url()
        );
    }
}
