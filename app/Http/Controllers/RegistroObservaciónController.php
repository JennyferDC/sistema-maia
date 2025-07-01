<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RegistroObservacionSalud;
use App\Models\Nino;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class RegistroObservacionController extends Controller
{
    public function index(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $registros = RegistroObservacionSalud::where('nino_id', $nino->id)
            ->orderBy('fecha_registro', 'desc')
            ->paginate(10);

        return Inertia::render('RegistrosObservacion/Index', [
            'nino' => $nino->only(['id', 'nombre']),
            'registros' => $registros
        ]);
    }

    public function create(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('RegistrosObservacion/Create', [
            'nino' => $nino->only(['id', 'nombre'])
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $data = $request->validate([
            'tipo_observacion' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $data['nino_id'] = $nino->id;
        $data['fecha_registro'] = Carbon::now();

        RegistroObservacionSalud::create($data);

        return Redirect::route('ninos.registros_observacion.index', $nino)
            ->with('success', 'Registro de observación guardado correctamente.');
    }

    public function destroy(Nino $nino, RegistroObservacionSalud $registro)
    {
        if ($nino->madre_id !== Auth::id() || $registro->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $registro->delete();

        return Redirect::route('ninos.registros_observacion.index', $nino)
            ->with('success', 'Registro eliminado correctamente.');
    }
}
