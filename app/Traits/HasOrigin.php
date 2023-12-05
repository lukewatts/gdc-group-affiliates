<?php

namespace App\Traits;

trait HasOrigin
{
    protected float $originLatitude = 53.339428;
    protected float $originLongitude =  -6.257664;

    public function setOrigin(float $originLatitude, float $originLongitude): void
    {
        $this->originLatitude = $originLatitude;
        $this->originLongitude = $originLongitude;
    }

    public function getOrigin(): object
    {
        return (object) [
            'latitude' => $this->originLatitude,
            'longitude' => $this->originLongitude
        ];
    }
}
