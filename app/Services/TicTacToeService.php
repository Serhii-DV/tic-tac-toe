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
    private $score = [
        Piece::X => 0,
        Piece::O => 0,
    ];

    public function __construct()
    {
        $this->loadState();
    }

    public function __destruct()
    {
        $this->saveState();
    }

    public function move(string $piece, int $x, int $y): self
    {
        $piece = new Piece($piece);
        $coordinate = new Position($x, $y);
        $this->board = $this->board->setPiece($piece, $coordinate);

        return $this;
    }

    public function clearBoard(): self
    {
        $this->board = Board::create();

        return $this;
    }

    public function clearScore(): self
    {
        $this->score = [
            Piece::X => 0,
            Piece::O => 0,
        ];

        return $this;
    }

    public function updateScore(): self
    {
        $winner = $this->board->getWinner();

        if (!$winner->isEmpty()) {
            $this->score[$winner->value] += 1;
        }

        return $this;
    }

    public function getState(): array
    {
        $winner = $this->board->getWinner();

        $state = [
            'board' => $this->board->toArray(),
            'score' => $this->score,
            'currentTurn' => $this->board->currentTurn->value,
            'victory' => $winner->value,
            'finished' => ! $winner->isEmpty()
        ];

        return $state;
    }

    public static function getDataFilePath(): string
    {
        return storage_path(self::DATA_FILE);
    }

    private function loadState(): self
    {
        $filePath = $this->getDataFilePath();
        $stateData = File::exists($filePath)
            ? json_decode(File::get($filePath), JSON_THROW_ON_ERROR)
            : [];

        $this->board = Board::create(
            $stateData['board'] ?? [],
            $stateData['currentTurn'] ?? Piece::X
        );

        $this->clearScore();
        $this->score = $stateData['score'] ?? $this->score;

        return $this;
    }

    private function saveState(): self
    {
        File::put($this->getDataFilePath(), json_encode($this->getState(), JSON_PRETTY_PRINT));

        return $this;
    }
}
