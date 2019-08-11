<?php

namespace DH\NavigationBundle\Provider\Here;

use DH\NavigationBundle\Provider\AbstractFactory;
use DH\NavigationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class HereFactory extends AbstractFactory
{
    protected function getProvider(array $config): ProviderInterface
    {
        $client = $config['http_client'] ?: new Client();

        return new Here($client, $config['app_id'], $config['app_code'], $config['use_cit']);
    }

    protected static function configureOptionResolver(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'http_client' => null,
            'app_id' => null,
            'app_code' => null,
            'use_cit' => false,
        ]);

        $resolver->setAllowedTypes('http_client', ['object', 'null']);
        $resolver->setAllowedTypes('app_id', ['string', 'null']);
        $resolver->setAllowedTypes('app_code', ['string', 'null']);
        $resolver->setRequired(['app_id', 'app_code']);
    }
}
