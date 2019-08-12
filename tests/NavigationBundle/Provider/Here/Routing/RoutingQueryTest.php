<?php

namespace DH\NavigationBundle\Tests\Provider\Here\Routing;

use DH\DoctrineAuditBundle\Tests\BaseTest;
use DH\NavigationBundle\Contract\Routing\RoutingResponseInterface;
use DH\NavigationBundle\Exception\WaypointException;

/**
 * @covers \DH\NavigationBundle\Contract\Routing\AbstractRoutingQuery
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\AddProvidersPass
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass
 * @covers \DH\NavigationBundle\DependencyInjection\Configuration
 * @covers \DH\NavigationBundle\DependencyInjection\DHNavigationExtension
 * @covers \DH\NavigationBundle\DHNavigationBundle
 * @covers \DH\NavigationBundle\Exception\ProviderNotRegistered
 * @covers \DH\NavigationBundle\Helper\FormatHelper
 * @covers \DH\NavigationBundle\NavigationManager
 * @covers \DH\NavigationBundle\Provider\AbstractFactory
 * @covers \DH\NavigationBundle\Provider\AbstractProvider
 * @covers \DH\NavigationBundle\Provider\GoogleMaps\GoogleMaps
 * @covers \DH\NavigationBundle\Provider\GoogleMaps\GoogleMapsFactory
 * @covers \DH\NavigationBundle\Provider\Here\Here
 * @covers \DH\NavigationBundle\Provider\Here\HereFactory
 * @covers \DH\NavigationBundle\Provider\Here\Routing\RoutingQuery
 * @covers \DH\NavigationBundle\Provider\Here\Routing\RoutingResponse
 * @covers \DH\NavigationBundle\Provider\ProviderAggregator
 */
class RoutingQueryTest extends BaseTest
{
    protected function checkCredentials(): void
    {
        if (!isset($_ENV['HERE_APP_ID'], $_ENV['HERE_APP_CODE'])) {
            $this->markTestSkipped('You need to configure the HERE_APP_ID and HERE_APP_CODE value in phpunit.xml');
        }
    }

    public function testExecuteWithoutWaypoint(): void
    {
        $this->checkCredentials();

        $this->expectException(WaypointException::class);

        $query = $this->manager
            ->using('here')
            ->createRoutingQuery()
        ;
        $response = $query->execute();
    }

    /**
     * @depends testExecuteWithoutWaypoint
     */
    public function testExecuteWithOneWaypoint(): void
    {
        $this->checkCredentials();

        $this->expectException(WaypointException::class);

        $query = $this->manager
            ->using('here')
            ->createRoutingQuery()
        ;
        $response = $query
            ->addWaypoint('45.834278,1.260816')
            ->execute()
        ;
    }

    /**
     * @depends testExecuteWithOneWaypoint
     */
    public function testExecute(): void
    {
        $this->checkCredentials();

        $query = $this->manager
            ->using('here')
            ->createRoutingQuery()
        ;
        $response = $query
            ->addWaypoint('45.834278,1.260816')
            ->addWaypoint('44.830109,-0.603649')
            ->execute()
        ;

        $this->assertInstanceOf(RoutingResponseInterface::class, $response);
    }
}
