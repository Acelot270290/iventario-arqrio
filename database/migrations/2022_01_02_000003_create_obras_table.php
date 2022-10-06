<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('categoria_id'); // Chave estrangeira para categoria
            $table->foreign('categoria_id')->references('id')->on('categorias');

            $table->unsignedBigInteger('acervo_id'); // Chave estrangeira para seculo
            $table->foreign('acervo_id')->references('id')->on('acervos');

            $table->string('titulo_obra'); // Nome da obra

            $table->string('foto_frontal_obra', 250)->nullable();// Foto principal (Destaque)
            $table->string('foto_lateral_esquerda_obra', 250)->nullable();
            $table->string('foto_lateral_direita_obra', 250)->nullable();
            $table->string('foto_posterior_obra', 250)->nullable();
            $table->string('foto_superior_obra', 250)->nullable();
            $table->string('foto_inferior_obra', 250)->nullable();

            $table->unsignedBigInteger('tesauro_id'); // Chave estrangeira para tesauro
            $table->foreign('tesauro_id')->references('id')->on('tesauros');

            //Dimensões (em centímetros sempre)
            $table->decimal('altura_obra')->nullable();
            $table->decimal('largura_obra')->nullable();
            $table->decimal('profundidade_obra')->nullable();
            $table->decimal('comprimento_obra')->nullable();
            $table->decimal('diametro_obra')->nullable();

            // Material (até 3)
            $table->unsignedBigInteger('material_id_1'); // Chave estrangeira para material 1
            $table->unsignedBigInteger('material_id_2'); // Chave estrangeira para material 2
            $table->unsignedBigInteger('material_id_3'); // Chave estrangeira para material 3
            $table->foreign('material_id_1')->references('id')->on('materiais');
            $table->foreign('material_id_2')->references('id')->on('materiais');
            $table->foreign('material_id_3')->references('id')->on('materiais');

            // Tecnica (até 3)
            $table->unsignedBigInteger('tecnica_id_1'); // Chave estrangeira para Tecnica 1
            $table->unsignedBigInteger('tecnica_id_2'); // Chave estrangeira para Tecnica 2
            $table->unsignedBigInteger('tecnica_id_3'); // Chave estrangeira para Tecnica 3
            $table->foreign('tecnica_id_1')->references('id')->on('tecnicas');
            $table->foreign('tecnica_id_2')->references('id')->on('tecnicas');
            $table->foreign('tecnica_id_3')->references('id')->on('tecnicas');

            // Século
            $table->unsignedBigInteger('seculo_id'); // Chave estrangeira para seculo
            $table->foreign('seculo_id')->references('id')->on('seculos');

            $table->unsignedSmallInteger('ano_obra')->nullable();
            $table->string('autoria_obra', 250);
            $table->string('procedencia_obra', 250);

            // Tombamento
            $table->unsignedBigInteger('tombamento_id'); // Chave estrangeira para seculo
            $table->foreign('tombamento_id')->references('id')->on('tombamentos');

            // Estado de conservação da Obra
            $table->unsignedBigInteger('estado_conservacao_obra_id'); // Chave estrangeira para
            $table->foreign('estado_conservacao_obra_id')->references('id')->on('estado_conservacao_obras');

            // Especificação de Obras
            //$table->unsignedBigInteger('especificacao_obra_id'); // Chave estrangeira para 
            //$table->foreign('especificacao_obra_id')->references('id')->on('especificacao_obras');

            $table->string('checkbox_especificacao_obra', 250)->nullable(); // Suporte para n estados de conservação do acervo

            // Segurança
            $table->unsignedBigInteger('condicoes_de_seguranca_obra_id'); // Chave estrangeira para 
            $table->foreign('condicoes_de_seguranca_obra_id')->references('id')->on('condicao_seguranca_obras');
            
            //$table->unsignedBigInteger('especificacao_seguranca_obra_id'); // Chave estrangeira para 
            //$table->foreign('especificacao_seguranca_obra_id')->references('id')->on('especificacao_seguranca_obras');

            $table->string('checkbox_especificacao_seguranca_obra', 250)->nullable(); // Suporte para n estados de conservação do acervo
                        
            // Características estilísticas/iconográficas e ornamentais
            $table->string('caracteristicas_est_icono_orna_obra', 9000)->nullable();

            $table->string('observacoes_obra', 4000)->nullable();

            $table->unsignedBigInteger('usuario_insercao_id');
            $table->foreign('usuario_insercao_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_atualizacao_id')->nullable();
            $table->foreign('usuario_atualizacao_id')->references('id')->on('users');

            // Obra provisoria
            $table->boolval('obra_provisoria')->default(0);

            // Visibilidade
            $table->boolval('obra_visibilidade')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obras');
    }
}
