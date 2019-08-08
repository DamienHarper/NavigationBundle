<?php

namespace DH\NavigationBundle;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Exception\UnsupportedFeatureException;
use DH\NavigationBundle\Provider\ProviderAggregator;

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

    public function createDistanceMatrixQuery(): DistanceMatrixQueryInterface
    {
        $provider = $this->providerAggregator->getProvider();

        if (method_exists($provider, 'createDistanceMatrixQuery')) {
            return $provider->createDistanceMatrixQuery();
        }

        throw new UnsupportedFeatureException(sprintf('Distance Matrix is not supported by "%s" provider.', $provider->getName()));
    }
}
