<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecificacaoAcervosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especificacao_acervos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo_especificacao_acervo');
            $table->string('descricao_especificacao_acervo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especificacao_acervos');
    }
}
