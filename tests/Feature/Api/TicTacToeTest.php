<?php

namespace Tests\Feature\Api;

use App\Services\TicTacToeService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TicTacToeTest extends TestCase
{
    public function test_game_state(): void
    {
        $this->deleteDataFile();
        $response = $this->getJson('/api/');

        $response->assertJsonStructure([
            'board',
            'score',
            'currentTurn',
            'victory'
        ]);
        $response->assertOk();
    }

    public function test_move_x(): void
    {
        $this->deleteDataFile();
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>0]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'board',
                'score',
                'currentTurn',
                'victory'
            ]);
    }

    public function test_move_o(): void
    {
        $this->deleteDataFile();
        // Reset the game
        $this->deleteJson('api/');

        $response = $this->postJson('/api/o', ['x'=>0, 'y'=>1]);

        $response->assertOk();
    }

    public function test_move_x_invalid(): void
    {
        $this->deleteDataFile();
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>3]);

        $response->assertNotAcceptable();
    }

    public function test_move_x_conflict(): void
    {
        $this->getJson('/');
        $this->postJson('/api/x', ['x'=>0, 'y'=>0]);
        $response = $this->postJson('/api/o', ['x'=>0, 'y'=>0]);

        $response->assertConflict();
    }

    public function test_move_o_invalid(): void
    {
        $this->deleteDataFile();
        $response = $this->postJson('/api/o', ['x'=>-1, 'y'=>1]);

        $response->assertNotAcceptable();
    }

    public function test_it_can_delete(): void
    {
        $this->deleteDataFile();
        $response = $this->deleteJson('api/');
        $response
            ->assertOk()
            ->assertJsonStructure([
                'currentTurn',
            ])
            ->assertJson([
                'currentTurn' => 'x'
            ]);
    }

    public function test_it_can_restart(): void
    {
        $this->deleteDataFile();
        $response = $this->postJson('api/restart');
        $response
            ->assertOk()
            ->assertJsonStructure([
                'board',
                'score',
                'currentTurn',
                'victory'
            ]);
    }

    public function test_it_can_get_the_winner(): void
    {
        // Reset the game
        $this->deleteDataFile();

        // Add some moves
        $this->postJson('/api/x', ['x'=>0, 'y'=>0]);
        $this->postJson('/api/o', ['x'=>1, 'y'=>0]);
        $this->postJson('/api/x', ['x'=>0, 'y'=>1]);
        $this->postJson('/api/o', ['x'=>1, 'y'=>1]);
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>2]);

        $response
            ->assertOk()
            ->assertJson([
                'score' => [
                    'x' => 1,
                    'o' => 0,
                ],
                'victory' => 'x',
            ]);
    }

    public function test_it_can_get_the_winner_in_two_games(): void
    {
        // Reset the game
        $this->deleteDataFile();

        // Add some moves
        $this->postJson('/api/x', ['x'=>0, 'y'=>0]);
        $this->postJson('/api/o', ['x'=>1, 'y'=>0]);
        $this->postJson('/api/x', ['x'=>0, 'y'=>1]);
        $this->postJson('/api/o', ['x'=>1, 'y'=>1]);
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>2]);

        // Winner - x. Score: 1:0
        $response
            ->assertOk()
            ->assertJson([
                'score' => [
                    'x' => 1,
                    'o' => 0,
                ],
                'victory' => 'x',
            ]);

        $this->postJson('/api/restart');

        // Add some moves
        $this->postJson('/api/x', ['x'=>0, 'y'=>0]);
        $this->postJson('/api/o', ['x'=>1, 'y'=>0]);
        $this->postJson('/api/x', ['x'=>0, 'y'=>1]);
        $this->postJson('/api/o', ['x'=>1, 'y'=>1]);
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>2]);

        // Winner - x. Score: 2:0

        $response
            ->assertOk()
            ->assertJson([
                'score' => [
                    'x' => 2,
                    'o' => 0,
                ],
                'victory' => 'x',
            ]);
    }

    private function deleteDataFile(): void
    {
        File::delete(TicTacToeService::getDataFilePath());
    }
}
