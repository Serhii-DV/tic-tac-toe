<?php

namespace App\Services;

use App\ValueObjects\Board;
use App\ValueObjects\GameState;
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
    private $finished = false;

    public function __construct()
    {
        $this->loadState();
    }

    public function __destruct()
    {
        $this->saveState();
    }

    public function newGame(): GameState
    {
        return $this
            ->loadState()
            ->clearBoard()
            ->clearScore()
            ->unFinishGame()
            ->saveState()
            ->getGameState();
    }

    public function restartGame(): GameState
    {
        return $this
            ->loadState()
            ->finishGame()
            ->updateScore()
            ->clearBoard()
            ->saveState()
            ->getGameState();
    }

    public function move(string $piece, int $x, int $y): GameState
    {
        $piece = new Piece($piece);
        $coordinate = new Position($x, $y);

        $this->loadState();
        $this->board = $this->board->setPiece($piece, $coordinate);

        return $this
            ->updateScore()
            ->saveState()
            ->getGameState();
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
        if ($this->finished) {
            // Do not update the score
            return $this;
        }

        $winner = $this->board->getWinner();

        if (!$winner->isEmpty()) {
            $this->score[$winner->value] += 1;
        }

        return $this;
    }

    public function getGameState(): GameState
    {
        $state = new GameState(
            $this->board,
            $this->score,
            $this->finished
        );

        return $state;
    }

    public static function getDataFilePath(): string
    {
        return storage_path(self::DATA_FILE);
    }

    private function finishGame(): self
    {
        $this->finished = true;
        return $this;
    }

    private function unFinishGame(): self
    {
        $this->finished = false;
        return $this;
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
        File::put($this->getDataFilePath(), json_encode($this->getGameState(), JSON_PRETTY_PRINT));

        return $this;
    }
}
