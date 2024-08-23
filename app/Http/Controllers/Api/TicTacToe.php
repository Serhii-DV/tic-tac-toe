<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameStateResource;
use App\Services\TicTacToeService;
use Illuminate\Http\Request;

class TicTacToe extends Controller
{
    public function __construct(private TicTacToeService $ticTacToeService)
    {

    }

    public function index(): string
    {
        return json_encode($this->ticTacToeService->getState());
    }

    public function move()
    {
        return __METHOD__;
    }

    public function restart()
    {
        return __METHOD__;
    }

    public function delete()
    {
        return __METHOD__;
    }
}
