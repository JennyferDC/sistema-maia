<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosObservacionSaludTable extends Migration
{
    public function up()
    {
        Schema::create('registros_observacion_salud', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_observacion'); // Ej: 'fiebre', 'vómito', etc.
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registros_observacion_salud');
    }
}
