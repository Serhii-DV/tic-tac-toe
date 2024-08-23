<?php

namespace App\ValueObjects;

use InvalidArgumentException;

class Position
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
        if ($x < 0 || $x > 2) {
            throw new InvalidArgumentException('Invalid board x position');
        }

        if ($y < 0 || $y > 2) {
            throw new InvalidArgumentException('Invalid board y position');
        }
    }

    public function toString()
    {
        return sprintf('x: %s, y: %s', $this->x, $this->y);
    }

    public function __toString()
    {
        return $this->toString();
    }
}
