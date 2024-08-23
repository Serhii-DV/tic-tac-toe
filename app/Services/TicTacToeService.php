<?php

namespace App\Services;

use App\ValueObjects\Board;
use App\ValueObjects\Position;
use App\ValueObjects\Piece;
use Illuminate\Support\Facades\File;

class TicTacToeService
{
    private const DATA_FILE = 'app/tic-tac-toe.json';

    private Board $board;

    public function __construct()
    {
        $this->loadState();
    }

    public function __destruct()
    {
        $this->saveState();
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
            'currentTurn' => $this->board->currentTurn->value,
            'victory' => '',
        ];

        return $state;
    }

    private function getDataFilePath(): string
    {
        return storage_path(self::DATA_FILE);
    }

    private function loadState(): void
    {
        $filePath = $this->getDataFilePath();
        $stateData = File::exists($filePath)
            ? json_decode(File::get($filePath), JSON_THROW_ON_ERROR)
            : [];

        $this->board = Board::create($stateData['board'] ?? []);
    }

    private function saveState(): void
    {
        File::put($this->getDataFilePath(), json_encode($this->getState(), JSON_PRETTY_PRINT));
    }
}
