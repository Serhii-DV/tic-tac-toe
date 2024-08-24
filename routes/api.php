<?php

use App\Http\Controllers\Api\TicTacToe;
use Illuminate\Support\Facades\Route;

Route::get('/', [TicTacToe::class, 'index']);
Route::post('/restart', [TicTacToe::class, 'restart']);
Route::post('/{piece}', [TicTacToe::class, 'move']);
Route::delete('/', [TicTacToe::class, 'delete']);
