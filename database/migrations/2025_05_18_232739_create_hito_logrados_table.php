<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHitoLogradosTable extends Migration
{
    public function up(): void
    {

        Schema::create('hito_logrados', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_logro');
            $table->foreignId('nino_id')->constrained('ninos')->onDelete('cascade');
            $table->foreignId('hito_id')->constrained('hitos')->onDelete('cascade');
            $table->timestamps();
        });
        
    }
    

    public function down(): void
    {
        Schema::dropIfExists('hito_logrados');
    }

};
