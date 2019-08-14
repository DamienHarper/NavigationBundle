<?php

namespace DH\NavigationBundle\Model;

use DH\NavigationBundle\Helper\FormatHelper;

class Distance
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getFormattedValue(): string
    {
        return FormatHelper::formatDistance($this->value);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFormattedValue();
    }
}
