<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionesTable extends Migration
{
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->dateTime('fecha');
            $table->string('estado')->default('pendiente');

            $table->foreignId('madre_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('alerta_id')->nullable()->constrained('alertas')->onDelete('set null');
            $table->foreignId('taller_actividad_id')->nullable()->constrained('talleres_actividades')->onDelete('set null');
    
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('notificaciones');
    }
}