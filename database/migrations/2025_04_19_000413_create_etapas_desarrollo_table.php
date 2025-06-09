<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapasDesarrolloTable extends Migration
{
    public function up()
    {
        Schema::create('etapas_desarrollo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_etapa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etapas_desarrollo');
    }
}
