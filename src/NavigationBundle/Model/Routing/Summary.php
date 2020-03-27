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
     */
    public function getDistance(): Distance
    {
        return $this->distance;
    }

    /**
     * Get the value of trafficTime.
     */
    public function getTrafficTime(): Duration
    {
        return $this->trafficTime;
    }

    /**
     * Get the value of baseTime.
     */
    public function getBaseTime(): Duration
    {
        return $this->baseTime;
    }

    /**
     * Get the value of travelTime.
     */
    public function getTravelTime(): Duration
    {
        return $this->travelTime;
    }

    /**
     * Get the value of text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get the value of flags.
     */
    public function getFlags(): array
    {
        return $this->flags;
    }
}
