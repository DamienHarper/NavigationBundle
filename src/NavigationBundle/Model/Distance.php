<?php

namespace DH\NavigationBundle\Model;

class Distance
{
    private $text;

    private $value;

    /**
     * Distance constructor.
     *
     * @param $text
     * @param $value
     */
    public function __construct(string $text = '', int $value = -1)
    {
        $this->text = $text;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
