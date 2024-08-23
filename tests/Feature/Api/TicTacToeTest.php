<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class TicTacToeTest extends TestCase
{
    public function test_game_state(): void
    {
        $response = $this->getJson('/api/');

        $response->assertJsonStructure([
            'board',
            'score',
            'currentTurn',
            'victory'
        ]);
        $response->assertStatus(200);
    }

    public function test_move_x(): void
    {
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>0]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'board',
                'score',
                'currentTurn',
                'victory'
            ]);
    }

    public function test_move_o(): void
    {
        $response = $this->postJson('/api/o', ['x'=>0, 'y'=>1]);

        $response->assertStatus(200);
    }

    public function test_move_x_invalid(): void
    {
        $response = $this->postJson('/api/x', ['x'=>0, 'y'=>3]);

        $response->assertNotAcceptable();
    }

    public function test_move_x_conflict(): void
    {
        //TODO
        // $response = $this->postJson('/api/x', ['x'=>0, 'y'=>3]);

        // $response->assertNotAcceptable();
    }

    public function test_move_o_invalid(): void
    {
        $response = $this->postJson('/api/o', ['x'=>-1, 'y'=>1]);

        $response->assertNotAcceptable();
    }

}
