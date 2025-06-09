<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecomendacionesTable extends Migration
{
    public function up()
    {
        Schema::create('recomendaciones', function (Blueprint $table) {
            $table->id();
            $table->text('contenido');
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');

            $table->foreignId('registro_observacion_salud_id')->nullable()->constrained('registros_observacion_salud')->onDelete('set null');
            $table->foreignId('prediccion_id')->nullable()->constrained('predicciones')->onDelete('set null');
            $table->foreignId('diagnostico_medico_id')->nullable()->constrained('diagnosticos_medicos')->onDelete('set null');
            $table->foreignId('alerta_id')->nullable()->constrained('alertas')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recomendaciones');
    }
}
