<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionDesarrollo;
use App\Models\EtapaDesarrollo;
use App\Models\Nino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\FotoNino;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EvaluacionDesarrolloController extends Controller
{
    public function index(Nino $nino)
    {
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
        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();

        return Inertia::render('Evaluaciones/Create', [
            'nino'   => $nino->only(['id', 'nombre']),
            'etapas' => $etapas,
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        $data = $request->validate([
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
            'peso'                => ['required', 'numeric', 'min:0'],
            'talla'               => ['required', 'numeric', 'min:0'],
            'comentario_madre'    => ['nullable', 'string', 'max:255'],
            'foto'                => ['nullable', 'image', 'max:2048'],
            'foto_descripcion'    => ['nullable', 'string', 'max:255'],
        ]);

        $data['nino_id'] = $nino->id;
        $data['fecha_evaluacion'] = now();

        $evaluacion = EvaluacionDesarrollo::create($data);

        // Guardar foto si se envía
        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('fotos_ninos', 'public');
            FotoNino::create([
                'ruta_foto' => '/storage/' . $ruta,
                'fecha_subida' => now(),
                'descripcion' => $request->input('foto_descripcion'),
                'nino_id' => $nino->id,
                'etapa_desarrollo_id' => $data['etapa_desarrollo_id'],
            ]);
        }

        return Redirect::route('etapas.por-nino', $nino)
            ->with('success', 'Evaluación registrada exitosamente.');
    }

    public function update(Request $request, Nino $nino, EvaluacionDesarrollo $evaluacion)
    {
        $data = $request->validate([
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
            'peso'                => ['required', 'numeric', 'min:0'],
            'talla'               => ['required', 'numeric', 'min:0'],
            'comentario_madre'    => ['nullable', 'string', 'max:255'],
            'foto'                => ['nullable', 'image', 'max:2048'],
            'foto_descripcion'    => ['nullable', 'string', 'max:255'],
        ]);

        $data['fecha_evaluacion'] = now();
        $evaluacion->update($data);

        // Guardar o actualizar foto si se envía
        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('fotos_ninos', 'public');
            // Buscar si ya existe una foto para este niño y etapa
            $foto = FotoNino::where('nino_id', $nino->id)
                ->where('etapa_desarrollo_id', $data['etapa_desarrollo_id'])
                ->first();
            if ($foto) {
                $foto->update([
                    'ruta_foto' => '/storage/' . $ruta,
                    'fecha_subida' => now(),
                    'descripcion' => $request->input('foto_descripcion'),
                ]);
            } else {
                FotoNino::create([
                    'ruta_foto' => '/storage/' . $ruta,
                    'fecha_subida' => now(),
                    'descripcion' => $request->input('foto_descripcion'),
                    'nino_id' => $nino->id,
                    'etapa_desarrollo_id' => $data['etapa_desarrollo_id'],
                ]);
            }
        }

        return redirect()->route('etapas.por-nino', $nino)
            ->with('success', 'Evaluación actualizada exitosamente.');
    }

    public function show(Nino $nino, EvaluacionDesarrollo $evaluacion)
    {
        $evaluacion->load('etapaDesarrollo:id,nombre_etapa');

        return Inertia::render('Evaluaciones/Show', [
            'nino'       => $nino->only(['id', 'nombre']),
            'evaluacion' => $evaluacion,
        ]);
    }

    public function destroy(Nino $nino, EvaluacionDesarrollo $evaluacion)
    {
        $evaluacion->delete();

        return Redirect::route('ninos.evaluaciones.index', $nino)
            ->with('success', 'Evaluación eliminada correctamente.');
    }
}
