<?php

namespace App\Http\Controllers;

use App\Models\EtapaDesarrollo;
use App\Models\Nino;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EtapaDesarrolloController extends Controller
{
    public function index()
    {
        $etapas = EtapaDesarrollo::orderBy('id')->get();

        return Inertia::render('EtapasDesarrollo/Index', [
            'etapas' => $etapas,
            'status' => session('status'),
        ]);
    }

    public function create() {
        return Inertia::render('EtapasDesarrollo/Create');
    }

    public function store(Request $request) {
        $request->validate([
            'nombre_etapa' => 'required|string|max:255|unique:etapas_desarrollo', 
        ]);
        
        EtapaDesarrollo::create([
            'nombre_etapa' => $request->input('nombre_etapa'), 
        ]);

        return redirect()->route('etapas-desarrollo.index')
            ->with('status', 'Etapa de desarrollo creada exitosamente');
    }

    public function show(EtapaDesarrollo $etapaDesarrollo)
    {
        return Inertia::render('EtapasDesarrollo/Show', [
            'etapa' => $etapaDesarrollo->load(['ninos', 'evaluaciones']),
        ]);
    }

    public function edit(EtapaDesarrollo $etapaDesarrollo) {
        return Inertia::render('EtapasDesarrollo/Edit', [
            'etapa' => $etapaDesarrollo,
        ]);
    }

    public function update(Request $request, EtapaDesarrollo $etapaDesarrollo) {
        
        $request->validate([
            'nombre_etapa' => 'required|string|max:255|unique:etapas_desarrollo,nombre_etapa,'.$etapaDesarrollo->id,
        ]);

        $etapaDesarrollo->update([
            'nombre_etapa' => $request->input('nombre_etapa'),
        ]);

        return redirect()->route('etapas-desarrollo.index')
            ->with('status', 'Etapa de desarrollo actualizada');
    }

    public function destroy(EtapaDesarrollo $etapaDesarrollo) {
        // Verificar si hay niños o evaluaciones asociadas
        if ($etapaDesarrollo->ninos()->count() > 0 || $etapaDesarrollo->evaluaciones()->count() > 0) {
            return back()->withErrors([
                'message' => 'No se puede eliminar esta etapa',
            ]);
        }

        $etapaDesarrollo->delete();

        return redirect()->route('etapas-desarrollo.index')
            ->with('status', 'Etapa eliminada exitosamente');
    }


    public function etapasPorNino($nino) {

        $nino = Nino::with('etapaDesarrollo')->findOrFail($nino);

        $etapas = EtapaDesarrollo::with(['evaluaciones' => function ($query) use ($nino) {
            $query->where('nino_id', $nino->id);
        }])->orderBy('id')->get();

        return Inertia::render('Etapas/Index', [
            'nino' => $nino,
            'etapas' => $etapas,
        ]);
    }

}