<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertasTable extends Migration
{
    public function up()
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_alerta'); // Ej: "Retraso moderado", "Peso bajo", "Fiebre persistente"
            $table->text('descripcion')->nullable(); // Detalles sobre la alerta
            $table->boolean('resuelta')->default(false); // Para seguimiento
            $table->dateTime('fecha_generada');

            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->foreignId('registro_observacion_salud_id')->nullable()->constrained('registros_observacion_salud')->onDelete('set null');
            $table->foreignId('prediccion_id')->nullable()->constrained('predicciones')->onDelete('set null');
            
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
}
