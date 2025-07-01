<?php

namespace App\Http\Controllers;

use App\Models\FotoNino;
use App\Models\Nino;
use App\Models\EtapaDesarrollo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class FotoNinoController extends Controller
{
    public function index(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $fotos = FotoNino::where('nino_id', $nino->id)
            ->with('etapaDesarrollo:id,nombre_etapa')
            ->orderBy('fecha_subida', 'desc')
            ->paginate(10);

        return Inertia::render('FotoNinos/Index', [
            'nino'  => $nino->only(['id', 'nombre']),
            'fotos' => $fotos,
        ]);
    }

    public function create(Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $etapas = EtapaDesarrollo::select('id', 'nombre_etapa as label')->get();

        return Inertia::render('FotoNinos/Create', [
            'nino'   => $nino->only(['id', 'nombre']),
            'etapas' => $etapas,
        ]);
    }

    public function store(Request $request, Nino $nino)
    {
        if ($nino->madre_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $data = $request->validate([
            'imagen'              => ['required', 'image', 'max:2048'],
            'descripcion'         => ['nullable', 'string', 'max:255'],
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
        ]);

        $ruta = $request->file('imagen')
            ->store("public/fotos_ninos/{$nino->madre_id}");

        FotoNino::create([
            'ruta_foto'           => Storage::url($ruta),
            'fecha_subida'        => Carbon::now(),
            'descripcion'         => $data['descripcion'],
            'nino_id'             => $nino->id,
            'etapa_desarrollo_id' => $data['etapa_desarrollo_id'],
        ]);

        return Redirect::route('ninos.fotos.index', $nino)
            ->with('success', 'Foto subida correctamente.');
    }

    public function show(Nino $nino, FotoNino $fotoNino)
    {
        if ($nino->madre_id !== Auth::id() || $fotoNino->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('FotoNinos/Show', [
            'foto' => $fotoNino->load('etapaDesarrollo:id,nombre_etapa'),
            'nino' => $nino->only(['id', 'nombre']),
        ]);
    }

    public function update(Request $request, Nino $nino, FotoNino $fotoNino)
    {
        if ($nino->madre_id !== Auth::id() || $fotoNino->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        $data = $request->validate([
            'descripcion'         => ['nullable', 'string', 'max:255'],
            'etapa_desarrollo_id' => ['required', 'exists:etapas_desarrollo,id'],
        ]);

        $fotoNino->update($data);

        return Redirect::route('ninos.fotos.index', $nino)
            ->with('success', 'Foto actualizada correctamente.');
    }

    public function destroy(Nino $nino, FotoNino $fotoNino)
    {
        if ($nino->madre_id !== Auth::id() || $fotoNino->nino_id !== $nino->id) {
            abort(403, 'No autorizado');
        }

        if ($fotoNino->ruta_foto) {
            Storage::delete(str_replace('/storage/', 'public/', $fotoNino->ruta_foto));
        }

        $fotoNino->delete();

        return Redirect::route('ninos.fotos.index', $nino)
            ->with('success', 'Foto eliminada correctamente.');
    }
}