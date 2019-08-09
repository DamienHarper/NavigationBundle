<?php

namespace DH\NavigationBundle\Tests;

use DH\NavigationBundle\DependencyInjection\DHNavigationExtension;
use DH\NavigationBundle\DHNavigationBundle;
use DH\NavigationBundle\NavigationManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \DH\NavigationBundle\DependencyInjection\Configuration
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\AddProvidersPass
 * @covers \DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass
 * @covers \DH\NavigationBundle\DependencyInjection\DHNavigationExtension
 * @covers \DH\NavigationBundle\DHNavigationBundle
 * @covers \DH\NavigationBundle\NavigationManager
 * @covers \DH\NavigationBundle\Provider\ProviderAggregator
 */
class DHNavigationBundleTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testDefaultBuild(): void
    {
        $container = new ContainerBuilder();

        $bundle = new DHNavigationBundle();
        $bundle->build($container);

        $extension = new DHNavigationExtension();
        $extension->load([], $container);

        $container->compile();

        $manager = $container->get('dh_navigation.manager');
        $this->assertInstanceOf(NavigationManager::class, $manager);
    }
}
