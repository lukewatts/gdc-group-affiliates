<?php

namespace Tests\Unit;

use App\Utilities\Affiliates;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class AffiliatesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        // Setup mock affiliates file
        Storage::fake('affiliates')->put(
            'affiliates.txt',
            file_get_contents(__DIR__ . '/mocks/affiliates.txt')
        );
    }

    public function testFakeAffiliatesFileExists(): void
    {
        $this->assertTrue(Storage::disk('affiliates')->exists('affiliates.txt'));
    }

    /**
     * @depends testFakeAffiliatesFileExists
     */
    public function testIsValidReturnsTrueWhenPathAndDiskExist(): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $this->assertTrue($affiliates->isValid());
    }

    /**
     * @depends testFakeAffiliatesFileExists
     */
    public function testIsValidReturnsFalseWhenPathDoesNotExist(): void
    {
        $affiliates = new Affiliates('does-not-exist.txt', 'affiliates');
        $this->assertFalse($affiliates->isValid());
    }

    /**
     * @depends testIsValidReturnsTrueWhenPathAndDiskExist
     */
    public function testGetErrorIsNullWhenPathAndDiskExist(): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $affiliates->isValid();

        $this->assertNull($affiliates->getError());
    }

    /**
     * @depends testIsValidReturnsFalseWhenPathDoesNotExist
     */
    public function testGetErrorIsNotNullWhenPathDoesNotExist(): void
    {
        $affiliates = new Affiliates('does-not-exist.txt', 'affiliates');
        $affiliates->isValid();

        $this->assertNotNull($affiliates->getError());
    }

    /**
     * @depends testIsValidReturnsTrueWhenPathAndDiskExist
     */
    public function testToArrayReturnsArrayContainingObjects(): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $this->assertIsArray($affiliates->toArray());
        $this->assertGreaterThan(0, count($affiliates->toArray()));
        $this->assertContainsOnly('object', $affiliates->toArray());
    }

    /**
     * @depends testToArrayReturnsArrayContainingObjects
     */
    public function testToArrayReturnsArrayContainingStdClassObjects(): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $this->assertContainsOnlyInstancesOf('\stdClass', $affiliates->toArray());
    }

    /**
     * @depends testToArrayReturnsArrayContainingStdClassObjects
     */
    public function testToArrayReturnsArrayContainingObjectsWithRequiredProperties(): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $originalCount = count($affiliates->toArray());

        // filter out objects that do not have the required properties
        $affiliates = array_filter($affiliates->toArray(), function ($affiliate) {
            return property_exists($affiliate, 'affiliate_id')
                && property_exists($affiliate, 'name')
                && property_exists($affiliate, 'latitude')
                && property_exists($affiliate, 'longitude');
        });

        $this->assertEquals($originalCount, count($affiliates));
    }

    /**
     * @depends testToArrayReturnsArrayContainingObjectsWithRequiredProperties
     */
    public function testWithinDistanceReturnsCorrectCount(): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $affiliates = $affiliates->withinDistance(
            100);

        $this->assertIsArray($affiliates);
        $this->assertContainsOnly('object', $affiliates);
        $this->assertContainsOnlyInstancesOf('\stdClass', $affiliates);
        $this->assertCount(16, $affiliates);
    }

    public static function provideAffiliateIdAndIndex(): array
    {
        // $affiliate_id, $index
        return [
            [4, 0],
            [5, 1],
            [6, 2],
            [8, 3],
            [11, 4],
            [12, 5],
            [13, 6],
            [15, 7],
            [17, 8],
            [23, 9],
            [24, 10],
            [26, 11],
            [29, 12],
            [30, 13],
            [31, 14],
            [39, 15],
        ];
    }

    /**
     * @depends testWithinDistanceReturnsCorrectCount
     * @dataProvider provideAffiliateIdAndIndex
     */
    public function testWithinDistanceReturnsCorrectIdsInCorrectOrder($affiliate_id, $index): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $affiliates = $affiliates->withinDistance(
            100
        );

        $this->assertEquals($affiliate_id, $affiliates[$index]->affiliate_id);
    }

    public static function dataProviderForWithinDistanceReturnsCorrectDistancesInCorrectOrder(): array
    {
        // $distance, $index
        return [
            [10.57, 0],
            [23.29, 1],
            [24.09, 2],
            [83.53, 3],
            [38.14, 4],
            [41.77, 5],
            [62.23, 6],
            [43.72, 7],
            [96.08, 8],
            [82.69, 9],
            [89.03, 10],
            [98.87, 11],
            [72.2, 12],
            [82.64, 13],
            [44.29, 14],
            [38.36, 15],
        ];
    }

    /**
     * @depends testWithinDistanceReturnsCorrectIdsInCorrectOrder
     * @dataProvider dataProviderForWithinDistanceReturnsCorrectDistancesInCorrectOrder
     */
    public function testWithinDistanceReturnsCorrectDistancesInCorrectOrder($distance, $index): void
    {
        $affiliates = new Affiliates('affiliates.txt', 'affiliates');
        $affiliates = $affiliates->withinDistance(
            100
        );

        $this->assertEquals($distance, $affiliates[$index]->distance);
    }
}
