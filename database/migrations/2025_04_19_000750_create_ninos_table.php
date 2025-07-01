<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNinosTable extends Migration
{
    public function up()
    {
        Schema::create('ninos', function (Blueprint $table) {

            $table->id();
            $table->string('nombre');
            $table->boolean('es_prematuro')->default(false);
            $table->integer('semanas_prematuro')->nullable();
            $table->date('fecha_nacimiento');
            $table->string('sexo');
            $table->float('peso');
            $table->float('talla');

            $table->foreignId('madre_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('etapa_desarrollo_id')->nullable()->constrained('etapas_desarrollo')->onDelete('set null');
            
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('ninos');
    }
}