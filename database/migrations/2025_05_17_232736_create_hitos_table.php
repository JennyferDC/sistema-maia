<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHitosTable extends Migration
{
    
    public function up(): void
    {
        Schema::create('hitos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_hito');

            $table->foreignId('etapa_desarrollo_id')->constrained('etapas_desarrollo')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hitos');
    }
};
