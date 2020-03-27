<?php

namespace DH\NavigationBundle;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Contract\Routing\RoutingQueryInterface;
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
     * @return NavigationManager
     */
    public function using(string $name): self
    {
        $this->providerAggregator->using($name);

        return $this;
    }

    /**
     * @throws UnsupportedFeatureException
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
     * @throws UnsupportedFeatureException
     */
    public function createRoutingQuery(): RoutingQueryInterface
    {
        $provider = $this->providerAggregator->getProvider();

        if (method_exists($provider, 'createRoutingQuery')) {
            return $provider->createRoutingQuery();
        }

        throw new UnsupportedFeatureException(sprintf('Routing is not supported by "%s" provider.', $provider->getName()));
    }

    public function getProvider(?string $name = null): ProviderInterface
    {
        return $this->providerAggregator->getProvider($name);
    }

    public function getProviders(): array
    {
        return $this->providerAggregator->getProviders();
    }

    public function getProviderAggregator(): ProviderAggregator
    {
        return $this->providerAggregator;
    }
}
