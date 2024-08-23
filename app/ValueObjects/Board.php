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

    public function getWinner(): Piece
    {
        $winner = $this->getHorizontalWinner();

        if ($winner->isEmpty()) {
            $winner = $this->getVerticalWinner();

            if ($winner->isEmpty()) {
                $winner = $this->getDiagonalWinner();
            }
        }

        return $winner;
    }

    private function getHorizontalWinner(): Piece
    {
        foreach ($this->toArray() as $y => $row) {
            $winner = $this->getWinnerFromRow($row);

            if (!$winner->isEmpty()) {
                break;
            }
        }

        return $winner;
    }

    private function getVerticalWinner(): Piece
    {
        $boardArr = $this->toArray();

        for ($x=0; $x <= 2; $x++) {
            $row = [
                $boardArr[0][$x],
                $boardArr[1][$x],
                $boardArr[2][$x],
            ];
            $winner = $this->getWinnerFromRow($row);

            if (!$winner->isEmpty()) {
                break;
            }
        }

        return $winner;
    }

    private function getDiagonalWinner(): Piece
    {
        $boardArr = $this->toArray();

        $mainDiagonal = [$boardArr[0][0], $boardArr[1][1], $boardArr[2][2]];
        $winner = $this->getWinnerFromRow($mainDiagonal);

        if ($winner->isEmpty()) {
            $antiDiagonal = [$boardArr[0][2], $boardArr[1][1], $boardArr[2][0]];
            $winner = $this->getWinnerFromRow($antiDiagonal);
        }

        return $winner;
    }

    private function getWinnerFromRow(array $row): Piece
    {
        $winner = Piece::EMPTY();
        $rowStr = implode('', $row);

        if ($rowStr === 'xxx') {
            $winner = Piece::X();
        } elseif ($rowStr === 'ooo') {
            $winner = Piece::O();
        }

        return $winner;
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
