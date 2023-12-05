<?php

namespace Tests\Unit;

use App\Utilities\Haversine;
use Tests\TestCase;

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

    /**
     * @depends testGetDistance
     */
    public function testGetLatLonAtDistanceAndBearingReturnsCorrectStructure(): void
    {
        $haversine = new Haversine(53.3340285, -6.2535495);

        $resultPoint = $haversine->getLatLonAtDistanceAndBearing(distance: 100, bearing: 180);

        $this->assertIsObject($resultPoint);
        $this->assertObjectHasProperty('latitude', $resultPoint);
        $this->assertObjectHasProperty('longitude', $resultPoint);
    }

    public static function provideDistanceBearingAndExpectedPoint(): array
    {
        // Distance, Bearing, Expected Lat/Lon
        return [
            [100, 180, (object) [
                'latitude' => 52.434706894081,
                'longitude' => -6.2535495,
            ]],
            [100, 0, (object) [
                'latitude' => 54.233350105919,
                'longitude' => -6.2535495,
            ]],
            [100, 90, (object) [
                'latitude' => 53.3245490608,
                'longitude' => -4.747746763273,
            ]],
            [100, 270, (object) [
                'latitude' => 53.3245490608,
                'longitude' => -7.759352236727,
            ]],
        ];
    }

    /**
     * @depends testGetLatLonAtDistanceAndBearingReturnsCorrectStructure
     * @dataProvider provideDistanceBearingAndExpectedPoint
     */
    public function testGetLatLonAtDistanceAndBearingReturnsLatLonAtCorrectDistance(int $distance, int $bearing, $expectedPoint): void
    {
        $haversine = new Haversine(53.3340285, -6.2535495);

        // If the formula is correct, this should be the returned latitude and longitude point
        $otherHaversine = new Haversine($expectedPoint->latitude, $expectedPoint->longitude);

        $resultPoint = $haversine->getLatLonAtDistanceAndBearing($distance, $bearing);

        $this->assertEquals($expectedPoint->latitude, $resultPoint->latitude);
        $this->assertEquals($expectedPoint->longitude, $resultPoint->longitude);

        $this->assertEquals(100, $haversine->getDistance($otherHaversine));
    }
}
