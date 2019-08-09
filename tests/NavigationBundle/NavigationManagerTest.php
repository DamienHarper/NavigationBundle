<?php

namespace DH\NavigationBundle\Tests;

use DH\DoctrineAuditBundle\Tests\BaseTest;
use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Exception\ProviderNotRegistered;
use DH\NavigationBundle\Provider\ProviderInterface;

/**
 * @covers \DH\NavigationBundle\Contract\DistanceMatrix\AbstractDistanceMatrixQuery
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\AddProvidersPass
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass
 * @covers \DH\NavigationBundle\DependencyInjection\Configuration
 * @covers \DH\NavigationBundle\DependencyInjection\DHNavigationExtension
 * @covers \DH\NavigationBundle\DHNavigationBundle
 * @covers \DH\NavigationBundle\Exception\ProviderNotRegistered
 * @covers \DH\NavigationBundle\NavigationManager
 * @covers \DH\NavigationBundle\Provider\AbstractFactory
 * @covers \DH\NavigationBundle\Provider\Here\Here
 * @covers \DH\NavigationBundle\Provider\Here\HereFactory
 * @covers \DH\NavigationBundle\Provider\ProviderAggregator
 */
class NavigationManagerTest extends BaseTest
{
    public function testGetProviders(): void
    {
        $providers = $this->manager->getProviders();

        $this->assertCount(1, $providers);
    }

    /**
     * @depends testGetProviders
     */
    public function testGetProvider(): void
    {
        $provider = $this->manager->getProvider();

        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }

    /**
     * @depends testGetProviders
     */
    public function testGetDefaultProvider(): void
    {
        $firstProvider = $this->manager->getProvider();
        $this->manager->using('here');
        $provider = $this->manager->getProvider();

        $this->assertSame($firstProvider, $provider);
    }

    /**
     * @depends testGetProviders
     */
    public function testGetKnownProvider(): void
    {
        $provider = $this->manager->getProvider('here');

        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }

    /**
     * @depends testGetProviders
     */
    public function testGetUnknownProvider(): void
    {
        $this->expectException(ProviderNotRegistered::class);

        $provider = $this->manager->getProvider('yo');
    }

    /**
     * @depends testGetKnownProvider
     */
    public function testUsingKnownProvider(): void
    {
        $hereProvider = $this->manager->getProvider('here');
        $this->manager->using('here');
        $provider = $this->manager->getProvider();

        $this->assertSame($hereProvider, $provider);
    }

    /**
     * @depends testGetKnownProvider
     */
    public function testUsingUnknownProvider(): void
    {
        $this->expectException(ProviderNotRegistered::class);

        $this->manager->using('yo');
    }

    /**
     * @depends testGetKnownProvider
     */
    public function testCreateDistanceMatrixQuery(): void
    {
        $query = $this->manager
            ->using('here')
            ->createDistanceMatrixQuery()
        ;

        $this->assertInstanceOf(DistanceMatrixQueryInterface::class, $query);
    }
}
