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
            $table->text('descripcion');
            $table->date('fecha');
            $table->string('ubicacion')->nullable();
            $table->foreignId('madre_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('talleres_actividades');
    }
}
