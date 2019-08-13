<?php

namespace DH\NavigationBundle\Model\Routing;

class Route
{
    /**
     * @var Leg[]|array
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
     * @return Leg[]|array
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