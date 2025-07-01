<?php
use App\Http\Controllers\Controller;

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
        if (Auth::user()->rol !== 'madre') {
            abort(403, 'No autorizado');
        }

        $etapas = EtapaDesarrollo::with('hitos')->orderBy('id')->get();

        $ninos = Auth::user()->ninos()->with('hitoLogrados')
            ->select('id', 'nombre')
            ->get();

        return Inertia::render('EtapasDesarrollo/Index', [
            'etapas' => $etapas,
            'ninos' => $ninos,
        ]);
    }

    public function create()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden crear etapas.');
        }

        return Inertia::render('EtapasDesarrollo/Create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden guardar etapas.');
        }

        $data = $request->validate([
            'nombre_etapa' => ['required', 'string', 'max:255'],
        ]);

        EtapaDesarrollo::create($data);

        return Redirect::route('etapas_desarrollo.index')
            ->with('success', 'Etapa creada correctamente.');
    }

    public function show(EtapaDesarrollo $etapaDesarrollo)
    {
        if (!in_array(Auth::user()->rol, ['madre', 'admin'])) {
            abort(403, 'No autorizado.');
        }

        $etapaDesarrollo->load(['ninos', 'evaluaciones', 'hitos']);

        return Inertia::render('EtapasDesarrollo/Show', [
            'etapa' => $etapaDesarrollo,
        ]);
    }

    public function edit(EtapaDesarrollo $etapaDesarrollo)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo los administradores pueden editar etapas.');
        }

        return Inertia::render('EtapasDesarrollo/Edit', [
            'etapa' => $etapaDesarrollo,
        ]);
    }

    public function update(Request $request, EtapaDesarrollo $etapaDesarrollo)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo administradores pueden actualizar etapas');
        }

        $data = $request->validate([
            'nombre_etapa' => ['required', 'string', 'max:255'],
        ]);

        $etapaDesarrollo->update($data);

        return Redirect::route('etapas_desarrollo.index')
            ->with('success', 'Etapa actualizada correctamente.');
    }

    public function destroy(EtapaDesarrollo $etapaDesarrollo)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo administradores pueden eliminar etapas');
        }

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

        return Inertia::render('EtapasDesarrollo/Evaluaciones', [
            'nino' => $nino,
            'etapas' => $etapas,
        ]);
    }
}