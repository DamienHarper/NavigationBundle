<?php

namespace DH\NavigationBundle\Model;

class Address
{
    /**
     * @var string
     */
    private $address;

    /**
     * Address constructor.
     *
     * @param $address string
     */
    public function __construct(string $address)
    {
        $this->address = $address;
    }

    public function __toString(): string
    {
        return $this->address;
    }
}
