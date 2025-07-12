<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\NinoController;
use App\Http\Controllers\EvaluacionDesarrolloController;
use App\Http\Controllers\EtapaDesarrolloController;
use App\Http\Controllers\DiagnosticoMedicoController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\RecomendacionController;
use App\Http\Controllers\TallerActividadController;
use App\Http\Controllers\PrediccionController;
use App\Http\Controllers\RegistroObservacionController;

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

    // 📌 NINOS
    Route::get('/ninos', [NinoController::class, 'index'])->name('ninos.index');
    Route::get('/ninos/create', [NinoController::class, 'create'])->name('ninos.create');
    Route::post('/ninos', [NinoController::class, 'store'])->name('ninos.store');
    Route::get('/ninos/{nino}', [NinoController::class, 'show'])->name('ninos.show');
    Route::get('/ninos/{nino}/edit', [NinoController::class, 'edit'])->name('ninos.edit');
    Route::put('/ninos/{nino}', [NinoController::class, 'update'])->name('ninos.update');
    Route::patch('/ninos/{nino}', [NinoController::class, 'update']);
    Route::delete('/ninos/{nino}', [NinoController::class, 'destroy'])->name('ninos.destroy');

    // 📌 ETAPAS DESARROLLO
    Route::get('/etapas-desarrollo', [EtapaDesarrolloController::class, 'getEtapasForSelect'])->name('etapas-desarrollo.select');
    Route::get('/ninos/{nino}/etapas', [EtapaDesarrolloController::class, 'etapasPorNino'])->name('etapas.por-nino');

    // 📌 EVALUACIONES
    Route::get('/ninos/{nino}/evaluaciones', [EvaluacionDesarrolloController::class, 'index'])->name('evaluaciones.index');
    Route::get('/ninos/{nino}/evaluaciones/create', [EvaluacionDesarrolloController::class, 'create'])->name('evaluaciones.create');
    Route::post('/ninos/{nino}/evaluaciones', [EvaluacionDesarrolloController::class, 'store'])->name('evaluaciones.store');
    Route::get('/evaluaciones/show/{id}', [EvaluacionDesarrolloController::class, 'show'])->name('evaluaciones.show');
    Route::post('/ninos/{nino}/evaluaciones/{evaluacion}', [EvaluacionDesarrolloController::class, 'update'])->name('evaluaciones.update');
    Route::patch('/ninos/{nino}/evaluaciones/{evaluacion}', [EvaluacionDesarrolloController::class, 'update']);

    // 📌 DIAGNÓSTICO MÉDICO
    Route::resource('diagnosticos', DiagnosticoMedicoController::class);

    // 📌 ALERTAS
    Route::get('/ninos/{nino}/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::post('/ninos/{nino}/alertas', [AlertaController::class, 'store'])->name('alertas.store');
    Route::put('/alertas/{alerta}', [AlertaController::class, 'update'])->name('alertas.update');
    Route::delete('/alertas/{alerta}', [AlertaController::class, 'destroy'])->name('alertas.destroy');

    // 📌 NOTIFICACIONES
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::put('/notificaciones/{notificacion}', [NotificacionController::class, 'update'])->name('notificaciones.update');

    // 📌 TALLERES / ACTIVIDADES
    Route::get('/talleres', [TallerActividadController::class, 'index'])->name('talleres.index');
    Route::post('/talleres', [TallerActividadController::class, 'store'])->name('talleres.store');
    Route::delete('/talleres/{taller}', [TallerActividadController::class, 'destroy'])->name('talleres.destroy');

    // 📌 PREDICCIONES
    Route::get('/ninos/{nino}/predicciones', [PrediccionController::class, 'index'])->name('predicciones.index');
    Route::post('/ninos/{nino}/predicciones', [PrediccionController::class, 'store'])->name('predicciones.store');

    // 📌 RECOMENDACIONES
    Route::get('/ninos/{nino}/recomendaciones', [RecomendacionController::class, 'index'])->name('recomendaciones.index');
    Route::post('/ninos/{nino}/recomendaciones', [RecomendacionController::class, 'store'])->name('recomendaciones.store');

    // 📌 REGISTROS OBSERVACIÓN SALUD
    Route::get('/ninos/{nino}/observaciones', [RegistroObservacionController::class, 'index'])->name('observaciones.index');
    Route::post('/ninos/{nino}/observaciones', [RegistroObservacionController::class, 'store'])->name('observaciones.store');

    // 📌 HITOS LOGRADOS
    Route::post('/ninos/{nino}/hitos-logrados', [\App\Http\Controllers\HitoLogradoController::class, 'store'])->name('hitos-logrados.store');


});
