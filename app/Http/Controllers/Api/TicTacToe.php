<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameStateResource;
use App\Services\TicTacToeService;
use App\ValueObjects\Exceptions\InvalidBoardPosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TicTacToe extends Controller
{
    public function __construct(private TicTacToeService $ticTacToeService)
    {

    }

    public function index(): JsonResponse
    {
        $state = $this->ticTacToeService->getGameState();

        return response()->json($state);
    }

    public function move(Request $request, string $piece): JsonResponse
    {
        $validatedData = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        try {
            $state = $this->ticTacToeService->move($piece, $validatedData['x'], $validatedData['y']);
        } catch (InvalidArgumentException $th) {
            abort(406, 'Not Acceptable');
        } catch (InvalidBoardPosition $th) {
            abort(409, 'Conflict');
        }

        return response()->json($state);
    }

    public function restart()
    {
        $state = $this->ticTacToeService->restartGame();

        return response()->json($state);
    }

    public function delete(): JsonResponse
    {
        $state = $this->ticTacToeService->newGame();

        return response()->json([
            'currentTurn' => $state->getCurrentTurn()
        ]);
    }
}
