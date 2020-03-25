<?php

namespace DH\NavigationBundle\Provider;

use GuzzleHttp\ClientInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Returns the HTTP adapter.
     *
     * {@inheritdoc}
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
