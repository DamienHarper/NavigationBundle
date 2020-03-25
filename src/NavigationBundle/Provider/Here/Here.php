<?php

namespace DH\NavigationBundle\Provider\Here;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Contract\Routing\RoutingQueryInterface;
use DH\NavigationBundle\Provider\AbstractProvider;
use DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixQuery;
use DH\NavigationBundle\Provider\Here\Routing\RoutingQuery;
use GuzzleHttp\ClientInterface;

class Here extends AbstractProvider
{
    /**
     * @var string
     */
    private $app_id;

    /**
     * @var string
     */
    private $app_code;

    /**
     * @var bool
     */
    private $useCIT;

    /**
     * Here constructor.
     *
     * @param string $appId   an App ID
     * @param string $appCode an App code
     * @param bool   $useCIT  use Customer Integration Testing environment (CIT) instead of production
     */
    public function __construct(ClientInterface $client, string $appId, string $appCode, bool $useCIT = false)
    {
        parent::__construct($client);

        $this->app_id = $appId;
        $this->app_code = $appCode;
        $this->useCIT = $useCIT;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'here';
    }

    public function getAppId(): string
    {
        return $this->app_id;
    }

    public function getAppCode(): string
    {
        return $this->app_code;
    }

    public function isCitEnabled(): bool
    {
        return $this->useCIT;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): array
    {
        return [
            'app_id' => $this->getAppId(),
            'app_code' => $this->getAppCode(),
        ];
    }

    public function createDistanceMatrixQuery(): DistanceMatrixQueryInterface
    {
        return new DistanceMatrixQuery($this);
    }

    public function createRoutingQuery(): RoutingQueryInterface
    {
        return new RoutingQuery($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
