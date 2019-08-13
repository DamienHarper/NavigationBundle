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
     * @return string
     */
    public function getAsText(): string
    {
        return FormatHelper::formatTime($this->value);
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
        return $this->getAsText();
    }
}
