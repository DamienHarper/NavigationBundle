<?php

namespace DH\NavigationBundle\Model\Routing;

class Leg
{
    /**
     * @var array
     */
    private $start;

    /**
     * @var array
     */
    private $end;

    /**
     * @var Step[]
     */
    private $steps;

    public function __construct(array $data)
    {
        $this->start = $data['start'] ?? [];
        $this->end = $data['end'] ?? [];
        $this->steps = $data['steps'] ?? [];
    }

    public function getStart(): array
    {
        return $this->start;
    }

    public function getEnd(): array
    {
        return $this->end;
    }

    /**
     * @return Step[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }
}
