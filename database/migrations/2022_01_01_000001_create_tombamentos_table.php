<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTombamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tombamentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo_tombamento');
            $table->string('descricao_tombamento')->nullable();
            $table->boolean('is_default_tombamento'); // Se é ou não default (APENAS UM CAMPO)

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tombamentos');
    }
}
