<?php

namespace DH\NavigationBundle\Provider;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An abstract factory that makes it easier to implement new factories. A class that extend the AbstractFactory
 * should override AbstractFactory::configureOptionResolver().
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class AbstractFactory implements ProviderFactoryInterface
{
    /**
     * @param array $config
     *
     * @return ProviderInterface
     */
    abstract protected function getProvider(array $config): ProviderInterface;

    /**
     * {@inheritdoc}
     */
    public function createProvider(array $options = []): ProviderInterface
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        $config = $resolver->resolve($options);

        return $this->getProvider($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function validate(array $options, $providerName): void
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);

        try {
            $resolver->resolve($options);
        } catch (\Exception $e) {
            $message = sprintf(
                'Error while configure provider "%s". Verify your configuration. %s',
                $providerName,
                $e->getMessage()
            );

            throw new InvalidConfigurationException($message, $e->getCode(), $e);
        }
    }

    /**
     * By default we do not have any options to configure.
     * A factory should override this function and configure the options resolver.
     *
     * @param OptionsResolver $resolver
     */
    protected static function configureOptionResolver(OptionsResolver $resolver): void
    {
    }
}
