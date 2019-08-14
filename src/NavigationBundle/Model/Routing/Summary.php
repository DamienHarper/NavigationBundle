<?php

namespace DH\NavigationBundle\Model\Routing;

use DH\NavigationBundle\Model\Distance;
use DH\NavigationBundle\Model\Duration;

class Summary
{
    /**
     * @var Distance
     */
    private $distance;

    /**
     * @var Duration
     */
    private $trafficTime;

    /**
     * @var Duration
     */
    private $baseTime;

    /**
     * @var Duration
     */
    private $travelTime;

    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $flags;

    public function __construct(array $data)
    {
        $this->distance = new Distance($data['distance'] ?? 0);
        $this->trafficTime = new Duration($data['trafficTime'] ?? 0);
        $this->baseTime = new Duration($data['baseTime'] ?? 0);
        $this->travelTime = new Duration($data['travelTime'] ?? 0);
        $this->text = $data['text'] ?? null;
        $this->flags = $data['flags'] ?? [];
    }

    /**
     * Get the value of distance.
     *
     * @return Distance
     */
    public function getDistance(): Distance
    {
        return $this->distance;
    }

    /**
     * Get the value of trafficTime.
     *
     * @return Duration
     */
    public function getTrafficTime(): Duration
    {
        return $this->trafficTime;
    }

    /**
     * Get the value of baseTime.
     *
     * @return Duration
     */
    public function getBaseTime(): Duration
    {
        return $this->baseTime;
    }

    /**
     * Get the value of travelTime.
     *
     * @return Duration
     */
    public function getTravelTime(): Duration
    {
        return $this->travelTime;
    }

    /**
     * Get the value of text.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get the value of flags.
     *
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }
}
