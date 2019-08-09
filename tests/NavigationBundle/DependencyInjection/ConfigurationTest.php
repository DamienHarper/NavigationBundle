<?php

namespace DH\NavigationBundle\Tests\DependencyInjection;

use DH\NavigationBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * @covers \DH\NavigationBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    public function testGetConfigTreeBuilder(): void
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/../Fixtures/dh_navigation.yaml'));

        $configuration = new Configuration(true);
        $treeBuilder = $configuration->getConfigTreeBuilder();
        $processor = new Processor();

        $config = $processor->process($treeBuilder->buildTree(), $config);

        $this->assertNotEmpty($config['providers']['here']['options']['app_id']);
        $this->assertNotEmpty($config['providers']['here']['options']['app_code']);
        $this->assertNotEmpty($config['providers']['here']['options']['use_cit']);
    }
}
