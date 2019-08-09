<?php

namespace DH\NavigationBundle\DependencyInjection\Compiler;

use DH\NavigationBundle\Provider\ProviderAggregator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddProvidersPass implements CompilerPassInterface
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * Get all providers based on their tag (`dh_navigation.provider`) and register them.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ProviderAggregator::class)) {
            return;
        }

        $providers = [];
        foreach ($container->findTaggedServiceIds('dh_navigation.provider') as $providerId => $attributes) {
            $providers[] = new Reference($providerId);
        }

        $definition = $container->getDefinition(ProviderAggregator::class);
        $definition->addMethodCall('registerProviders', [$providers]);
    }
}
