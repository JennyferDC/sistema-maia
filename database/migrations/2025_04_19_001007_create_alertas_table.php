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
            $table->string('tipo'); // Ej: "Retraso moderado", "Peso bajo", "Fiebre persistente"
            $table->text('descripcion')->nullable(); // Detalles sobre la alerta
            $table->boolean('resuelta')->default(false); // Para seguimiento
            $table->date('fecha');
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
}
