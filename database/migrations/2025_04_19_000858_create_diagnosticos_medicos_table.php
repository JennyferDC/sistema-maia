<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticosMedicosTable extends Migration
{
    public function up()
    {
        Schema::create('diagnosticos_medicos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_diagnostico');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // si es null, el diagnóstico sigue activo o es permanente
            
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosticos_medicos');
    }
}
