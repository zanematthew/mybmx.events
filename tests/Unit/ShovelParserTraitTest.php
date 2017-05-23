<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ShovelParserTrait;

class ShovelParserTraitTest extends TestCase
{
    use ShovelParserTrait;

    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->getMockForTrait(ShovelParserTrait::class);
    }

    /**
     * Given a specific structure determine the ID
     *
     * @return void
     * @SuppressWarnings(PHPMD)
     */
    public function testVenueId()
    {
        $this->assertEquals('123', $this->mock->venueId('a/a/a/123'));
    }

    public function testEventIdFromUrl()
    {
        $eventId = $this->mock->eventIdFromUrl('https://www.usabmx.com/site/bmx_races/325850?section_id=228');
        $this->assertEquals(325850, $eventId);
    }

    public function testLocationFromP()
    {
        $html     = '<p>26600 Budds Creek Rd<br>Mechanicsville,  Md 20659<br>Usa</p>';
        $location = $this->mock->locationFromP($html);

        $this->assertArraySubset([
            'street'  => '26600 Budds Creek Rd',
            'city'    => 'Mechanicsville',
            'state'   => 'MD',
            'zip'     => '20659',
            'country' => 'USA',
        ], $location);
    }

    public function testLocationFromPmissingStreet()
    {
        $html     = '<p>Egg Harbor Township,  Nj 08234<br>Usa</p>';
        $location = $this->mock->locationFromP($html);

        $this->assertArraySubset([
            'street'  => '',
            'city'    => 'Egg Harbor Township',
            'state'   => 'NJ',
            'zip'     => '08234',
            'country' => 'USA'
        ], $location);
    }

    // public function testIdFromUri(){}
    public function testLatLongFromLinkHref()
    {
        $url     = 'http://maps.google.com/maps?q=39.647549,-77.709078&iwloc=A&iwd = 1';
        $latLong = $this->mock->latLongFromLinkHref($url);

        $this->assertArraySubset([
            "lat" => 39.647549,
            "long" => -77.709078,
        ], $latLong);
    }

    public function testDistrict()
    {
        $this->assertEquals('MD01', $this->mock->districtFromString('District: MD01'));
    }

    // public function testTitle(){}
    // public function testUsaBmxId(){}
    // public function testDescription(){}
    // public function testImageUri(){}
    // public function testScheduleUri(){}
    // public function testWebsite(){}
    // public function testFullLocation(){}
    // public function testStreet(){}
    // public function testCity(){}
    // public function testStateAbbr(){}
    // public function testZipCode(){}
    // public function testLat(){}
    // public function testLong(){}
}
