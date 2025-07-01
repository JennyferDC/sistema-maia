<?php

namespace App\Http\Controllers;

use App\Models\Prediccion;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Carbon\Carbon;

class PrediccionController extends Controller
{
    public function index(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $predicciones = Prediccion::where('nino_id', $nino->id)
            ->orderBy('fecha_prediccion', 'desc')
            ->paginate(10);

        return Inertia::render('Predicciones/Index', [
            'nino' => $nino->only(['id', 'nombre']),
            'predicciones' => $predicciones
        ]);
    }

    public function show(Nino $nino, Prediccion $prediccion)
    {
        if ($nino->madre_id !== Auth::id() || $prediccion->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Predicciones/Show', [
            'nino' => $nino->only(['id', 'nombre']),
            'prediccion' => $prediccion
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        // Las predicciones normalmente las genera el sistema, pero aquí dejo validación si se requiriera
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $data = $request->validate([
            'tipo' => 'required|string|max:255',
            'resultado' => 'required|string',
            'probabilidad' => 'required|numeric|min:0|max:100',
            'registro_observacion_salud_id' => 'nullable|exists:registros_observacion_salud,id',
            'diagnostico_medico_id' => 'nullable|exists:diagnosticos_medicos,id'
        ]);

        $data['nino_id'] = $nino->id;
        $data['fecha_prediccion'] = Carbon::now();

        Prediccion::create($data);

        return Redirect::route('ninos.predicciones.index', $nino)
            ->with('success', 'Predicción registrada correctamente.');
    }

    public function destroy(Nino $nino, Prediccion $prediccion)
    {
        if ($nino->madre_id !== Auth::id() || $prediccion->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $prediccion->delete();

        return Redirect::route('ninos.predicciones.index', $nino)
            ->with('success', 'Predicción eliminada correctamente.');
    }
}
