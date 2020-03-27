<?php

namespace DH\NavigationBundle\Tests\Provider\Dummy;

use DH\NavigationBundle\Provider\AbstractProvider;

class Dummy extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'dummy';
    }

    /**
     * {@inheritdoc}
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
