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
        $data = $request->validate([
            'hito_id'             => 'required|exists:hitos,id',
            'fecha_logro'         => 'nullable|date',
            'observaciones'       => 'nullable|string|max:255',
            'etapa_desarrollo_id' => 'required|exists:etapas_desarrollo,id',
        ]);

        HitoLogrado::create([
            'nino_id'             => $nino->id,
            'hito_id'             => $data['hito_id'],
            'fecha_logro'         => $data['fecha_logro'] ?? Carbon::now(),
            'observaciones'       => $data['observaciones'] ?? null,
            'etapa_desarrollo_id' => $data['etapa_desarrollo_id'],
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Nino $nino, HitoLogrado $hitoLogrado)
    {
        $hitoLogrado->delete();

        return Redirect::route('ninos.hitos.index', $nino)
            ->with('success', 'Hito eliminado correctamente.');
    }
}
