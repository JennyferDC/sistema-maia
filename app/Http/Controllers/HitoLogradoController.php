<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HitoLogradoController extends Controller
{

    public function obtenerHitos()
    {
        return collect([
            'sostiene_cabeza',
            'sonrie_socialmente',
            'fija_mirada',
            'reacciona_a_sonidos',
            'se_sienta_con_apoyo',
            'coord_mano_ojo',
            'vocaliza',
            'reconoce_familiares',
            'se_sienta_sin_apoyo',
            'gatea',
            'balbucea',
            'responde_a_su_nombre',
            'camina',
            'corre',
            'forma_frases',
            'juega_simbolico',
        ])->map(fn($nombre) => [
            'label' => ucfirst(str_replace('_', ' ', $nombre)),
            'nombre' => $nombre,
        ]);
    }

     // Muestra los hitos logrados de una evaluación
    public function index($evaluacionId)
    {
         $hitosLogrados = HitoLogrado::with('hito')
             ->where('evaluacion_id', $evaluacionId)
             ->get();
 
         return response()->json($hitosLogrados);
    } 

    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluacion_id' => 'required|exists:evaluaciones_desarrollo,id',
            'hitos' => 'required|array',
            'hitos.*.nombre_hito' => 'required|string',
            'hitos.*.logrado' => 'required|boolean',
            'hitos.*.fecha_cumplimiento' => 'nullable|date',
        ]);

        foreach ($data['hitos'] as $hitoData) {
            HitoLogrado::updateOrCreate(
                [
                    'evaluacion_id' => $data['evaluacion_id'],
                    'nombre_hito' => $hitoData['nombre_hito'],
                ],
                [
                    'logrado' => $hitoData['logrado'],
                    'fecha_cumplimiento' => $hitoData['fecha_cumplimiento'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Hitos registrados correctamente.');
    }

}
