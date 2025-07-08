<?php

namespace App\Http\Controllers;

use App\Models\TallerActividad;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Carbon\Carbon;

class TallerActividadController extends Controller
{
    public function index(Nino $nino)
    {
        $actividades = TallerActividad::where('nino_id', $nino->id)
            ->orderBy('fecha_evento', 'desc')
            ->paginate(10);

        return Inertia::render('TalleresActividades/Index', [
            'nino' => $nino->only(['id', 'nombre']),
            'actividades' => $actividades
        ]);
    }

    public function create(Nino $nino)
    {
        return Inertia::render('TalleresActividades/Create', [
            'nino' => $nino->only(['id', 'nombre'])
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo_evento' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_evento' => 'required|date',
        ]);

        $data['nino_id'] = $nino->id;
        $data['madre_id'] = Auth::id();

        TallerActividad::create($data);

        return Redirect::route('ninos.talleres.index', $nino)
            ->with('success', 'Taller o actividad registrada correctamente.');
    }

    public function destroy(Nino $nino, TallerActividad $actividad)
    {
        $actividad->delete();

        return Redirect::route('ninos.talleres.index', $nino)
            ->with('success', 'Taller o actividad eliminada correctamente.');
    }
}
