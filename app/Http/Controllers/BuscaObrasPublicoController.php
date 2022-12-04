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
use Illuminate\Support\Facades\Storage;

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

        // Inicializa a string de retorno
        $controles = "";

        // Temporário para o css, mas vai ser removido para por no .css
        $css = "background-color: #fff; border-radius: 10px; border: none; position: relative; margin-bottom: 30px; margin-left: 2px; margin-right: 2px; box-shadow: 0 0.46875rem 2.1875rem rgb(90 97 105 / 10%), 0 0.9375rem 1.40625rem rgb(90 97 105 / 10%), 0 0.25rem 0.53125rem rgb(90 97 105 / 12%), 0 0.125rem 0.1875rem rgb(90 97 105 / 10%); height: 350px;";

        // Ids de permissões TODO: mover para um local centralizado para evitar hardcoding
        $allowEdit = ['1', '2', '3', '5'];
        $canOnlyView = ['6'];
        $allowDelete = ['1', '2'];

        // Para cada elemento de obras
        foreach($obras as $obra){
            // Para cada elemento em obras monte um card
            $controles .= '<div class="col-lg-4 col-sm-6 mr-3">' .
            '<img  class="img-thumbnail mb-3" src="' . url($obra->foto_frontal_obra) . '" alt="' . $obra->titulo_obra . '">';

            // Checa se o usuário está logado e pode visualizar detalhes
            if(auth()->user('id') && in_array(strval(auth()->user('id')['id_cargo']), array_merge($allowEdit, $canOnlyView)) || $obra->obra_visibilidade == 1) {
                $controles .= '<a href="' . route('detalhar_obras_publico', ['id' => $obra->id]) . '"><h5> ' . $obra->titulo_obra . ' </h5></a>';
            }else{
                $controles .= '<a href="' . route('detalhar_obras_publico', ['id' => $obra->id]) . '"><h5> ' . $obra->titulo_obra . ' </h5></a>';
            }

            $controles .= '<div style="text-align: center; margin-top: 10px;">';

            // Checa se o usuário está logado
            if(auth()->user('id')){

                // Checa se pode editar
                if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {
                $controles .= '<button class="btn btn-primary mr-1" onclick="location.href=\'' . route('editar_obra', ['id' => $obra->id]) . '\'")> Editar </button>';
                }

                // Checa se pode visualizar
                if(in_array(strval(auth()->user('id')['id_cargo']), array_merge($allowEdit, $canOnlyView))) {
                    $controles .=  '<button class="btn btn-success mr-1" onclick="location.href=\'' . route('detalhar_obra', ['id' => $obra->id]) . '\'")> Visualizar </button>';
                }

                // Checa se pode deletar
                if(in_array(strval(auth()->user('id')['id_cargo']), $allowDelete))
                {
                    $controles .= '<button class="btn btn-danger mr-1"> Deletar </button>';
                }
            }

            $controles .= '</div>' .
            '</div>';
        }

        // Retorna o json com os dados
        return response()->json($controles);
    }

    public function detalhar(Request $request, $id){
        // Seleciona os dados de obras para detalhamento (query completa com as devidas associações)
        $obra = Obras::select('obras.id', 'acervo_id', 'nome_acervo', 'obras.created_at', 'obras.updated_at', 'categoria_id', 'titulo_categoria', 'titulo_obra', 'foto_frontal_obra', 'foto_lateral_esquerda_obra', 'foto_lateral_direita_obra', 'foto_posterior_obra', 'foto_superior_obra', 'foto_inferior_obra', 'tesauro_id', 'titulo_tesauro', 'altura_obra', 'largura_obra', 'profundidade_obra', 'comprimento_obra', 'diametro_obra', 'material_id_1', 'm1.titulo_material as titulo_material_1', 'material_id_2', 'm2.titulo_material as titulo_material_2', 'material_id_3', 'm3.titulo_material as titulo_material_3', 'tecnica_id_1', 't1.titulo_tecnica as titulo_tecnica_1', 'tecnica_id_2', 't2.titulo_tecnica as titulo_tecnica_2', 'tecnica_id_3', 't3.titulo_tecnica as titulo_tecnica_3', 'obras.seculo_id', 'titulo_seculo', 'ano_obra', 'autoria_obra', 'procedencia_obra', 'estado_conservacao_obra_id', 'titulo_estado_conservacao_obra', 'checkbox_especificacao_obra', 'condicoes_de_seguranca_obra_id', 'titulo_condicao_seguranca_obra', 'checkbox_especificacao_seguranca_obra','caracteristicas_est_icono_orna_obra', 'observacoes_obra', 'localizacao_obra_id', 'nome_localizacao', 'obras.usuario_insercao_id', 'u1.name as usuario_cadastrante', 'obras.usuario_atualizacao_id', 'u2.name as usuario_revisor', 'obra_provisoria')
            ->where('obras.id', '=', intval($id))
            ->join('acervos as a', 'a.id', '=', 'acervo_id')
            ->join('categorias as c', 'c.id', '=', 'categoria_id')
            ->join('tesauros as te', 'te.id', '=', 'tesauro_id')
            ->leftjoin('materiais as m1', 'm1.id', '=', 'material_id_1')
            ->leftjoin('materiais as m2', 'm2.id', '=', 'material_id_2')
            ->leftjoin('materiais as m3', 'm3.id', '=', 'material_id_3')
            ->leftjoin('tecnicas as t1', 't1.id', '=', 'tecnica_id_1')
            ->leftjoin('tecnicas as t2', 't2.id', '=', 'tecnica_id_2')
            ->leftjoin('tecnicas as t3', 't3.id', '=', 'tecnica_id_3')
            ->join('seculos as s', 's.id', '=', 'obras.seculo_id')
            ->join('estado_conservacao_obras as ec', 'ec.id', '=', 'estado_conservacao_obra_id')
            ->join('condicao_seguranca_obras as cs', 'cs.id', '=', 'condicoes_de_seguranca_obra_id')
            ->join('localizacoes_obras as l', 'l.id', '=', 'localizacao_obra_id')
            ->join('users as u1', 'u1.id', '=', 'obras.usuario_insercao_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'obras.usuario_atualizacao_id')
            ->first();


        // Como as especificações não são chave estrangeira perfeita, o split da string é feita utilizando como separador a ,
        $especificacoes_array = explode(',', $obra->checkbox_especificacao_obra);
        $especificacoes = EspecificacaoObras::find($especificacoes_array);

        // Como as especificações de segurança não são chave estrangeira perfeita, o split da string é feita utilizando como separador a ,
        $especificacoes_seg_array = explode(',', $obra->checkbox_especificacao_seguranca_obra);
        $especificacoesSeg = EspecificacaoSegurancaObras::find($especificacoes_seg_array);

       // var_dump($obra);die();

        // Retorna a visualização de detalhamento de obras com os dados coletados
        return view('web.detalhar_obras_publico', [
            'obra' => $obra,
            'especificacoes' => $especificacoes,
            'especificacoesSeg' => $especificacoesSeg
        ]);
    }
}
