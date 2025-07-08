<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;
use App\Models\Nino;
use App\Models\RegistroObservacionSalud;
use App\Models\Prediccion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Carbon;

class AlertaController extends Controller
{
    public function index(Nino $nino)
    {
        $alertas = Alerta::where('nino_id', $nino->id)
            ->with(['registroObservacionSalud:id,fecha', 'prediccion:id,tipo'])
            ->orderBy('fecha_generada', 'desc')
            ->paginate(10);

        return Inertia::render('Alertas/Index', [
            'nino'    => $nino->only(['id', 'nombre']),
            'alertas' => $alertas,
        ]);
    }

    public function create(Nino $nino)
    {
        $observaciones = RegistroObservacionSalud::where('nino_id', $nino->id)
            ->select('id', 'fecha as label')
            ->get();

        $predicciones = Prediccion::where('nino_id', $nino->id)
            ->select('id', 'tipo as label')
            ->get();

        return Inertia::render('Alertas/Create', [
            'nino'          => $nino->only(['id', 'nombre']),
            'observaciones' => $observaciones,
            'predicciones'  => $predicciones,
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        $data = $request->validate([
            'tipo_alerta'                  => ['required', 'string', 'max:255'],
            'descripcion'                  => ['nullable', 'string', 'max:255'],
            'resuelta'                     => ['required', 'boolean'],
            'registro_observacion_salud_id' => ['nullable', 'exists:registro_observacion_salud,id'],
            'prediccion_id'                => ['nullable', 'exists:predicciones,id'],
        ]);

        $data['fecha_generada'] = Carbon::now();
        $data['nino_id'] = $nino->id;

        Alerta::create($data);

        return Redirect::route('ninos.alertas.index', $nino)
            ->with('success', 'Alerta creada correctamente.');
    }

    public function show(Nino $nino, Alerta $alerta)
    {
        $alerta->load(['registroObservacionSalud:id,fecha', 'prediccion:id,tipo']);

        return Inertia::render('Alertas/Show', [
            'nino'   => $nino->only(['id', 'nombre']),
            'alerta' => $alerta,
        ]);
    }

    public function update(Request $request, Nino $nino, Alerta $alerta)
    {
        $data = $request->validate([
            'tipo_alerta'                  => ['required', 'string', 'max:255'],
            'descripcion'                  => ['nullable', 'string', 'max:255'],
            'resuelta'                     => ['required', 'boolean'],
            'registro_observacion_salud_id' => ['nullable', 'exists:registro_observacion_salud,id'],
            'prediccion_id'                => ['nullable', 'exists:predicciones,id'],
        ]);

        $alerta->update($data);

        return Redirect::route('ninos.alertas.index', $nino)
            ->with('success', 'Alerta actualizada correctamente.');
    }

    public function destroy(Nino $nino, Alerta $alerta)
    {
        $alerta->delete();

        return Redirect::route('ninos.alertas.index', $nino)
            ->with('success', 'Alerta eliminada correctamente.');
    }
}
