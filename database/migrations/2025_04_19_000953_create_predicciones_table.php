<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediccionesTable extends Migration
{
    public function up()
    {
        Schema::create('predicciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); 
            $table->text('contenido')->nullable(); 
            $table->float('probabilidad'); 
            $table->text('fuente_datos')->nullable(); 
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->date('fecha'); // Fecha de predicción
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('predicciones');
    }
}
