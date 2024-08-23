<?php

namespace App\ValueObjects;

use InvalidArgumentException;

class Piece
{
    public const X = 'x';
    public const O = 'o';
    public const EMPTY = '';

    public function __construct(public readonly string $value)
    {
        if (! in_array($value, [self::X, self::O, self::EMPTY])) {
            throw new InvalidArgumentException(sprintf(
                'Unknown piece value: "%s". Expected "%s", "%s" or "%s"',
                $value,
                self::X,
                self::O,
                self::EMPTY
            ));
        }
    }

    public function isEmpty(): bool
    {
        return $this->value === self::EMPTY;
    }

    public function isX(): bool
    {
        return $this->value === self::X;
    }

    public function isO(): bool
    {
        return $this->value === self::O;
    }

    public static function X(): self
    {
        return new self(self::X);
    }

    public static function O(): self
    {
        return new self(self::O);
    }

    public static function EMPTY(): self
    {
        return new self(self::EMPTY);
    }
}
