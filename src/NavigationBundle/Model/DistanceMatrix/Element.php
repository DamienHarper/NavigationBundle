<?php

namespace DH\NavigationBundle\Model\DistanceMatrix;

use DH\NavigationBundle\Model\Distance;
use DH\NavigationBundle\Model\Duration;

class Element
{
    public const STATUS_OK = 'OK';
    public const STATUS_NOT_FOUND = 'NOT_FOUND';
    public const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';

    public const STATUS = [
        self::STATUS_OK,
        self::STATUS_NOT_FOUND,
        self::STATUS_ZERO_RESULTS,
    ];

    private $status;

    private $duration;

    private $distance;

    /**
     * Element constructor.
     *
     * @param $status
     * @param Duration $duration
     * @param Distance $distance
     *
     * @throws \Exception
     */
    public function __construct($status, Duration $duration, Distance $distance)
    {
        if (!\in_array($status, self::STATUS, true)) {
            throw new \Exception(sprintf('Unknown status code: %s', $status));
        }

        $this->status = $status;
        $this->duration = $duration;
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return Duration
     */
    public function getDuration(): Duration
    {
        return $this->duration;
    }

    /**
     * @return Distance
     */
    public function getDistance(): Distance
    {
        return $this->distance;
    }
}
