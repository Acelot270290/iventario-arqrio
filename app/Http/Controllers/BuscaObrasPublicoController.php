<?php

namespace App\Http\Controllers;

use App\Models\Acervos;
use App\Models\Obras;
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

class BuscaObrasPublicoController extends Controller
{
    public function index(){
        // Seleciona os dados necessários para o preenchimento dos dados do formulário de criação de obras (checkboxes, select, ...)
        $acervos = Acervos::select('id', 'nome_acervo');

    
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

        return view('web.busca_obra',[
                'acervos' => $acervos,
                'categorias' => $categorias,
                'especificacoes' => $especificacoes,
                'estados' => $estados,
                'localizacoes' => $localizacoes,
                'seculos' => $seculos,
                'tombamentos' => $tombamentos,
                'especificacoesSeg' => $especificacoesSeg,
                'materiais' => $materiais,
                'tecnicas' => $tecnicas,
                'tesauros'=>$tesauros
        ]);
    }

    public function busca(Request $request){
        // Cria uma lista de dados para serem aplicados nos filtros contendo os pares de coluna e valor
        $filtros = array(
            ['categoria_id', $request->input('categoria_obra')],
            ['acervo_id', $request->input('acervo_obra')],
            ['titulo_obra', $request->input('titulo_obra')],
            ['tesauro_id', $request->input('tesauro_obra')],
            ['localizacao_obra_id', $request->input('localizacao_obra')],
            ['procedencia_obra', $request->input('procedencia_obra')],
            ['tombamento_id', $request->input('tombamento_obra')],
            ['seculo_id', $request->input('seculo_obra')],
            ['ano_obra', $request->input('ano_obra')],
            ['estado_conservacao_obra_id', $request->input('estado_de_conservacao_obra')],
            ['autoria_obra', $request->input('autoria_obra')],
            ['material', $request->input('material_obra')],
            ['tecnica', $request->input('tecnica_obra')],
        );

        // Inicializa a lista de campos múltiplos 
        $multiplos = ['material', 'tecnica'];
        $multiplosMateriais = ['material_id_1', 'material_id_2', 'material_id_3'];
        $multiplosTecnicas = ['tecnica_id_1', 'tecnica_id_2', 'tecnica_id_3'];

        // Cria a query para buscar as obras selecionando todas as obras
        $query = Obras::select('obras.*');
        
        // Percorre a lista de filtros e aplica os filtros que não são nulos
        foreach($filtros as $filtro){
            if($filtro[1] != null){
                # Checa se o campo está na lista de campos com múltiplas colunas (por exemplo: materiais e técnicas)
                if(in_array($filtro[0], $multiplos)){
                    # Se estiver, aplica o filtro com and e or aninhados para ambas as colunas
                    $query = $query->where(function($query) use ($filtro, $multiplosMateriais){
                        foreach($multiplosMateriais as $material){
                            $query->orWhere($material, $filtro[1]);
                        }
                    });

                    $query = $query->where(function($query) use ($filtro, $multiplosTecnicas){
                        foreach($multiplosTecnicas as $tecnica){
                            $query->orWhere($tecnica, $filtro[1]);
                        }
                    });
                }else{
                    // Se não estiver, faz a comparação com where normalmente
                    $query->where($filtro[0], $filtro[1]);
                }
            }
        }

        // Pega os dados
        $obras = $query->get();

        // Retorna o json com os dados
        return response()->json($obras);
    }
}
