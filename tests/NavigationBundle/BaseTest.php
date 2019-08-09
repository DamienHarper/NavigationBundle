<?php

namespace DH\DoctrineAuditBundle\Tests;

use DH\NavigationBundle\DependencyInjection\DHNavigationExtension;
use DH\NavigationBundle\DHNavigationBundle;
use DH\NavigationBundle\NavigationManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

abstract class BaseTest extends TestCase
{
    /**
     * @var NavigationManager
     */
    protected $manager;

    public function setUp(): void
    {
        $container = new ContainerBuilder();

        $bundle = new DHNavigationBundle();
        $bundle->build($container);

        $config = Yaml::parse(file_get_contents(__DIR__.'/Fixtures/dh_navigation.yaml'));

        $extension = new DHNavigationExtension();
        $extension->load($config, $container);

        $container->compile();

        $this->manager = $container->get('dh_navigation.manager');
    }
}