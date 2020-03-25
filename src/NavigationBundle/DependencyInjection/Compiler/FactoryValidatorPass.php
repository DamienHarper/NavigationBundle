<?php

namespace DH\NavigationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Make sure that the factory actually exists.
 */
class FactoryValidatorPass implements CompilerPassInterface
{
    private static $factoryServiceIds = [];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        foreach (self::$factoryServiceIds as $id) {
            if (!$container->hasAlias($id) && !$container->hasDefinition($id)) {
                throw new ServiceNotFoundException(sprintf('Factory with ID "%s" could not be found', $id));
            }
        }
    }

    public static function addFactoryServiceId(string $factoryServiceIds): void
    {
        self::$factoryServiceIds[] = $factoryServiceIds;
    }
}
