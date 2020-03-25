<?php

namespace DH\NavigationBundle\Model;

use DH\NavigationBundle\Helper\FormatHelper;

class Duration
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
     */
    public function getFormattedValue(int $precision = 1): string
    {
        return FormatHelper::formatTime($this->value, $precision);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getFormattedValue();
    }
}
