<?php

namespace DH\NavigationBundle\Model\DistanceMatrix;

class Row
{
    /**
     * @var Element[]
     */
    private $elements;

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return Element[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }
}
