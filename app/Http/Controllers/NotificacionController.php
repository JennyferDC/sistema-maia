<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('madre_id', Auth::id())
            ->with('alerta:id,tipo_alerta')
            ->orderBy('fecha', 'desc')
            ->paginate(10);

        return Inertia::render('Notificaciones/Index', [
            'notificaciones' => $notificaciones
        ]);
    }

    public function show(Notificacion $notificacion)
    {
        return Inertia::render('Notificaciones/Show', [
            'notificacion' => $notificacion->load('alerta:id,tipo_alerta,descripcion')
        ]);
    }

    public function store(Request $request)
    {
        // Normalmente esto lo hace el sistema / ML
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'estado' => 'required|string|max:50',
            'alerta_id' => 'nullable|exists:alertas,id',
            'taller_actividad_id' => 'nullable|exists:talleres_actividades,id'
        ]);

        $data['madre_id'] = Auth::id();
        $data['fecha'] = Carbon::now();
        $data['leida'] = false;

        Notificacion::create($data);

        return Redirect::route('notificaciones.index')
            ->with('success', 'Notificación registrada correctamente.');
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $data = $request->validate([
            'leida' => 'required|boolean',
            'estado' => 'required|string|max:50'
        ]);

        $notificacion->update($data);

        return Redirect::route('notificaciones.index')
            ->with('success', 'Notificación actualizada correctamente.');
    }

    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();

        return Redirect::route('notificaciones.index')
            ->with('success', 'Notificación eliminada correctamente.');
    }
}