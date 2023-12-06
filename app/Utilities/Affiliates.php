<?php

namespace App\Utilities;

use App\Traits\HasOrigin;
use Illuminate\Support\Facades\Storage;

class Affiliates
{
    use HasOrigin;

    const SORT_BY_AFFILIATE_ID = 'affiliate_id';
    const SORT_BY_DISTANCE = 'distance';
    const SOR_BY_OPTIONS = [
        self::SORT_BY_AFFILIATE_ID,
        self::SORT_BY_DISTANCE,
    ];

    /**
     * @var array $affiliates
     */
    protected array $affiliates = [];

    /**
     * @var string|null $error
     */
    protected ?string $error;

    public function __construct(
        protected string $path,
        protected string $disk = 'affiliates'
    ) {
        $this->error = null;
    }

    /**
     * Get Validation Error
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Validate affiliates file
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (!Storage::disk($this->disk)->exists($this->path)) {
            $this->error = 'Affiliates file does not exist.';
        }

        if (!Storage::disk($this->disk)->get($this->path)) {
            $this->error = 'Affiliates file is not readable.';
        }

        // Returns true if no validation errors, false otherwise
        return !$this->error;
    }

    /**
     * Get affiliates array from affiliates file
     *
     * @return array
     */
    public function toArray(): array
    {
        $affiliatesFileContents = Storage::disk($this->disk)->get($this->path);

        $affiliatesFileContents = str_replace("\r\n", "\n", $affiliatesFileContents);
        $affiliates = explode("\n", $affiliatesFileContents);
        $affiliates = (array) array_filter($affiliates);

        $this->affiliates =  array_map(function ($affiliate) {
            $affiliateObject = json_decode($affiliate);

            $affiliateObject->latitude = (float) $affiliateObject->latitude;
            $affiliateObject->longitude = (float) $affiliateObject->longitude;

            return $affiliateObject;
        }, $affiliates);

        return $this->affiliates;
    }

    /**
     * Get affiliates within 100km.
     *
     * @param float $distance Distance in Kilometers
     * @param string $sort_by Attribute to sort by (affiliate_id, distance)
     *
     * @return array
     */
    public function withinDistance(float $distance, string $sort_by = self::SORT_BY_AFFILIATE_ID): array
    {
        $affiliates = $this->toArray();
        $originHaversine = new Haversine($this->originLatitude, $this->originLongitude);
        $affiliatesWithinDistance = [];
        foreach ($affiliates as $affiliate) {
            $affiliateHaversine = new Haversine($affiliate->latitude, $affiliate->longitude);
            $affiliate->distance = $originHaversine->getDistance($affiliateHaversine);

            if ($affiliate->distance <= $distance) {
                $affiliatesWithinDistance[] = $affiliate;
            }
        }

        // Ensure sort_by is a valid option
        if (!in_array($sort_by, self::SOR_BY_OPTIONS)) {
            $sort_by = self::SORT_BY_AFFILIATE_ID;
        }

        // Sort by attribute (affiliate_id || distance)...
        // https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.spaceship-op
        usort($affiliatesWithinDistance, function ($a, $b) use ($sort_by) {
            if (!is_numeric($a->$sort_by) || !is_numeric($b->$sort_by)) {
                throw new \InvalidArgumentException('Unhandled attribute value type. Value in attribute must be a number to sort.');
            }

            return $a->$sort_by <=> $b->$sort_by;
        });

        return $affiliatesWithinDistance;
    }
}
