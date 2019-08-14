<?php

namespace DH\NavigationBundle\Model\Routing;

use DH\NavigationBundle\Model\Distance;
use DH\NavigationBundle\Model\Duration;

class Step
{
    /**
     * @var array
     */
    private $position;

    /**
     * @var Distance
     */
    private $distance;

    /**
     * @var Duration
     */
    private $duration;

    /**
     * @var string
     */
    private $instruction;

    public function __construct(array $data)
    {
        $this->position = $data['position'] ?? [];
        $this->instruction = $data['instruction'] ?? '';
        $this->duration = new Duration((int) ($data['instruction'] ?? 0));
        $this->distance = new Distance((int) ($data['distance'] ?? 0));
    }

    /**
     * Get the value of position.
     *
     * @return array
     */
    public function getPosition(): array
    {
        return $this->position;
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
     * Get the value of duration.
     *
     * @return Duration
     */
    public function getDuration(): Duration
    {
        return $this->duration;
    }

    /**
     * Get the value of instruction.
     *
     * @return string
     */
    public function getInstruction(): string
    {
        return $this->instruction;
    }
}
