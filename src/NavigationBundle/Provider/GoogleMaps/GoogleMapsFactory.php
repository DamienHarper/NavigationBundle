<?php

namespace DH\NavigationBundle\Provider\GoogleMaps;

use DH\NavigationBundle\Provider\AbstractFactory;
use DH\NavigationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GoogleMapsFactory extends AbstractFactory
{
    protected function getProvider(array $config): ProviderInterface
    {
        $client = $config['http_client'] ?: new Client();

        return new GoogleMaps($client, $config['api_key'], $config['region']);
    }

    protected static function configureOptionResolver(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'http_client' => null,
            'region' => null,
            'api_key' => null,
        ]);

        $resolver->setAllowedTypes('http_client', ['object', 'null']);
        $resolver->setAllowedTypes('api_key', ['string', 'null']);
        $resolver->setAllowedTypes('region', ['string', 'null']);
        $resolver->setRequired(['api_key']);
    }
}
