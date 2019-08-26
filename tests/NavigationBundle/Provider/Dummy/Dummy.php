<?php

namespace DH\NavigationBundle\Tests\Provider\Dummy;

use DH\NavigationBundle\Provider\AbstractProvider;

class Dummy extends AbstractProvider
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'dummy';
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
