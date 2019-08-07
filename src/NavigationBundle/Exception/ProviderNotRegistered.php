<?php

namespace DH\NavigationBundle\Exception;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
final class ProviderNotRegistered extends \RuntimeException
{
    /**
     * @param string $providerName
     * @param array $registeredProviders
     *
     * @return ProviderNotRegistered
     */
    public static function create(string $providerName, array $registeredProviders = []): ProviderNotRegistered
    {
        return new self(sprintf(
            'Provider "%s" is not registered, so you cannot use it. Did you forget to register it or made a typo?%s',
            $providerName,
            0 === \count($registeredProviders) ? '' : sprintf(' Registered providers are: %s.', implode(', ', $registeredProviders))
        ));
    }

    /**
     * @return ProviderNotRegistered
     */
    public static function noProviderRegistered(): ProviderNotRegistered
    {
        return new self('No provider registered.');
    }
}
