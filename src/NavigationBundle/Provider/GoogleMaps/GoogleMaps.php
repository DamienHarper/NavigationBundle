<?php

namespace DH\NavigationBundle\Provider\GoogleMaps;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Provider\AbstractProvider;
use DH\NavigationBundle\Provider\GoogleMaps\DistanceMatrix\DistanceMatrixQuery;
use GuzzleHttp\ClientInterface;

class GoogleMaps extends AbstractProvider
{
    /**
     * @var string
     */
    private $api_key;

    /**
     * @var string|null
     */
    private $region;

    /**
     * Here constructor.
     *
     * @param string      $apiKey an Api key
     * @param string|null $region region
     */
    public function __construct(ClientInterface $client, string $apiKey, ?string $region = null)
    {
        parent::__construct($client);

        $this->api_key = $apiKey;
        $this->region = $region;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'google_maps';
    }

    public function getApiKey(): string
    {
        return $this->api_key;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): array
    {
        return [
            'key' => $this->getApiKey(),
        ];
    }

    public function createDistanceMatrixQuery(): DistanceMatrixQueryInterface
    {
        return new DistanceMatrixQuery($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
