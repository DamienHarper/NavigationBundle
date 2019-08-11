<?php

namespace DH\NavigationBundle\Provider;

use GuzzleHttp\ClientInterface;

interface ProviderInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * @return array
     */
    public function getCredentials(): array;
}
