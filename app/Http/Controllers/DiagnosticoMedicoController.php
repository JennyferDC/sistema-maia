<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosticoMedico;
use App\Models\Nino;
use Inertia\Inertia;

class DiagnosticoMedicoController extends Controller
{
    // Mostrar todos los diagnósticos con Inertia
    public function index()
    {
        $diagnosticos = DiagnosticoMedico::with('nino')->get();

        return Inertia::render('Salud/Index', [
            'diagnosticos' => $diagnosticos,
            'status' => session('status'),
        ]);
    }

    // Mostrar formulario para crear diagnósticos
    public function create()
    {
        $ninos = Nino::select('id', 'nombre')->get();

        return Inertia::render('Diagnosticos/Create', [
            'ninos' => $ninos,
        ]);
    }

    // Guardar un solo diagnóstico desde formulario tradicional
    public function store(Request $request)
    {
        $request->validate([
            'tipo_diagnostico' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'origen_diagnostico' => 'nullable|string|max:255',
            'nino_id' => 'required|exists:ninos,id',
        ]);

        DiagnosticoMedico::create($request->only([
            'tipo_diagnostico',
            'fecha_inicio',
            'origen_diagnostico',
            'nino_id',
        ]));

        return redirect()->route('diagnosticos.index')
            ->with('status', 'Diagnóstico registrado correctamente');
    }

    // Guardar múltiples diagnósticos desde Vue con Inertia
    public function storeMultiples(Request $request)
    {
        $request->validate([
            'diagnosticos' => 'required|array',
            'diagnosticos.*.diagnostico' => 'required|string|max:255',
            'diagnosticos.*.fecha_diagnostico' => 'required|date',
            'diagnosticos.*.categoria' => 'nullable|string|max:255',
            'diagnosticos.*.nino_id' => 'required|exists:ninos,id',
        ]);

        foreach ($request->diagnosticos as $dato) {
            DiagnosticoMedico::create([
                'tipo_diagnostico' => $dato['diagnostico'],
                'fecha_inicio' => $dato['fecha_diagnostico'],
                'origen_diagnostico' => $dato['categoria'] ?? null,
                'nino_id' => $dato['nino_id'],
            ]);
        }

        return redirect()->route('diagnosticos.index')
            ->with('status', 'Diagnósticos registrados correctamente');
    }

    // Mostrar un diagnóstico (opcional)
    public function show(DiagnosticoMedico $diagnosticoMedico)
    {
        return Inertia::render('Diagnosticos/Show', [
            'diagnostico' => $diagnosticoMedico->load('nino'),
        ]);
    }

    // Editar diagnóstico (opcional)
    public function edit(DiagnosticoMedico $diagnosticoMedico)
    {
        $ninos = Nino::select('id', 'nombre')->get();

        return Inertia::render('Diagnosticos/Edit', [
            'diagnostico' => $diagnosticoMedico,
            'ninos' => $ninos,
        ]);
    }

    // Actualizar diagnóstico (opcional)
    public function update(Request $request, DiagnosticoMedico $diagnosticoMedico)
    {
        $request->validate([
            'tipo_diagnostico' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'origen_diagnostico' => 'nullable|string|max:255',
            'nino_id' => 'required|exists:ninos,id',
        ]);

        $diagnosticoMedico->update($request->only([
            'tipo_diagnostico',
            'fecha_inicio',
            'origen_diagnostico',
            'nino_id',
        ]));

        return redirect()->route('diagnosticos.index')
            ->with('status', 'Diagnóstico actualizado correctamente');
    }

    // Eliminar diagnóstico (opcional)
    public function destroy(DiagnosticoMedico $diagnosticoMedico)
    {
        $diagnosticoMedico->delete();

        return redirect()->route('diagnosticos.index')
            ->with('status', 'Diagnóstico eliminado correctamente');
    }
}