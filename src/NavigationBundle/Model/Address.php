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
    public function __construct($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->address;
    }
}
