<?php

use App\Http\Controllers\Api\TicTacToe;
use Illuminate\Support\Facades\Route;

Route::get('/', [TicTacToe::class, 'index']);
Route::post('/{piece}', [TicTacToe::class, 'move']);
Route::post('/restart', [TicTacToe::class, 'restart']);
Route::delete('/', [TicTacToe::class, 'delete']);
