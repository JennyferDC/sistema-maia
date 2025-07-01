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
            $table->text('resultado')->nullable(); 
            $table->float('probabilidad'); 
            $table->dateTime('fecha_prediccion');

            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->foreignId('registro_observacion_salud_id')->nullable()->constrained('registros_observacion_salud')->onDelete('set null');
            $table->foreignId('diagnostico_medico_id')->nullable()->constrained('diagnosticos_medicos')->onDelete('set null');

            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('predicciones');
    }
}
