<?php

namespace App\ValueObjects;

use JsonSerializable;

class GameState implements JsonSerializable
{
    public function __construct(
        public readonly Board $board,
        public readonly array $score
    )
    {
    }

    public function getCurrentTurn(): string
    {
        return $this->board->currentTurn->value;
    }

    public function jsonSerialize(): array
    {
        $winner = $this->board->getWinner();

        return [
            'board' => $this->board->toArray(),
            'score' => $this->score,
            'currentTurn' => $this->getCurrentTurn(),
            'victory' => $winner->value,
            'finished' => ! $winner->isEmpty()
        ];
    }
}
