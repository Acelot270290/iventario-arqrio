<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acervos extends Model
{
    use HasFactory;

    public $timestamps = true;


    protected $fillable = ['id','nome_acervo', 'cep_acervo','endereco_acervo','numero_endereco_acervo', 'bairro_acervo', 'cidade_acervo', 'UF_acervo', 'tombamento_id', 'seculo_id', 'ano_construcao_acervo', 'estado_conservacao_acervo_id', 'especificacao_acervo_id', 'descricao_fachada_planta_acervo', 'usuario_insercao_id', 'foto_frontal_acervo','created_at','updated_at' ];
}
