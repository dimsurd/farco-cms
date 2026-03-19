<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OccupancyController;
use App\Http\Controllers\Api\ApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/occupancies', [OccupancyController::class, 'index'])->middleware('api.key');
Route::post('/applications/submit', [ApplicationController::class, 'submit'])->middleware('api.key');
