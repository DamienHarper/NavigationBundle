<?php

namespace DH\NavigationBundle\Provider\Here;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Provider\AbstractProvider;
use DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixQuery;
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
     * @param ClientInterface $client
     * @param string          $appId   an App ID
     * @param string          $appCode an App code
     * @param bool            $useCIT  use Customer Integration Testing environment (CIT) instead of production
     */
    public function __construct(ClientInterface $client, string $appId, string $appCode, bool $useCIT = false)
    {
        parent::__construct($client);

        $this->app_id = $appId;
        $this->app_code = $appCode;
        $this->useCIT = $useCIT;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'here';
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->app_id;
    }

    /**
     * @return string
     */
    public function getAppCode(): string
    {
        return $this->app_code;
    }

    /**
     * @return bool
     */
    public function isCitEnabled(): bool
    {
        return $this->useCIT;
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return [
            'app_id' => $this->getAppId(),
            'app_code' => $this->getAppCode(),
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
