<?php

namespace DH\NavigationBundle\Provider\Here;

use DH\NavigationBundle\Contract\DistanceMatrix\DistanceMatrixQueryInterface;
use DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixQuery;
use DH\NavigationBundle\Provider\ProviderInterface;

class Here implements ProviderInterface
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
    public function __construct(string $appId, string $appCode, bool $useCIT = false)
    {
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
