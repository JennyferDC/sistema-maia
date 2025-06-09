<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionDesarrollo;
use App\Models\EtapaDesarrollo;
use App\Models\Nino;
use Illuminate\Http\Request;
use Inertia\Inertia;


class EvaluacionDesarrolloController extends Controller
{
    // Lista evaluaciones de un niño
    public function index($ninoId) {

        $nino = Nino::findOrFail($ninoId);

        $evaluaciones = EvaluacionDesarrollo::with('etapaDesarrollo')
            ->where('nino_id', $ninoId)
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($eval) {
                return [
                    'id' => $eval->id,
                    'fecha' => $eval->fecha->format('d/m/Y'),
                    'etapa' => $eval->etapaDesarrollo->nombre_etapa,
                    'peso' => $eval->peso,
                    'talla' => $eval->talla
                ];
            });

        return Inertia::render('Evaluaciones/Index', [
            'nino' => $nino,
            'evaluaciones' => $evaluaciones
        ]);
    }

    public function create($ninoId) {

        $nino = Nino::findOrFail($ninoId);
        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();
        
        return Inertia::render('Evaluaciones/Create', [
            'nino' => $nino,
            'etapas' => $etapas,
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nino_id' => 'required|exists:ninos,id',
            'etapa_desarrollo_id' => 'required|exists:etapas_desarrollo,id',
            'fecha' => 'required|date',
            'peso' => 'required|numeric|min:0',
            'talla' => 'required|numeric|min:0',
        ]);

        $evaluacion = EvaluacionDesarrollo::create($validated);

        return redirect()
            ->route('evaluaciones.index', $validated['nino_id'])
            ->with('success', 'Evaluación registrada exitosamente.');
    }

    public function show($id) {

        $evaluacion = EvaluacionDesarrollo::with(['nino', 'etapaDesarrollo'])->findOrFail($id);

        return Inertia::render('Evaluaciones/Show', [
            'evaluacion' => $evaluacion,
        ]);
    }
}