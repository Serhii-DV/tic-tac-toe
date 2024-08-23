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
        $response->assertOk();
    }

    public function test_move_x(): void
    {
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
        $response = $this->postJson('/api/o', ['x'=>0, 'y'=>1]);

        $response->assertOk();
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

    public function test_it_can_delete(): void
    {
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

}
