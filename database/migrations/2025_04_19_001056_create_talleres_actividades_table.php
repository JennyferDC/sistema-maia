<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalleresActividadesTable extends Migration
{
    public function up()
    {
        Schema::create('talleres_actividades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('tipo_evento');
            $table->text('descripcion');
            $table->dateTime('fecha_evento');
            
            $table->foreignId('nino_id')->nullable()->constrained('ninos')->onDelete('cascade');
            $table->foreignId('madre_id')->nullable()->constrained('users')->onDelete('cascade'); 
            
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('talleres_actividades');
    }
}
