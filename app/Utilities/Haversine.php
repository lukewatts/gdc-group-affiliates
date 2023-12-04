<?php

namespace App\Utilities;

class Haversine
{
    const EARTH_RADIUS = 6371;

    public function __construct(
        protected float $latitude,
        protected float $longitude,
    ) {}

    /**
     * Get Latitude
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Get Longitude
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Get Distance
     */
    public function getDistance(Haversine $other): float
    {
        $differenceLatitude = deg2rad($other->getLatitude() - $this->latitude);
        $differenceLongitude = deg2rad($other->getLongitude() - $this->longitude);

        $a = sin($differenceLatitude / 2) * sin($differenceLatitude / 2) +
            cos(deg2rad($this->latitude)) * cos(deg2rad($other->getLatitude())) *
            sin($differenceLongitude / 2) * sin($differenceLongitude / 2);

        $c = 2 * asin(sqrt($a));

        return round($c * self::EARTH_RADIUS, 2);
    }
}
