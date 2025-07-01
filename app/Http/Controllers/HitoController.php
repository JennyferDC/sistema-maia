<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Hito;
use App\Models\EtapaDesarrollo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HitoController extends Controller
{
    public function index()
    {
        // Solo accesible para admin o madre
        if (!in_array(Auth::user()->rol, ['admin', 'madre'])) {
            abort(403, 'No autorizado');
        }

        $hitos = Hito::with('etapaDesarrollo:id,nombre_etapa')
            ->orderBy('etapa_desarrollo_id')
            ->orderBy('nombre_hito')
            ->get();

        return Inertia::render('Hitos/Index', [
            'hitos' => $hitos,
        ]);
    }

    public function create()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden crear hitos.');
        }

        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();

        return Inertia::render('Hitos/Create', [
            'etapas' => $etapas,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden registrar hitos.');
        }

        $data = $request->validate([
            'nombre_hito'         => ['required', 'string', 'max:255'],
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
        ]);

        Hito::create($data);

        return Redirect::route('hitos.index')
            ->with('success', 'Hito registrado correctamente.');
    }

    public function show(Hito $hito)
    {
        if (!in_array(Auth::user()->rol, ['admin', 'madre'])) {
            abort(403, 'No autorizado');
        }

        $hito->load('etapaDesarrollo:id,nombre_etapa');

        return Inertia::render('Hitos/Show', [
            'hito' => $hito,
        ]);
    }

    public function edit(Hito $hito)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden editar hitos.');
        }

        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();

        return Inertia::render('Hitos/Edit', [
            'hito'   => $hito,
            'etapas' => $etapas,
        ]);
    }

    public function update(Request $request, Hito $hito)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden actualizar hitos.');
        }

        $data = $request->validate([
            'nombre_hito'         => ['required', 'string', 'max:255'],
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
        ]);

        $hito->update($data);

        return Redirect::route('hitos.index')
            ->with('success', 'Hito actualizado correctamente.');
    }

    public function destroy(Hito $hito)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden eliminar hitos.');
        }

        $hito->delete();

        return Redirect::route('hitos.index')
            ->with('success', 'Hito eliminado correctamente.');
    }
}