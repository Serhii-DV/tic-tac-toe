<?php

namespace App\Services;

final class TicTacToeService
{
    public function __construct()
    {
    }

    public function getState(): array
    {
        $state = [
            'board' => [],
            'score' => [],
            'currentTurn' => '',
            'victory' => '',
        ];

        return $state;
    }
}
