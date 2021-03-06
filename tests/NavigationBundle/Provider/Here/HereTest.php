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

    protected function checkCredentials(): void
    {
        if (!isset($_ENV['HERE_APP_ID'], $_ENV['HERE_APP_CODE'])) {
            $this->markTestSkipped('You need to configure the HERE_APP_ID and HERE_APP_CODE value in phpunit.xml');
        }
    }

    public function setUp(): void
    {
        $this->here = new Here(new Client(), 'app-id', 'app-code', true);
    }

    public function testGetName(): void
    {
        $this->checkCredentials();

        $this->assertSame('here', $this->here->getName());
    }

    public function testGetAppId(): void
    {
        $this->checkCredentials();

        $this->assertSame('app-id', $this->here->getAppId());
    }

    public function testGetAppCode(): void
    {
        $this->checkCredentials();

        $this->assertSame('app-code', $this->here->getAppCode());
    }

    public function testIsCitEnabled(): void
    {
        $this->checkCredentials();

        $this->assertTrue($this->here->isCitEnabled());
    }

    public function testGetCredentials(): void
    {
        $this->checkCredentials();

        $this->assertSame([
            'app_id' => 'app-id',
            'app_code' => 'app-code',
        ], $this->here->getCredentials());
    }

    public function testCreateDistanceMatrixQuery(): void
    {
        $this->checkCredentials();

        $query = $this->here->createDistanceMatrixQuery();

        $this->assertInstanceOf(DistanceMatrixQueryInterface::class, $query);
    }
}
