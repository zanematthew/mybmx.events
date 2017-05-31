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

    public function testPageRangeSinglePage()
    {
        $this->assertArraySubset([1,1], $this->mock->parsedPageRange(1));
    }

    public function testPageRange()
    {
        $this->assertArraySubset([5,10], $this->mock->parsedPageRange('5-10'));
    }
}
