<?php

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidBoardPosition;
use InvalidArgumentException;

class Board
{
    private function __construct(private array $value)
    {
        foreach ($this->value as $y => $row) {
            foreach ($row as $x => $piece) {
                if ($piece instanceof Piece) {
                    continue;
                }

                $this->value[$y][$x] = new Piece($piece);
            }
        }
    }

    public function getPositions(): array
    {
        return $this->value;
    }

    public function getPiece(Position $pos): Piece
    {
        return $this->value[$pos->y][$pos->x];
    }

    /**
     * @throws InvalidBoardPosition
     */
    public function setPiece(Piece $piece, Position $pos): self
    {
        if (! $this->getPiece($pos)->isEmpty()) {
            throw new InvalidBoardPosition(sprintf(
                'Board position %s is already in place',
                $pos
            ));
        }

        $clone = clone $this;
        $clone->value[$pos->y][$pos->x] = $piece;

        return $clone;
    }

    public function toArray(): array
    {
        return array_map(
            fn ($row) => array_map(fn (Piece $piece) => $piece->value, $row),
            $this->value
        );
    }

    public static function create(array $pos = []): static
    {
        $boardPos = [
            [Piece::EMPTY, Piece::EMPTY, Piece::EMPTY],
            [Piece::EMPTY, Piece::EMPTY, Piece::EMPTY],
            [Piece::EMPTY, Piece::EMPTY, Piece::EMPTY],
        ];

        for ($y=0; $y <= 2; $y++) {
            for ($x=0; $x <= 2; $x++) {
                if (isset($pos[$y]) && isset($pos[$y][$x])) {
                    $boardPos[$y][$x] = $pos[$y][$x];
                }
            }
        }

        return new static($boardPos);
    }
}
