<?php

namespace DH\NavigationBundle;

use DH\NavigationBundle\DependencyInjection\Compiler\AddProvidersPass;
use DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DHNavigationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddProvidersPass());
        $container->addCompilerPass(new FactoryValidatorPass());
    }
}
