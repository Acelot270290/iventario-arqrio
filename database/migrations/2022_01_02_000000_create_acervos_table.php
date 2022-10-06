<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcervosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acervos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome_acervo');
            $table->string('cep_acervo', 9);
            $table->string('endereco_acervo', 250);
            $table->string('numero_endereco_acervo', 6)->nullable();
            $table->string('bairro_acervo', 50);
            $table->string('cidade_acervo', 50);
            $table->string('UF_acervo', 2);
            $table->string('descricao_fachada_planta_acervo', 10000);
            $table->string('foto_frontal_acervo', 250)->nullable();// Foto principal (Destaque)
            $table->string('foto_lateral_1_acervo', 250)->nullable();
            $table->string('foto_lateral_2_acervo', 250)->nullable();
            $table->string('foto_posterior_acervo', 250)->nullable();
            $table->string('foto_cobertura_acervo', 250)->nullable();
            $table->string('plantas_situacao_acervo', 250)->nullable();

            // Estado de conservação
            $table->unsignedBigInteger('estado_conservacao_acervo_id'); // Chave estrangeira para estado de conservação do acervo
            $table->foreign('estado_conservacao_acervo_id')->references('id')->on('estado_conservacao_acervos');

            $table->unsignedSmallInteger('ano_construcao_acervo')->nullable();

            // Tombamento
            $table->unsignedBigInteger('tombamento_id'); // Chave estrangeira para tombamento
            $table->foreign('tombamento_id')->references('id')->on('tombamentos');

            // Século
            $table->unsignedBigInteger('seculo_id'); // Chave estrangeira para século
            $table->foreign('seculo_id')->references('id')->on('seculos');

            //$table->unsignedBigInteger('especificacao_acervo_id')->nullable(); // Chave estrangeira para estado de conservação do acervo
            //$table->foreign('especificacao_acervo_id')->references('id')->on('especificacao_acervos');

            // Especificação do acervo
            $table->string('checkbox_especificacao_acervo', 250)->nullable(); // Suporte para n estados de conservação do acervo

            $table->unsignedBigInteger('localizacao_id'); // Chave estrangeira para tombamento
            $table->foreign('localizacao_id')->references('id')->on('localicacoes_obras');

            //Informação do user
            $table->unsignedBigInteger('usuario_insercao_id');
            $table->foreign('usuario_insercao_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_atualizacao_id')->nullable();
            $table->foreign('usuario_atualizacao_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acervos');
    }
}
