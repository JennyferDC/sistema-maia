<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HitoController extends Controller {

    public function index() {
        
    $hitosAgrupados = EtapaDesarrollo::with('hitos')->get();

    return Inertia::render('Hitos/Index', [
        'hitosAgrupados' => $hitosAgrupados
    ]);
}
    
}
