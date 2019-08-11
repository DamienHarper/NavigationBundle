<?php

namespace DH\NavigationBundle\Tests\Provider\GoogleMaps;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Provider\GoogleMaps\GoogleMaps;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery
 * @covers \DH\NavigationBundle\Provider\AbstractProvider
 * @covers \DH\NavigationBundle\Provider\GoogleMaps\GoogleMaps
 */
class GoogleMapsTest extends TestCase
{
    /**
     * @var GoogleMaps
     */
    private $googleMaps;

    protected function checkCredentials(): void
    {
        if (!isset($_ENV['HERE_APP_ID'], $_ENV['HERE_APP_CODE'])) {
            $this->markTestSkipped('You need to configure the HERE_APP_ID and HERE_APP_CODE value in phpunit.xml');
        }
    }

    public function setUp(): void
    {
        $this->googleMaps = new GoogleMaps(new Client(), 'api-key');
    }

    public function testGetName(): void
    {
        $this->checkCredentials();

        $this->assertSame('google_maps', $this->googleMaps->getName());
    }

    public function testGetApiKey(): void
    {
        $this->checkCredentials();

        $this->assertSame('key', $this->googleMaps->getApiKey());
    }

    public function testGetCredentials(): void
    {
        $this->checkCredentials();

        $this->assertSame([
            'key' => 'api-key',
        ], $this->googleMaps->getCredentials());
    }

    public function testCreateDistanceMatrixQuery(): void
    {
        $this->checkCredentials();

        $query = $this->googleMaps->createDistanceMatrixQuery();

        $this->assertInstanceOf(DistanceMatrixQueryInterface::class, $query);
    }
}
