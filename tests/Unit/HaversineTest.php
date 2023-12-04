<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utilities\Haversine;

class HaversineTest extends TestCase
{
    private float $latitude = 36.12;
    private float $longitude = -86.67;

    public function testGetLatitude(): void
    {
        $haversine = new Haversine($this->latitude, $this->longitude);
        $this->assertEquals($this->latitude, $haversine->getLatitude());
    }

    public function testGetLongitude(): void
    {
        $haversine = new Haversine($this->latitude, $this->longitude);
        $this->assertEquals($this->longitude, $haversine->getLongitude());
    }

    /**
     * Test a distance calculation between two points
     *
     * We are using a points where we know the distance is 2886.44 km
     *
     * @depends testGetLatitude
     * @depends testGetLongitude
     */
    public function testGetDistance(): void
    {
        $haversine = new Haversine($this->latitude, $this->longitude);
        $other = new Haversine(33.94, -118.40);

        $this->assertEquals(2886.44, $haversine->getDistance($other));
    }
}
