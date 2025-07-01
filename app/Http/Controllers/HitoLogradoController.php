<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HitoLogrado;
use App\Models\Hito;
use App\Models\Nino;
use App\Models\EtapaDesarrollo;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Carbon\Carbon;

class HitoLogradoController extends Controller
{
    public function index(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $hitos = Hito::where('etapa_desarrollo_id', $nino->etapa_desarrollo_id)
            ->select('id', 'nombre_hito')
            ->get();

        $logros = HitoLogrado::where('nino_id', $nino->id)
            ->with('hito:id,nombre_hito')
            ->get();

        return Inertia::render('HitosLogrados/Index', [
            'nino'   => $nino->only(['id', 'nombre', 'etapa_desarrollo_id']),
            'hitos'  => $hitos,
            'logros' => $logros,
        ]);
    }

    public function create(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $hitos = Hito::where('etapa_desarrollo_id', $nino->etapa_desarrollo_id)
            ->select('id', 'nombre_hito')
            ->get();

        return Inertia::render('HitosLogrados/Create', [
            'nino'  => $nino->only(['id', 'nombre', 'etapa_desarrollo_id']),
            'hitos' => $hitos,
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $data = $request->validate([
            'logros'                       => 'required|array',
            'logros.*.hito_id'             => 'required|exists:hitos,id',
            'logros.*.fecha_logro'         => 'nullable|date',
            'logros.*.observaciones'       => 'nullable|string|max:255',
        ]);

        foreach ($data['logros'] as $logro) {
            HitoLogrado::updateOrCreate(
                [
                    'nino_id' => $nino->id,
                    'hito_id' => $logro['hito_id'],
                ],
                [
                    'fecha_logro'         => $logro['fecha_logro'] ?? Carbon::now(),
                    'observaciones'       => $logro['observaciones'] ?? null,
                    'etapa_desarrollo_id' => $nino->etapa_desarrollo_id,
                ]
            );
        }

        return Redirect::route('ninos.hitos.index', $nino)
            ->with('success', 'Hitos logrados guardados correctamente.');
    }

    public function destroy(Nino $nino, HitoLogrado $hitoLogrado)
    {
        if ($nino->madre_id !== Auth::id() || $hitoLogrado->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $hitoLogrado->delete();

        return Redirect::route('ninos.hitos.index', $nino)
            ->with('success', 'Hito eliminado correctamente.');
    }
}