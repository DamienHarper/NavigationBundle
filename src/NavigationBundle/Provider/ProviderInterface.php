<?php

namespace DH\NavigationBundle\Provider;

use GuzzleHttp\ClientInterface;

interface ProviderInterface
{
    public function getName(): string;

    public function getClient(): ClientInterface;

    public function getCredentials(): array;
}
