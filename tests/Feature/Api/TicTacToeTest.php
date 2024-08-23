<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class TicTacToeTest extends TestCase
{
    public function test_run_the_game(): void
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
}
