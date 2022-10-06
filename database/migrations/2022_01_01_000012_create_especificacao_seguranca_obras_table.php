<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecificacaoSegurancaObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especificacao_seguranca_obras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo_especificacao_seguranca_obra');
            $table->string('descricao_especificacao_seguranca_obra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especificacao_seguranca_obras');
    }
}
