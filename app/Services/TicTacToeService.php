<?php

namespace App\Services;

use App\ValueObjects\Board;
use App\ValueObjects\Position;
use App\ValueObjects\Piece;
use Illuminate\Support\Facades\File;

class TicTacToeService
{
    private const BOARD_FILE = 'app/board.json';

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

    private function getBoardFilePath(): string
    {
        return storage_path(self::BOARD_FILE);
    }

    private function getBoard(): Board
    {
        $filePath = $this->getBoardFilePath();
        $boardData = File::exists($filePath)
            ? json_decode(File::get($filePath), JSON_THROW_ON_ERROR)
            : [];

        return Board::create($boardData);
    }

    private function saveBoard(Board $board): void
    {
        File::put($this->getBoardFilePath(), json_encode($board->toArray(), JSON_PRETTY_PRINT));
    }
}
