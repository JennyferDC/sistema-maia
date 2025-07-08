<?php

namespace App\Http\Controllers;

use App\Models\EtapaDesarrollo;
use App\Models\Nino;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EtapaDesarrolloController extends Controller
{
    
    public function index()
    {
        $etapas = EtapaDesarrollo::with('hitos')->orderBy('id')->get();

        $ninos = Auth::user()->ninos()->with('hitoLogrados')
            ->select('id', 'nombre')
            ->get();

        return Inertia::render('Etapas/Index', [
            'etapas' => $etapas,
            'ninos' => $ninos,
        ]);
    }

    public function create()
    {
        return Inertia::render('Etapas/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_etapa' => ['required', 'string', 'max:255'],
        ]);

        EtapaDesarrollo::create($data);

        return Redirect::route('etapas_desarrollo.index')
            ->with('success', 'Etapa creada correctamente.');
    }

    public function show(EtapaDesarrollo $etapaDesarrollo)
    {

        $etapaDesarrollo->load(['ninos', 'evaluaciones', 'hitos']);

        return Inertia::render('Etapas/Show', [
            'etapa' => $etapaDesarrollo,
        ]);
    }

    public function edit(EtapaDesarrollo $etapaDesarrollo)
    {
        return Inertia::render('Etapas/Edit', [
            'etapa' => $etapaDesarrollo,
        ]);
    }

    public function update(Request $request, EtapaDesarrollo $etapaDesarrollo)
    {
        $data = $request->validate([
            'nombre_etapa' => ['required', 'string', 'max:255'],
        ]);

        $etapaDesarrollo->update($data);

        return Redirect::route('etapas_desarrollo.index')
            ->with('success', 'Etapa actualizada correctamente.');
    }

    public function destroy(EtapaDesarrollo $etapaDesarrollo)
    {
        $etapaDesarrollo->delete();

        return Redirect::route('etapas_desarrollo.index')
            ->with('success', 'Etapa eliminada correctamente.');
    }

    public function evaluacionEtapa($ninoId)
    {
        $nino = Nino::with('etapaDesarrollo')->where('madre_id', Auth::id())->findOrFail($ninoId);

        $etapas = EtapaDesarrollo::with(['evaluaciones' => function ($query) use ($nino) {
            $query->where('nino_id', $nino->id);
        }])->orderBy('id')->get();

        return Inertia::render('Etapas/Evaluaciones', [
            'nino' => $nino,
            'etapas' => $etapas,
        ]);
    }

    public function etapasPorNino(Nino $nino)
    {
    $etapas = EtapaDesarrollo::with('hitos')->orderBy('id')->get();

    return Inertia::render('Etapas/Index', [
        'nino' => $nino,
        'etapas' => $etapas,
    ]);
    
   }
   
}