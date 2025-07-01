<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluacionesDesarrolloTable extends Migration
{
    public function up()
    {

        Schema::create('evaluaciones_desarrollo', function (Blueprint $table) {
            
            $table->id();
            $table->dateTime('fecha_evaluacion');
            $table->float('peso');
            $table->float('talla');
            $table->string('comentario_madre')->nullable();

            $table->foreignId('etapa_desarrollo_id')->constrained('etapas_desarrollo')->onDelete('cascade');
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            
            $table->timestamps();

        });
        
    }

    public function down()
    {
        Schema::dropIfExists('evaluaciones_desarrollo');
    }
}
