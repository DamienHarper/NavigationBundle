<?php

namespace DH\NavigationBundle\Model\DistanceMatrix;

class Row
{
    /**
     * @var array
     */
    private $elements;

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }
}
