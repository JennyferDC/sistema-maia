<?php

namespace App\Http\Controllers;

use App\Models\Recomendacion;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class RecomendacionController extends Controller
{
    public function index(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $recomendaciones = Recomendacion::where('nino_id', $nino->id)
            ->orderBy('fecha_generada', 'desc')
            ->paginate(10);

        return Inertia::render('Recomendaciones/Index', [
            'nino' => $nino->only(['id', 'nombre']),
            'recomendaciones' => $recomendaciones
        ]);
    }

    public function show(Nino $nino, Recomendacion $recomendacion)
    {
        if ($nino->madre_id !== Auth::id() || $recomendacion->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Recomendaciones/Show', [
            'nino' => $nino->only(['id', 'nombre']),
            'recomendacion' => $recomendacion
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        // Esto lo generaría el ML o el sistema
        $data = $request->validate([
            'contenido' => 'required|string',
            'registro_observacion_salud_id' => 'nullable|exists:registros_observacion_salud,id',
            'diagnostico_medico_id' => 'nullable|exists:diagnosticos_medicos,id',
            'prediccion_id' => 'nullable|exists:predicciones,id',
            'alerta_id' => 'nullable|exists:alertas,id'
        ]);

        $data['nino_id'] = $nino->id;
        $data['fecha_generada'] = Carbon::now();

        Recomendacion::create($data);

        return Redirect::route('ninos.recomendaciones.index', $nino)
            ->with('success', 'Recomendación registrada correctamente.');
    }

    public function destroy(Nino $nino, Recomendacion $recomendacion)
    {
        if ($nino->madre_id !== Auth::id() || $recomendacion->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $recomendacion->delete();

        return Redirect::route('ninos.recomendaciones.index', $nino)
            ->with('success', 'Recomendación eliminada correctamente.');
    }
}