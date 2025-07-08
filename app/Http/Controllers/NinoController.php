<?php

namespace App\Http\Controllers;

use App\Models\Nino;
use App\Models\User;
use App\Models\EtapaDesarrollo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


class NinoController extends Controller
{

    public function index()
    {
        $ninos = Nino::with(['madre', 'etapaDesarrollo'])
            ->where('madre_id', Auth::id())  // ← FILTRA SOLO LOS NIÑOS DE LA MADRE AUTENTICADA
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Ninos/Index', [
            'ninos' => $ninos
        ]);
    }

    public function create()
    {
        // if (Auth::user()->rol !== 'madre') { // VALIDA QUE EL USUARIO ES LA MADRE
        //     abort(403, 'No autorizado');
        // }

        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();
        $madreId = Auth::id();

        return Inertia::render('Ninos/Create', [
            'etapas' => $etapas,
            'madreId' => $madreId
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'es_prematuro' => ['required', 'boolean'],
            'semanas_prematuro' => ['required_if:es_prematuro,true', 'nullable', 'integer', 'min:0'],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:masculino,femenino'],
            'peso' => ['required', 'numeric', 'min:0'],
            'talla' => ['required', 'numeric', 'min:0'],
        ]);

        $data['madre_id'] = Auth::id();

        $edadMeses = Carbon::parse($data['fecha_nacimiento'])->diffInMonths(Carbon::now());

        if ($edadMeses <= 1) {
            $data['etapa_desarrollo_id'] = 1;
        } elseif ($edadMeses <= 12) {
            $data['etapa_desarrollo_id'] = 2;
        } elseif ($edadMeses <= 24) {
            $data['etapa_desarrollo_id'] = 3;
        } else {
            $data['etapa_desarrollo_id'] = 4;
        }

        Nino::create($data);

        return Redirect::route('ninos.index')->with('success', 'Niño creado exitosamente.');
    }

    public function show(Nino $nino)
    {
        $nino->load([
            'madre',
            'etapaDesarrollo',
            'fotos',
            'evaluaciones',
            'observacionesSalud',
            'diagnosticoMedico',
            'alertas',
            'predicciones',
            'recomendaciones'
        ]);

        return Inertia::render('Ninos/Show', [
            'nino' => $nino
        ]);
    }

    public function edit(Nino $nino)
    {
        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();

        return Inertia::render('Ninos/Edit', [
            'nino' => $nino,
            'etapas' => $etapas
        ]);
    }

    public function update(Request $request, Nino $nino)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'es_prematuro' => ['required', 'boolean'],
            'semanas_prematuro' => ['required_if:es_prematuro,true', 'nullable', 'integer', 'min:0'],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:masculino,femenino'],
            'peso' => ['required', 'numeric', 'min:0'],
            'talla' => ['required', 'numeric', 'min:0'],
        ]);

        if ($request->fecha_nacimiento !== $nino->fecha_nacimiento) {
            $edadMeses = Carbon::parse($data['fecha_nacimiento'])->diffInMonths(Carbon::now());

            if ($edadMeses <= 1) {
                $data['etapa_desarrollo_id'] = 1;
            } elseif ($edadMeses <= 12) {
                $data['etapa_desarrollo_id'] = 2;
            } elseif ($edadMeses <= 24) {
                $data['etapa_desarrollo_id'] = 3;
            } else {
                $data['etapa_desarrollo_id'] = 4;
            }
        }

        $nino->update($data);

        return Redirect::route('ninos.index')->with('success', 'Niño actualizado correctamente.');
    }

    public function destroy(Nino $nino)
    {
        // if ($nino->madre_id !== Auth::id()) {
        //     abort(403, 'No autorizado');
        // }

        $nino->delete();

        return Redirect::route('ninos.index')->with('success', 'Niño eliminado correctamente.');
    }
    
}
