<?php

namespace DH\NavigationBundle\Tests\Provider\Here\DistanceMatrix;

use DH\DoctrineAuditBundle\Tests\BaseTest;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixResponseInterface;
use DH\NavigationBundle\Exception\DestinationException;
use DH\NavigationBundle\Exception\OriginException;

/**
 * @covers \DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\AddProvidersPass
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass
 * @covers \DH\NavigationBundle\DependencyInjection\Configuration
 * @covers \DH\NavigationBundle\DependencyInjection\DHNavigationExtension
 * @covers \DH\NavigationBundle\DHNavigationBundle
 * @covers \DH\NavigationBundle\Exception\ProviderNotRegistered
 * @covers \DH\NavigationBundle\Helper\FormatHelper
 * @covers \DH\NavigationBundle\Model\Address
 * @covers \DH\NavigationBundle\Model\Distance
 * @covers \DH\NavigationBundle\Model\Duration
 * @covers \DH\NavigationBundle\Model\Element
 * @covers \DH\NavigationBundle\Model\Row
 * @covers \DH\NavigationBundle\NavigationManager
 * @covers \DH\NavigationBundle\Provider\AbstractFactory
 * @covers \DH\NavigationBundle\Provider\AbstractProvider
 * @covers \DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixQuery
 * @covers \DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixResponse
 * @covers \DH\NavigationBundle\Provider\Here\Here
 * @covers \DH\NavigationBundle\Provider\Here\HereFactory
 * @covers \DH\NavigationBundle\Provider\ProviderAggregator
 */
class DistanceMatrixQueryTest extends BaseTest
{
    public function testExecuteWithoutOrigin(): void
    {
        $this->expectException(OriginException::class);

        $query = $this->manager->createDistanceMatrixQuery();
        $response = $query->execute();
    }

    /**
     * @depends testExecuteWithoutOrigin
     */
    public function testExecuteWithoutDestination(): void
    {
        $this->expectException(DestinationException::class);

        $query = $this->manager->createDistanceMatrixQuery();
        $response = $query
            ->addOrigin('45.834278,1.260816')
            ->execute()
        ;
    }

    /**
     * @depends testExecuteWithoutDestination
     */
    public function testExecute(): void
    {
        $query = $this->manager->createDistanceMatrixQuery();
        $response = $query
            ->addOrigin('45.834278,1.260816')
            ->addDestination('44.830109,-0.603649')
            ->execute()
        ;

        $this->assertInstanceOf(DistanceMatrixResponseInterface::class, $response);
    }
}
