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
        $config = $this->setupFromEnvVars($config);

        $extension = new DHNavigationExtension();
        $extension->load($config, $container);

        $container->compile();

        $this->manager = $container->get('dh_navigation.manager');

        foreach ($this->manager->getProviders() as $provider) {
            $provider->getClient()->setProvider($provider);
        }
    }

    private function setupFromEnvVars(array $array): array
    {
        $output = [];
        foreach ($array as $key => $value) {
            if (!\is_array($value)) {
                foreach ($_ENV as $k => $v) {
                    $value = str_replace('%env('.$k.')%', $v, $value);
                }
                $output[$key] = $value;
            } else {
                $output[$key] = $this->setupFromEnvVars($value);
            }
        }

        return $output;
    }
}
