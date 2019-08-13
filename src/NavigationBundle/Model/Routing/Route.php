<?php

namespace DH\NavigationBundle\Model\Routing;

class Route
{
    /**
     * @var array|Leg[]
     */
    private $legs = [];

    /**
     * @var Summary
     */
    private $summary;

    public function __construct(array $data)
    {
        $this->legs = $data['legs'];
        $this->summary = $data['summary'];
    }

    /**
     * @return array|Leg[]
     */
    public function getLegs(): array
    {
        return $this->legs;
    }

    public function getSummary(): ?Summary
    {
        return $this->summary;
    }
}
