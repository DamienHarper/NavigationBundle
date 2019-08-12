<?php

namespace DH\NavigationBundle\Model\Routing;

class RouteSummary
{
    /**
     * @var int
     */
    private $distance;

    /**
     * @var int
     */
    private $trafficTime;

    /**
     * @var int
     */
    private $baseTime;

    /**
     * @var int
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
        $this->distance = $data['distance'] ?? null;
        $this->trafficTime = $data['trafficTime'] ?? null;
        $this->baseTime = $data['baseTime'] ?? null;
        $this->travelTime = $data['travelTime'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->flags = $data['flags'] ?? [];
    }

    /**
     * Get the value of distance.
     *
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * Get the value of trafficTime.
     *
     * @return int
     */
    public function getTrafficTime(): int
    {
        return $this->trafficTime;
    }

    /**
     * Get the value of baseTime.
     *
     * @return int
     */
    public function getBaseTime(): int
    {
        return $this->baseTime;
    }

    /**
     * Get the value of travelTime.
     *
     * @return int
     */
    public function getTravelTime(): int
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