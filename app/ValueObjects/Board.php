<?php

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidBoardPosition;

class Board
{
    private function __construct(
        public readonly array $value,
        public readonly Piece $currentTurn
    )
    {
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

        $value = $this->value;
        $value[$pos->y][$pos->x] = $piece;
        $currentTurn = $piece->isX() ? Piece::O() : Piece::X();

        return new self($value, $currentTurn);
    }

    public function toArray(): array
    {
        return array_map(
            fn ($row) => array_map(fn (Piece $piece) => $piece->value, $row),
            $this->value
        );
    }

    public static function create(array $pos = [], string $currentTurn = Piece::X): static
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

        foreach ($boardPos as $y => $row) {
            foreach ($row as $x => $piece) {
                if ($piece instanceof Piece) {
                    continue;
                }

                $boardPos[$y][$x] = new Piece($piece);
            }
        }

        return new static($boardPos, new Piece($currentTurn));
    }
}
