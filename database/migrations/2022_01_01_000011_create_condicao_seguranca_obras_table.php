<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondicaoSegurancaObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condicao_seguranca_obras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo_condicao_seguranca_obra');
            $table->string('descricao_condicao_seguranca_obra')->nullable();
            $table->boolean('is_default_condicao_seguranca_obra'); // Se é ou não default (APENAS UM CAMPO)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('condicao_seguranca_obras');
    }
}
