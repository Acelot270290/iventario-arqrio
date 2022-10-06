<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoConservacaoObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_conservacao_obras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo_estado_conservacao_obra');
            $table->string('descricao_estado_conservacao_obra')->nullable();
            $table->boolean('is_default_estado_conservacao_obra'); // Se é ou não default (APENAS UM CAMPO)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estado_conservacao_obras');
    }
}
