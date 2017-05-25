<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ShovelTrait;

class ShovelTraitTest extends TestCase
{
    use ShovelTrait;

    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->getMockForTrait(ShovelTrait::class);
    }

    public function testValidNationalUrl()
    {
        $this->assertEquals(
            'https://www.usabmx.com/site/bmx_races?section_id=228&year=UPCOMING&past_only=0&page=1&category=NATIONAL',
            $this->mock->nationalUrl()
        );
    }

    public function testInvalidYearNationalUrl()
    {
        $this->assertEmpty($this->mock->nationalUrl(1979));
    }

    public function testValidStateUrl()
    {
        $this->assertEquals(
            'https://www.usabmx.com/site/bmx_races?section_id=23&year=UPCOMING&past_only=0&page=1&filter_state=1',
            $this->mock->stateUrl()
        );
    }

    public function testValidEarnedDoubleUrl()
    {
        $this->assertEquals(
            'https://www.usabmx.com/site/bmx_races?section_id=95&year=UPCOMING&past_only=0&page=1&series_race_type=Earned+Double',
            $this->mock->earnedDoubleUrl()
        );
    }

    public function testValidRaceForLifeUrl()
    {
        $this->assertEquals(
            'https://www.usabmx.com/site/bmx_races?section_id=19&year=UPCOMING&past_only=0&page=1&series_race_type=Race+for+Life',
            $this->mock->raceForLifeUrl()
        );
    }

    public function testValidGoldCupUrl()
    {
        $this->assertEquals(
            'https://www.usabmx.com/site/bmx_races?section_id=24&year=UPCOMING&past_only=0&page=1&goldcup=1',
            $this->mock->goldCupUrl()
        );
    }

    public function testIsYearValid()
    {
        $this->assertTrue($this->mock->isYearValid('Upcoming'));
    }

    public function testIsStateValid()
    {
        $this->assertTrue($this->mock->isStateValid('md'));
    }

    public function testFalseIsStateValid()
    {
        $this->assertFalse($this->mock->isStateValid('DC'));
    }

    public function testFalseIsYearValid()
    {
        $this->assertFalse($this->mock->isYearValid(2000));
    }
}
