<?php

namespace DH\NavigationBundle\DependencyInjection;

use DH\NavigationBundle\DependencyInjection\Compiler\FactoryValidatorPass;
use DH\NavigationBundle\Provider\ProviderFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DHNavigationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $this->loadProviders($container, $config);

        foreach ($config['providers'] as $providerName => $providerConfig) {
            if (isset($providerConfig['options'])) {
                foreach ($providerConfig['options'] as $key => $value) {
                    $container->setParameter('dh_navigation.%s.%s'.$providerName.'.'.$key, $value);
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function loadProviders(ContainerBuilder $container, array $config): void
    {
        foreach ($config['providers'] as $providerName => $providerConfig) {
            $factoryClass = null;

            try {
                $factoryService = $container->getDefinition($providerConfig['factory']);
                $factoryClass = $factoryService->getClass() ?: $providerConfig['factory'];
                if (!$this->implementsProviderFactory($factoryClass)) {
                    throw new \LogicException(sprintf('Provider factory "%s" must implement ProviderFactoryInterface', $providerConfig['factory']));
                }
                // See if any option has a service reference
                $providerConfig['options'] = $this->findReferences($providerConfig['options']);
                $factoryClass::validate($providerConfig['options'], $providerName);
            } catch (ServiceNotFoundException $e) {
                // Assert: We are using a custom factory. If invalid config, it will be caught in FactoryValidatorPass
                $providerConfig['options'] = $this->findReferences($providerConfig['options']);
                FactoryValidatorPass::addFactoryServiceId($providerConfig['factory']);
            }

            if (null !== $factoryClass) {
                $serviceId = 'dh_navigation.provider.'.$providerName;
                $def = $container->register($serviceId, $factoryClass)
                    ->setFactory([new Reference($factoryClass), 'createProvider'])
                    ->addArgument($providerConfig['options']);

                $def->addTag('dh_navigation.provider');
                foreach ($providerConfig['aliases'] as $alias) {
                    $container->setAlias($alias, $serviceId);
                }
            }
        }
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function findReferences(array $options): array
    {
        foreach ($options as $key => $value) {
            if (\is_array($value)) {
                $options[$key] = $this->findReferences($value);
            } elseif ('_service' === substr((string) $key, -8) || 0 === strpos((string) $value, '@') || 'service' === $key) {
                $options[$key] = new Reference(ltrim($value, '@'));
            }
        }

        return $options;
    }

    /**
     * @param string $factoryClass
     *
     * @return bool
     */
    private function implementsProviderFactory(string $factoryClass): bool
    {
        if (false === $interfaces = class_implements($factoryClass)) {
            return false;
        }

        return \in_array(ProviderFactoryInterface::class, $interfaces, true);
    }
}
