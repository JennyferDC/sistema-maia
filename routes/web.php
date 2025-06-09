<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\NinoController;
use App\Http\Controllers\EvaluacionDesarrolloController;
use App\Http\Controllers\EtapaDesarrolloController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Rutas para Nino (definidas manualmente)
    // Listar todos los niños
    Route::get('/ninos', [NinoController::class, 'index'])
        ->name('ninos.index');

    // Mostrar formulario de creación
    Route::get('/ninos/create', [NinoController::class, 'create'])
        ->name('ninos.create');

    // Guardar nuevo niño
    Route::post('/ninos', [NinoController::class, 'store'])
        ->name('ninos.store');

    // Ver detalle de un niño
    Route::get('/ninos/{nino}', [NinoController::class, 'show'])
        ->name('ninos.show');

    // Mostrar formulario de edición
    Route::get('/ninos/{nino}/edit', [NinoController::class, 'edit'])
        ->name('ninos.edit');

    // Actualizar un niño existente
    Route::put('/ninos/{nino}', [NinoController::class, 'update'])
        ->name('ninos.update');
    Route::patch('/ninos/{nino}', [NinoController::class, 'update']);

    // Eliminar un niño
    Route::delete('/ninos/{nino}', [NinoController::class, 'destroy'])
        ->name('ninos.destroy');

    // ETAPAS DESARROLLO
    Route::get('/etapas-desarrollo', [EtapaDesarrolloController::class, 'getEtapasForSelect'])
        ->name('etapas-desarrollo.select'); 

    
    Route::get('/evaluaciones/show/{id}', [EvaluacionDesarrolloController::class, 'show'])
        ->name('evaluaciones.show');

    Route::get('/ninos/{nino}/etapas', [EtapaDesarrolloController::class, 'etapasPorNino'])
        ->name('etapas.por-nino');
});
