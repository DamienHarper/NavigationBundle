<?php

namespace DH\NavigationBundle\Tests\Provider\Dummy;

use DH\NavigationBundle\Provider\AbstractFactory;
use DH\NavigationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DummyFactory extends AbstractFactory
{
    protected function getProvider(array $config): ProviderInterface
    {
        $client = $config['http_client'] ?: new Client();

        return new Dummy($client);
    }

    protected static function configureOptionResolver(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'http_client' => null,
        ]);

        $resolver->setAllowedTypes('http_client', ['object', 'null']);
    }
}
