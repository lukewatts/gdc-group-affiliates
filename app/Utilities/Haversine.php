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
     * Get Distance between two points
     *
     * @param Haversine $other
     *
     * @return float
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

    /**
     * Get Latitude and Longitude at Distance and Bearing
     *
     * @param int $distance
     * @param int $bearing
     *
     * @return object
     */
    public function getLatLonAtDistanceAndBearing(int $distance, int $bearing): object
    {
        $bearing = deg2rad($bearing);

        $latitude = deg2rad($this->latitude);
        $longitude = deg2rad($this->longitude);

        $otherLatitude = asin(
            sin($latitude) * cos($distance / self::EARTH_RADIUS) +
            cos($latitude) * sin($distance / self::EARTH_RADIUS) * cos($bearing)
        );

        $otherLongitude = $longitude + atan2(
            sin($bearing) * sin($distance / self::EARTH_RADIUS) * cos($latitude),
            cos($distance / self::EARTH_RADIUS) - sin($latitude) * sin($otherLatitude)
        );

        return (object) [
            'latitude' => round(rad2deg($otherLatitude), 12),
            'longitude' => round(rad2deg($otherLongitude), 12),
        ];
    }
}
