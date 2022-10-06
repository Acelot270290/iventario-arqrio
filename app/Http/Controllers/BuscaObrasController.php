<?php

namespace App\Http\Controllers;

use App\Models\Acervos;
use App\Models\Categorias;
use App\Models\CondicaoSegurancaObras;
use App\Models\EspecificacaoObras;
use App\Models\EspecificacaoSegurancaObras;
use App\Models\EstadoConservacaoObras;
use App\Models\LocalizacoesObras;
use App\Models\Materiais;
use App\Models\Seculos;
use App\Models\Tecnicas;
use App\Models\Tesauros;
use App\Models\Tombamentos;
use Illuminate\Http\Request;

class BuscaObrasController extends Controller
{
    public function index(){

        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '3', '5', '6'])){
            return view('unauthorized');
        }

        // Seleciona os dados necessários para o preenchimento dos dados do formulário de criação de obras (checkboxes, select, ...)
        $acervos = Acervos::select('id', 'nome_acervo');

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if(!is_null($accesses)){
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (Acesso 0 é acesso a tudo)
            if(strval($accesses[0]) != '0'){
                // Para cada acesso
                foreach($accesses as $access){
                    // Faz o where com operador or para o id da obra
                    $acervos->orwhere('id', '=', $access);
                }
            }
        }else{
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }
        // Pega os dados
        $acervos = $acervos->get();

        // Se não houve nenhum resultado em acervo, o usuário não possui acesso a nenum acervo cadastrado.
        if($acervos === null){
            // Se estiver vazio, o usuário não pode cadastrar nada (não deve existir esse erro).
            return view('unauthorized');
            // TODO: Trocar essa view para "usuario não tem acesso a nenhum acervo existente"
        }

        $categorias = Categorias::select('id', 'titulo_categoria')->get();
        $condicoes = CondicaoSegurancaObras::select('id', 'titulo_condicao_seguranca_obra', 'is_default_condicao_seguranca_obra')->get();
        $especificacoes = EspecificacaoObras::select('id', 'titulo_especificacao_obra')->orderBy('titulo_especificacao_obra', 'ASC')->get();
        $especificacoesSeg = EspecificacaoSegurancaObras::select('id', 'titulo_especificacao_seguranca_obra')->get();
        $estados = EstadoConservacaoObras::select('id', 'titulo_estado_conservacao_obra', 'is_default_estado_conservacao_obra')->get();
        $localizacoes = LocalizacoesObras::select('id', 'nome_localizacao')->orderBy('nome_localizacao', 'ASC')->get();
        $materiais = Materiais::select('id', 'titulo_material')->orderBy('titulo_material', 'ASC')->get();
        $seculos = Seculos::select('id', 'titulo_seculo', 'ano_inicio_seculo', 'ano_fim_seculo', 'is_default_seculo')->get();
        $tecnicas = Tecnicas::select('id', 'titulo_tecnica')->orderBy('titulo_tecnica', 'ASC')->get();
        $tesauros = Tesauros::select('id', 'titulo_tesauro')->orderBy('titulo_tesauro', 'ASC')->get();
        $tombamentos = Tombamentos::select('id', 'titulo_tombamento')->get();

        return view('admin.busca_obra',[
                'acervos' => $acervos,
                'categorias' => $categorias,
                'especificacoes' => $especificacoes,
                'estados' => $estados,
                'localizacoes' => $localizacoes,
                'seculos' => $seculos,
                'tombamentos' => $tombamentos,
                'condicoes' => $condicoes,
                'especificacoesSeg' => $especificacoesSeg,
                'materiais' => $materiais,
                'tecnicas' => $tecnicas,
                'tesauros'=>$tesauros
        ]);
    }
}
