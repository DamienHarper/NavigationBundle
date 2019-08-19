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
     * @param int
     *
     * @return string
     */
    public function getFormattedValue(int $precision = 1): string
    {
        return FormatHelper::formatDistance($this->value, $precision);
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
