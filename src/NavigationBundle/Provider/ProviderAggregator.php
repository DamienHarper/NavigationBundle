<?php

namespace DH\NavigationBundle\Provider;

use DH\NavigationBundle\Exception\ProviderNotRegistered;

class ProviderAggregator
{
    /**
     * @var ProviderInterface[]
     */
    private $providers = [];

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * Registers a new provider to the aggregator.
     *
     * @return ProviderAggregator
     */
    public function registerProvider(ProviderInterface $provider): self
    {
        $this->providers[$provider->getName()] = $provider;

        return $this;
    }

    /**
     * Registers a set of providers.
     *
     * @param ProviderInterface[] $providers
     *
     * @return ProviderAggregator
     */
    public function registerProviders(array $providers = []): self
    {
        foreach ($providers as $provider) {
            $this->registerProvider($provider);
        }

        return $this;
    }

    /**
     * Sets the default provider to use.
     *
     * @return ProviderAggregator
     */
    public function using(string $name): self
    {
        if (!isset($this->providers[$name])) {
            throw ProviderNotRegistered::create($name ?? '', $this->providers);
        }

        $this->provider = $this->providers[$name];

        return $this;
    }

    /**
     * Returns all registered providers indexed by their name.
     *
     * @return ProviderInterface[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    public function getProvider(?string $name = null): ProviderInterface
    {
        if (0 === \count($this->providers)) {
            throw ProviderNotRegistered::noProviderRegistered();
        }

        if (null === $name) {
            if (null === $this->provider) {
                $key = key($this->providers);
                $this->provider = $this->providers[$key];
            }

            return $this->provider;
        }

        if (!isset($this->providers[$name])) {
            throw ProviderNotRegistered::create($name);
        }

        return $this->providers[$name];
    }
}
