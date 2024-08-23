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
        return response()->json($this->ticTacToeService->getState());
    }

    public function move(Request $request, string $piece): JsonResponse
    {
        $validatedData = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        try {
            $this->ticTacToeService->move($piece, $validatedData['x'], $validatedData['y']);
        } catch (InvalidArgumentException $th) {
            abort(406, 'Not Acceptable');
        } catch (InvalidBoardPosition $th) {
            abort(409, 'Conflict');
        }

        return response()->json($this->ticTacToeService->getState());
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
