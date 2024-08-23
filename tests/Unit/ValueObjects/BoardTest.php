<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Board;
use App\ValueObjects\Position;
use App\ValueObjects\Piece;
use Tests\TestCase;

class BoardTest extends TestCase
{
    public function test_it_can_create_empty_board(): void
    {
        $board = Board::create();
        $this->assertEquals([
            [Piece::EMPTY(), Piece::EMPTY(), Piece::EMPTY()],
            [Piece::EMPTY(), Piece::EMPTY(), Piece::EMPTY()],
            [Piece::EMPTY(), Piece::EMPTY(), Piece::EMPTY()],
        ], $board->getPositions());
        $this->assertEquals(Piece::X()->value, $board->currentTurn->value);
    }

    public function test_it_can_create_non_empty_board(): void
    {
        $board = Board::create([
            ['x', 'o', ''],
            ['x', 'x', ''],
            ['', '', ''],
        ]);

        $this->assertEquals([
            [Piece::X(), Piece::O(), Piece::EMPTY()],
            [Piece::X(), Piece::X(), Piece::EMPTY()],
            [Piece::EMPTY(), Piece::EMPTY(), Piece::EMPTY()],
        ], $board->getPositions());
        $this->assertEquals(Piece::X()->value, $board->currentTurn->value);
    }

    public function test_it_can_set_position(): void
    {
        $board = Board::create()->setPiece(
            Piece::X(),
            new Position(0, 0)
        );

        $this->assertEquals([
            [Piece::X(), Piece::EMPTY(), Piece::EMPTY()],
            [Piece::EMPTY(), Piece::EMPTY(), Piece::EMPTY()],
            [Piece::EMPTY(), Piece::EMPTY(), Piece::EMPTY()],
        ], $board->getPositions());
        $this->assertEquals(Piece::O()->value, $board->currentTurn->value);

    }

    public function test_it_can_get_the_winner_horizontal(): void
    {
        $board = Board::create([
            ['x', 'x', 'x'],
            ['x', 'o', ''],
            ['', '', ''],
        ]);

        $this->assertEquals(Piece::X(), $board->getWinner());
    }

    public function test_it_can_get_the_winner_vertical(): void
    {
        $board = Board::create([
            ['x', 'o', 'x'],
            ['o', 'o', ''],
            ['', 'o', ''],
        ]);

        $this->assertEquals(Piece::O(), $board->getWinner());
    }

    public function test_it_can_get_the_winner_diagonal(): void
    {
        $board = Board::create([
            ['x', 'o', 'x'],
            ['o', 'x', ''],
            ['', 'o', 'x'],
        ]);

        $this->assertEquals(Piece::X(), $board->getWinner());
    }

    public function test_it_cannot_get_the_winner(): void
    {
        $board = Board::create([
            ['x', 'o', 'x'],
            ['o', 'x', ''],
            ['', 'o', ''],
        ]);

        $this->assertEquals(Piece::EMPTY(), $board->getWinner());
    }
}
