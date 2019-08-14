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
     * @var ?string
     */
    private $region;

    /**
     * Here constructor.
     *
     * @param ClientInterface $client
     * @param string          $apiKey an Api key
     * @param ?string         $region region
     */
    public function __construct(ClientInterface $client, string $apiKey, ?string $region = null)
    {
        parent::__construct($client);

        $this->api_key = $apiKey;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'google_maps';
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * @return ?string
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return [
            'key' => $this->getApiKey(),
        ];
    }

    /**
     * @return DistanceMatrixQueryInterface
     */
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
