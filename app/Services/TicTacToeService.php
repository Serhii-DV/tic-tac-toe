<?php

namespace App\Services;

use App\ValueObjects\Board;
use App\ValueObjects\Position;
use App\ValueObjects\Piece;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class TicTacToeService
{
    private Board $board;

    public function __construct()
    {
        $this->board = $this->getBoard();
    }

    public function __destruct()
    {
        $this->saveBoard($this->board);
    }

    public function move(string $piece, int $x, int $y): void
    {
        $piece = new Piece($piece);
        $coordinate = new Position($x, $y);
        $this->board = $this->board->setPiece($piece, $coordinate);
    }

    public function getState(): array
    {
        $state = [
            'board' => $this->board->toArray(),
            'score' => [],
            'currentTurn' => '',
            'victory' => '',
        ];

        return $state;
    }

    private function getBoard(): Board
    {
        return Board::create(Session::get('board', []));
    }

    private function saveBoard(Board $board): void
    {
        Session::put('board', $board->toArray());
    }
}
