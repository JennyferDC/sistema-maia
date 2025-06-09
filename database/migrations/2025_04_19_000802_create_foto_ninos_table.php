<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotoNinosTable extends Migration
{
    public function up()
    {
        Schema::create('foto_ninos', function (Blueprint $table) {
            $table->id();
            $table->string('ruta_foto');
            $table->string('descripcion')->nullable(); 
            $table->date('fecha')->nullable(); 
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->foreignId('etapa_desarrollo_id')->constrained('etapas_desarrollo')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('foto_ninos');
    }
}

