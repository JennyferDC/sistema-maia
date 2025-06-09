<?php

namespace App\Http\Controllers;

use App\Models\Nino;
use App\Models\User;
use App\Models\EtapaDesarrollo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


class NinoController extends Controller
{    
    public function index() {

        $ninos = Nino::with(['madre', 'etapaDesarrollo'])
            ->where('madre_id', Auth::id()) // ← FILTRA SOLO LOS NIÑOS DE LA MADRE AUTENTICADA
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Ninos/Index', [
            'ninos' => $ninos
        ]);
    }

    public function create() {

    if (Auth::user()->rol !== 'madre') {  // Valida que el usuario es madre
        abort(403, 'No autorizado');
    }

        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();
        $madreId = Auth::id();

        return Inertia::render('Ninos/Create', [
            'etapas' => $etapas,
            'madreId' => $madreId // <-- lo pasas al frontend
        ]);
    }

    public function store(Request $request) {

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'semanas_prematuro' => ['required', 'integer', 'min:0'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'sexo' => ['required', 'in:masculino,femenino'],
            'peso_nacimiento' => ['required', 'numeric', 'min:0'],
            'talla_nacimiento' => ['required', 'numeric', 'min:0'],
        ]);
    
        $data['madre_id'] = Auth::id();
    
        $edadMeses = 0;
    
        if (!empty($data['fecha_nacimiento'])) {
            $fechaNacimiento = new DateTime($data['fecha_nacimiento']);
            $fechaActual = new DateTime(); // Fecha actual
    
            $intervalo = $fechaNacimiento->diff($fechaActual);
            $edadMeses = ($intervalo->y * 12) + $intervalo->m;
        }
    
        if ($edadMeses <= 1) {
            $data['etapa_desarrollo_id'] = 1; // Recién Nacido
        } elseif ($edadMeses <= 12) {
            $data['etapa_desarrollo_id'] = 2; // Lactante
        } elseif ($edadMeses <= 24) {
            $data['etapa_desarrollo_id'] = 3; // Primera Infancia
        } else {
            $data['etapa_desarrollo_id'] = 4; // Segunda Infancia
        }
    
        Nino::create($data);
    
        return Redirect::route('ninos.index')
            ->with('success', 'Niño creado exitosamente.');
    }

    public function show(Nino $nino) {
        $nino->load([
            'madre',
            'etapaDesarrollo',
            'fotos',
            'evaluaciones',
            'observacionesSalud',
            'diagnosticos',
            'alertas',
            'predicciones',
            'recomendaciones'
        ]);

        return Inertia::render('Ninos/Show', [
            'nino' => $nino
        ]);
    }

    public function edit(Nino $nino) {

        $madres = User::where('id', Auth::id())
            ->select('id', 'name as label') // label para el <select>
            ->get();

        $etapas = EtapaDesarrollo::select('id', 'nombre as label')->get();

        return Inertia::render('Ninos/Edit', [
            'nino' => $nino,
            'madres' => $madres,
            'etapas' => $etapas
        ]);
    }

    
    public function update(Request $request, Nino $nino) {
    
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'semanas_prematuro' => ['required', 'integer', 'min:0'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'sexo' => ['required', 'in:masculino,femenino'],
            'peso_nacimiento' => ['required', 'numeric', 'min:0'],
            'talla_nacimiento' => ['required', 'numeric', 'min:0'],
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'], // se recalcula si cambia fecha
        ]);

        // Recalcular etapa si cambia fecha de nacimiento
        if ($request->fecha_nacimiento !== $nino->fecha_nacimiento) {
            $edadMeses = 0;

            if (!empty($data['fecha_nacimiento'])) {
                $fechaNacimiento = new \DateTime($data['fecha_nacimiento']);
                $fechaActual = new \DateTime();
                $intervalo = $fechaNacimiento->diff($fechaActual);
                $edadMeses = ($intervalo->y * 12) + $intervalo->m;
            }

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
        return Redirect::route('ninos.index')
        ->with('success', 'Niño actualizado correctamente.');
    }

    public function destroy(Nino $nino)
    {
        $nino->delete();

        return Redirect::route('ninos.index')
            ->with('success', 'Niño eliminado correctamente.');
    }

}
