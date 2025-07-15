<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Nino;

class GeminiController extends Controller
{
    public function generarRecomendaciones(Request $request)
    {
        $nino = $this->getNinoWithRelations($request->input('nino_id'));
        $prompt = $this->getNinoInfoPrompt($nino, 'recomendaciones');
        $apiKey = env('GOOGLE_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";
        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'recomendaciones' => [
                            'type' => 'ARRAY',
                            'items' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'tipo_recomendacion' => [ 'type' => 'STRING' ],
                                    'descripcion' => [ 'type' => 'STRING' ]
                                ],
                                'propertyOrdering' => ['tipo_recomendacion', 'descripcion']
                            ]
                        ]
                    ],
                    'propertyOrdering' => ['recomendaciones']
                ]
            ]
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $body);
        $text = $response->json('candidates.0.content.parts.0.text') ?? '';
        $recomendaciones = [];
        if ($text) {
            $json = json_decode($text, true);
            if (isset($json['recomendaciones'])) {
                $recomendaciones = $json['recomendaciones'];
            }
        }
        return response()->json(['recomendaciones' => $recomendaciones]);
    }

    public function generarAlertas(Request $request)
    {
        $nino = $this->getNinoWithRelations($request->input('nino_id'));
        $prompt = $this->getNinoInfoPrompt($nino, 'alertas');
        $apiKey = env('GOOGLE_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";
        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'alertas' => [
                            'type' => 'ARRAY',
                            'items' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'tipo_alerta' => [ 'type' => 'STRING' ],
                                    'descripcion' => [ 'type' => 'STRING' ]
                                ],
                                'propertyOrdering' => ['tipo_alerta', 'descripcion']
                            ]
                        ]
                    ],
                    'propertyOrdering' => ['alertas']
                ]
            ]
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $body);
        $text = $response->json('candidates.0.content.parts.0.text') ?? '';
        $alertas = [];
        if ($text) {
            $json = json_decode($text, true);
            if (isset($json['alertas'])) {
                $alertas = $json['alertas'];
            }
        }
        return response()->json(['alertas' => $alertas]);
    }

    public function generarPredicciones(Request $request)
    {
        $nino = $this->getNinoWithRelations($request->input('nino_id'));
        $prompt = $this->getNinoInfoPrompt($nino, 'predicciones');
        $apiKey = env('GOOGLE_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";
        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'predicciones' => [
                            'type' => 'ARRAY',
                            'items' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'tipo_prediccion' => [ 'type' => 'STRING' ],
                                    'resultado' => [ 'type' => 'STRING' ],
                                    'probabilidad' => [ 'type' => 'STRING' ]
                                ],
                                'propertyOrdering' => ['tipo_prediccion', 'resultado', 'probabilidad']
                            ]
                        ]
                    ],
                    'propertyOrdering' => ['predicciones']
                ]
            ]
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $body);
        $text = $response->json('candidates.0.content.parts.0.text') ?? '';
        $predicciones = [];
        if ($text) {
            $json = json_decode($text, true);
            if (isset($json['predicciones'])) {
                $predicciones = $json['predicciones'];
            }
        }
        return response()->json(['predicciones' => $predicciones]);
    }

    private function getNinoWithRelations($ninoId)
    {
        return Nino::with([
            'madre',
            'etapaDesarrollo',
            'hitoLogrados',
            'fotos',
            'evaluaciones',
            'observacionesSalud',
            'diagnosticoMedico',
            'alertas',
            'predicciones',
            'recomendaciones',
            'tallerActividad',
        ])->findOrFail($ninoId);
    }

    private function getNinoInfoPrompt($nino, $tipo = 'recomendaciones')
    {
        // Datos básicos
        $datos = [
            'Nombre' => $nino->nombre,
            'Prematuro' => $nino->es_prematuro ? 'Sí' : 'No',
            'Semanas prematuro' => $nino->semanas_prematuro,
            'Fecha nacimiento' => $nino->fecha_nacimiento,
            'Sexo' => $nino->sexo,
            'Peso nacimiento' => $nino->peso,
            'Talla nacimiento' => $nino->talla,
        ];
        if ($nino->madre) {
            $datos['Madre'] = $nino->madre->name;
        }
        if ($nino->etapaDesarrollo) {
            $datos['Etapa desarrollo'] = $nino->etapaDesarrollo->nombre;
        }
        // Relaciones
        $diagnosticos = $nino->diagnosticoMedico->map(function($d) {
            return $d->tipo_diagnostico . ': ' . $d->descripcion;
        })->implode('; ') ?: 'ninguno';
        $observaciones = $nino->observacionesSalud->map(function($o) {
            return $o->tipo_observacion . ': ' . $o->observaciones;
        })->implode('; ') ?: 'ninguna';
        $hitos = $nino->hitoLogrados->map(function($h) {
            return $h->hito_id . ' (' . $h->fecha_logro . ')';
        })->implode('; ') ?: 'ninguno';
        $fotos = $nino->fotos->map(function($f) {
            return $f->descripcion;
        })->implode('; ') ?: 'ninguna';
        $evaluaciones = $nino->evaluaciones->map(function($e) {
            return 'Peso: ' . $e->peso . 'kg, Talla: ' . $e->talla . 'cm, Fecha: ' . $e->fecha_evaluacion;
        })->implode('; ') ?: 'ninguna';
        $alertas = $nino->alertas->map(function($a) {
            return $a->tipo_alerta . ': ' . $a->descripcion;
        })->implode('; ') ?: 'ninguna';
        $predicciones = $nino->predicciones->map(function($p) {
            return $p->tipo . ': ' . $p->resultado . ' (Prob: ' . $p->probabilidad . ')';
        })->implode('; ') ?: 'ninguna';
        $recomendaciones = $nino->recomendaciones->map(function($r) {
            return $r->contenido;
        })->implode('; ') ?: 'ninguna';
        $talleres = $nino->tallerActividad->map(function($t) {
            return $t->nombre . ' (' . $t->fecha . ')';
        })->implode('; ') ?: 'ninguno';

        $prompt = "Genera una lista de $tipo para un niño con la siguiente información completa:\n";
        foreach ($datos as $k => $v) {
            $prompt .= "$k: $v\n";
        }
        $prompt .= "Diagnósticos médicos: $diagnosticos\n";
        $prompt .= "Observaciones de salud: $observaciones\n";
        $prompt .= "Hitos logrados: $hitos\n";
        $prompt .= "Fotos: $fotos\n";
        $prompt .= "Evaluaciones: $evaluaciones\n";
        $prompt .= "Alertas previas: $alertas\n";
        $prompt .= "Predicciones: $predicciones\n";
        $prompt .= "Recomendaciones previas: $recomendaciones\n";
        $prompt .= "Talleres/Actividades: $talleres\n";
        if ($tipo === 'recomendaciones') {
            $prompt .= "\nDevuelve la respuesta en formato JSON con la siguiente estructura: {\"recomendaciones\":[{\"tipo_recomendacion\":\"...\",\"descripcion\":\"...\"}]}";
        } elseif ($tipo === 'alertas') {
            $prompt .= "\nDevuelve la respuesta en formato JSON con la siguiente estructura: {\"alertas\":[{\"tipo_alerta\":\"...\",\"descripcion\":\"...\"}]}";
        } elseif ($tipo === 'predicciones') {
            $prompt .= "\nDevuelve la respuesta en formato JSON con la siguiente estructura: {\"predicciones\":[{\"tipo_prediccion\":\"...\",\"resultado\":\"...\",\"probabilidad\":\"...\"}]}";
        }
        return $prompt;
    }
}
