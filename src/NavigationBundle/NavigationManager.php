<?php

namespace DH\NavigationBundle;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Exception\UnsupportedFeatureException;
use DH\NavigationBundle\Provider\ProviderAggregator;
use DH\NavigationBundle\Provider\ProviderInterface;

class NavigationManager
{
    /**
     * @var ProviderAggregator
     */
    private $providerAggregator;

    public function __construct(ProviderAggregator $providerAggregator)
    {
        $this->providerAggregator = $providerAggregator;
    }

    /**
     * Sets the default provider to use.
     *
     * @param string $name
     *
     * @return NavigationManager
     */
    public function using(string $name): self
    {
        $this->providerAggregator->using($name);

        return $this;
    }

    /**
     * @throws UnsupportedFeatureException
     *
     * @return DistanceMatrixQueryInterface
     */
    public function createDistanceMatrixQuery(): DistanceMatrixQueryInterface
    {
        $provider = $this->providerAggregator->getProvider();

        if (method_exists($provider, 'createDistanceMatrixQuery')) {
            return $provider->createDistanceMatrixQuery();
        }

        throw new UnsupportedFeatureException(sprintf('Distance Matrix is not supported by "%s" provider.', $provider->getName()));
    }

    /**
     * @param null|string $name
     *
     * @return ProviderInterface
     */
    public function getProvider(?string $name = null): ProviderInterface
    {
        return $this->providerAggregator->getProvider($name);
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->providerAggregator->getProviders();
    }
}
