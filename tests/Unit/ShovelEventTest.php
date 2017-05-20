<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\ShovelEvent;

class ShovelEventTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->event = new ShovelEvent;
        $this->url   = 'https://www.usabmx.com/site/bmx_races';
    }

    /**
     * Ensures that we can parse a start date, and an end date
     * from an entry as [date - date]
     *
     * @return void
     * @SuppressWarnings(PHPMD)
     */
    public function testDate()
    {
        $this->assertEquals('July 01, 2016', $this->event->getStartDate(' July 01, 2016 - July 03, 2016'));
        $this->assertEquals('July 03, 2016', $this->event->getEndDate("\n\n\t  July 01, 2016 - July 03, 2016\t\t\n"));
        $this->assertEquals('July 01, 2016', $this->event->getStartDate(' July 01, 2016'));
        $this->assertEquals('July 03, 2016', $this->event->getEndDate('  July 03, 2016'));
    }

    /**
     * Given a specific structure determine the ID
     *
     * @return void
     * @SuppressWarnings(PHPMD)
     */
    public function testParserTrackId()
    {
        $this->assertEquals('123', $this->event->parseTrackId('a/a/a/123'));
    }

    /**
     * Most important test, verifies that we can build specific query params.
     *
     * @return void
     * @SuppressWarnings(PHPMD)
     */
    public function testUrlNational()
    {
        $this->assertEquals(
            $this->url.'?section_id=228&year=UPCOMING&past_only=0&page=1&category=NATIONAL',
            $this->event->buildUrl('National', 1, 'Upcoming')
        );
    }

    /**
     * [testUrlNationalPast description]
     * @return [type] [description]
     * @SuppressWarnings(PHPMD)
     */
    public function testUrlNationalPast()
    {
        $this->assertEquals(
            $this->url.'?section_id=228&year=2005&past_only=1&page=1&category=NATIONAL',
            $this->event->buildUrl('National', 1, 2005)
        );
    }

    /**
     * [testUrlNationalPast description]
     * @return [type] [description]
     * @SuppressWarnings(PHPMD)
     */
    public function testUrlState()
    {
        $this->assertEquals(
            $this->url.'?section_id=23&year=UPCOMING&past_only=0&page=1&filter_state=1',
            $this->event->buildUrl('State', 1, 'Upcoming')
        );
    }

    /**
     * [testUrlNationalPast description]
     * @return [type] [description]
     * @SuppressWarnings(PHPMD)
     */
    public function testUrlEarnedDouble()
    {
        $this->assertEquals(
            $this->url.'?section_id=95&year=UPCOMING&past_only=0&page=1&series_race_type=Earned+Double',
            $this->event->buildUrl('Earned Double', 1, 'Upcoming')
        );
    }

    /**
     * [testUrlNationalPast description]
     * @return [type] [description]
     * @SuppressWarnings(PHPMD)
     */
    public function testUrlGoldCup()
    {
        $this->assertEquals(
            $this->url.'?section_id=24&year=UPCOMING&past_only=0&page=1&goldcup=1',
            $this->event->buildUrl('Gold Cup', 1, 'Upcoming')
        );
    }

    /**
     * [testUrlNationalPast description]
     * @return [type] [description]
     * @SuppressWarnings(PHPMD)
     */
    public function testUrlRaceForLife()
    {
        $this->assertEquals(
            $this->url.'?section_id=19&year=UPCOMING&past_only=0&page=1&series_race_type=Race+for+Life',
            $this->event->buildUrl('Race for Life', 1, 'Upcoming')
        );
    }

    /**
     * [testUrlNationalPast description]
     * @return [type] [description]
     * @SuppressWarnings(PHPMD)
     */
    public function testEventUrl()
    {
        $this->assertEquals($this->url.'', $this->event->eventUrl());
    }

    public function testEventId()
    {
        $eventId = $this->event->parseEventIdFromUrl('https://www.usabmx.com/site/bmx_races/325850?section_id=228');
        $this->assertEquals(325850, $eventId);
    }
}
