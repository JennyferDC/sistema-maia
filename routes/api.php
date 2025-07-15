<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::post('/gemini/recomendaciones', [GeminiController::class, 'generarRecomendaciones']);
Route::post('/gemini/alertas', [GeminiController::class, 'generarAlertas']);
Route::post('/gemini/predicciones', [GeminiController::class, 'generarPredicciones']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
