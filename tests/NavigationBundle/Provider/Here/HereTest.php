<?php

namespace DH\NavigationBundle\Tests\Provider\Here;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Provider\Here\Here;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery
 * @covers \DH\NavigationBundle\Provider\AbstractProvider
 * @covers \DH\NavigationBundle\Provider\Here\Here
 */
class HereTest extends TestCase
{
    /**
     * @var Here
     */
    private $here;

    public function setUp(): void
    {
        $this->here = new Here(new Client(), 'app-id', 'app-code', true);
    }

    public function testGetName(): void
    {
        $this->assertSame('here', $this->here->getName());
    }

    public function testGetAppId(): void
    {
        $this->assertSame('app-id', $this->here->getAppId());
    }

    public function testGetAppCode(): void
    {
        $this->assertSame('app-code', $this->here->getAppCode());
    }

    public function testIsCitEnabled(): void
    {
        $this->assertTrue($this->here->isCitEnabled());
    }

    public function testGetCredentials(): void
    {
        $this->assertSame([
            'app_id' => 'app-id',
            'app_code' => 'app-code',
        ], $this->here->getCredentials());
    }

    public function testCreateDistanceMatrixQuery(): void
    {
        $query = $this->here->createDistanceMatrixQuery();

        $this->assertInstanceOf(DistanceMatrixQueryInterface::class, $query);
    }
}
