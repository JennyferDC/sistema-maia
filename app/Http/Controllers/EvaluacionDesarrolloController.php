<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionDesarrollo;
use App\Models\EtapaDesarrollo;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EvaluacionDesarrolloController extends Controller
{
    public function index(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $evaluaciones = EvaluacionDesarrollo::where('nino_id', $nino->id)
            ->with('etapaDesarrollo:id,nombre_etapa')
            ->orderBy('fecha_evaluacion', 'desc')
            ->get();

        return Inertia::render('Evaluaciones/Index', [
            'nino'        => $nino->only(['id', 'nombre']),
            'evaluaciones' => $evaluaciones->map(function ($eval) {
                return [
                    'id'        => $eval->id,
                    'fecha'     => $eval->fecha_evaluacion ? $eval->fecha_evaluacion->format('d/m/Y') : null,
                    'etapa'     => optional($eval->etapaDesarrollo)->nombre_etapa,
                    'peso'      => $eval->peso,
                    'talla'     => $eval->talla,
                    'comentario_madre' => $eval->comentario_madre,
                ];
            }),
        ]);
    }

    public function create(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();

        return Inertia::render('Evaluaciones/Create', [
            'nino'   => $nino->only(['id', 'nombre']),
            'etapas' => $etapas,
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $data = $request->validate([
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
            'fecha_evaluacion'    => ['required', 'date'],
            'peso'                => ['required', 'numeric', 'min:0'],
            'talla'               => ['required', 'numeric', 'min:0'],
            'comentario_madre'    => ['nullable', 'string', 'max:255'],
        ]);

        $data['nino_id'] = $nino->id;

        EvaluacionDesarrollo::create($data);

        return Redirect::route('ninos.evaluaciones.index', $nino)
            ->with('success', 'Evaluación registrada exitosamente.');
    }

    public function show(Nino $nino, EvaluacionDesarrollo $evaluacion)
    {
        if ($nino->madre_id !== Auth::id() || $evaluacion->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $evaluacion->load('etapaDesarrollo:id,nombre_etapa');

        return Inertia::render('Evaluaciones/Show', [
            'nino'       => $nino->only(['id', 'nombre']),
            'evaluacion' => $evaluacion,
        ]);
    }

    public function destroy(Nino $nino, EvaluacionDesarrollo $evaluacion)
    {
        if ($nino->madre_id !== Auth::id() || $evaluacion->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $evaluacion->delete();

        return Redirect::route('ninos.evaluaciones.index', $nino)
            ->with('success', 'Evaluación eliminada correctamente.');
    }
}