<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acervos;
use App\Models\Categorias;
use App\Models\CondicaoSegurancaObras;
use App\Models\EspecificacaoObras;
use App\Models\EspecificacaoSegurancaObras;
use App\Models\EstadoConservacaoObras;
use App\Models\LocalizacoesObras;
use App\Models\Materiais;
use App\Models\Obras;
use App\Models\Seculos;
use App\Models\Tecnicas;
use App\Models\Tesauros;
use App\Models\Tombamentos;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;

class ObraController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Seleciona os dados de obras para serem dispostas na listagem de obras
        $obras = Obras::select('obras.id', 'titulo_obra', 'tesauro_id', 'titulo_tesauro', 'acervo_id', 'nome_acervo', 'material_id_1', 'm1.titulo_material as titulo_material_1', 'material_id_2', 'm2.titulo_material as titulo_material_2', 'material_id_3', 'm3.titulo_material as titulo_material_3', 'foto_frontal_obra', 'obras.seculo_id', 'titulo_seculo', 'obra_provisoria');

        $user = auth()->user('id_cargo');

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
                    $obras->orwhere('acervo_id', '=', $access);
                }
            }
        }else{
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        $obras = $obras->join('seculos as s', 's.id', '=', 'obras.seculo_id')
            ->join('tesauros as t', 't.id', '=', 'tesauro_id')
            ->join('acervos as a', 'a.id', '=', 'acervo_id')
            ->leftjoin('materiais as m1', 'm1.id', '=', 'material_id_1')
            ->leftjoin('materiais as m2', 'm2.id', '=', 'material_id_2')
            ->leftjoin('materiais as m3', 'm3.id', '=', 'material_id_3')
            ->orderBy('obras.id', 'ASC')
            ->get();

        // Retorna a view de listagem de obras
        return view('admin.obra', [
            'obras' => $obras,
            'user'=>$user
        ]);
    }

    public function criar(Request $request)
    {
        // Revisores não podem criar, vistantes podem VER
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

        // Retorna a view de criação de obras contendo os dados coletados
        return view('admin.criar_obra', [
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

    public function adicionar(Request $request)
    {
        // Revisores e visitantes não podem criar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '3', '5'])){
            return view('unauthorized');
        }

        // Descobre os limites superior e inferior para os anos referente ao século desejado
        $seculo = Seculos::select('ano_inicio_seculo', 'ano_fim_seculo')->where('id', $request->seculo_obra)->first();

        // Valida os campos
        $request->validate([
            'acervo_obra' => 'required|min:1|max:21',
            'categoria_obra'=>'required|min:1|max:21',
            'titulo_obra'=>'required|min:1|max:250',
            'tesauro_obra'=>'required|min:1|max:21',
            'localizacao_obra'=>'required|min:1|max:21',
            'condicao_seguranca_obra'=>'required|min:1|max:21',
            'tombamento_obra'=>'required|min:1|max:21',
            'estado_de_conservacao_obra'=>'required|min:1|max:21',
            'material_1_obra'=>'required|min:1|max:21',
            'tecnica_1_obra'=>'required|min:1|max:21',
            'seculo_obra'=>'required|min:1|max:21',
            'ano_obra' => 'nullable|max:5|gte:' . strval($seculo->ano_inicio_seculo) . '|lte:' . strval($seculo->ano_fim_seculo),
        ]);

        // Descobre qual user que fez a requisição
        $usuario = auth()->user('id');

        // Se existe uma especificação de obra e ela não está vazia
        if(isset($request->especificacao_obra) and !empty($request->especificacao_obra)){
            // Concatena os elementos do array usando como separador uma ,
            $check = implode(',', $request->especificacao_obra);
        } else {
            // Já que não existe dado para especificação de obra, marca como uma string vazia
            $check = '';
        }

        // Se existe uma especificação de segurança de obra e ela não está vazia
        if(isset($request->especificacao_seg_obra) and !empty($request->especificacao_seg_obra)){
            // Concatena os elementos do array usando como separador uma ,
            $checkSeg = implode(',', $request->especificacao_seg_obra);
        } else {
            // Já que não existe dado para especificação de segurança de obra, marca como uma string vazia
            $checkSeg = '';
        }

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if(!is_null($accesses)){
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);
        }else{
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        if(!in_array('0', $accesses) and !in_array(strval($request->acervo_obra) , $accesses)){
            // Se não estiver no array, o usuário não pode inserir nesse acervo
            return view('unauthorized');
        }

        // Insere os dados em obras e retorna o id do elemento inserido
        $obraId = Obras::insertGetId([
            'id' => $request->id,
            'acervo_id'=> $request->acervo_obra,
            'created_at'=> new \DateTime(),
            'usuario_insercao_id'=> $usuario->id,
            'categoria_id' => $request->categoria_obra,
            'titulo_obra' => $request->titulo_obra,
            'altura_obra' => $request->altura_obra,
            'largura_obra' => $request->largura_obra,
            'profundidade_obra' => $request->profundidade_obra,
            'comprimento_obra' => $request->comprimento_obra,
            'diametro_obra' => $request->diâmetro_obra,
            'tesauro_id' => $request->tesauro_obra,
            'localizacao_obra_id' => $request->localizacao_obra,
            'condicoes_de_seguranca_obra_id' => $request->condicao_seguranca_obra,
            'procedencia_obra' => $request->procedencia_obra,
            'tombamento_id' => $request->tombamento_obra,
            'seculo_id' => $request->seculo_obra,
            'ano_obra'=> $request->ano_obra,
            'autoria_obra'=> $request->autoria_obra,
            'estado_conservacao_obra_id'=> $request->estado_de_conservacao_obra,
            'material_id_1'=> $request->material_1_obra,
            'material_id_2'=> $request->material_2_obra,
            'material_id_3'=> $request->material_3_obra,
            'tecnica_id_1'=> $request->tecnica_1_obra,
            'tecnica_id_2'=> $request->tecnica_2_obra,
            'tecnica_id_3'=> $request->tecnica_3_obra,
            'checkbox_especificacao_obra' => $check,
            'checkbox_especificacao_seguranca_obra' => $checkSeg,
            'caracteristicas_est_icono_orna_obra'=> $request->caracteristicas_estilisticas_obra,
            'observacoes_obra'=> $request->observacoes_obra,
            'obra_provisoria' => isset($request->obra_provisoria) ? 1 : 0,
        ]);

        $repeat = [];

        if($request->repete_obra == 1){ // ver checkbox
            $repeat['acervo_id'] = $request->acervo_obra;
            $repeat['categoria_id'] = $request->categoria_obra;
            $repeat['altura_obra'] = $request->altura_obra;
            $repeat['largura_obra'] = $request->largura_obra;
            $repeat['profundidade_obra'] = $request->profundidade_obra;
            $repeat['comprimento_obra'] = $request->comprimento_obra;
            $repeat['diametro_obra'] = $request->diametro_obra;
            $repeat['tesauro_id'] = $request->tesauro_obra;
            $repeat['localizacao_obra_id'] = $request->localizacao_obra;
            $repeat['condicoes_de_seguranca_obra_id'] = $request->condicao_seguranca_obra;
            $repeat['procedencia_obra'] = $request->procedencia_obra;
            $repeat['tombamento_id'] = $request->tombamento_obra;
            $repeat['seculo_id'] = $request->seculo_obra;
            $repeat['ano_obra'] = $request->ano_obra;
            $repeat['autoria_obra'] = $request->autoria_obra;
            $repeat['estado_conservacao_obra_id'] = $request->estado_de_conservacao_obra;
            $repeat['material_id_1'] = $request->material_1_obra;
            $repeat['material_id_2'] = $request->material_2_obra;
            $repeat['material_id_3'] = $request->material_3_obra;
            $repeat['tecnica_id_1'] = $request->tecnica_1_obra;
            $repeat['tecnica_id_2'] = $request->tecnica_2_obra;
            $repeat['tecnica_id_3'] = $request->tecnica_3_obra;
            $repeat['checkbox_especificacao_obra'] = explode(',', $check);
            $repeat['checkbox_especificacao_seguranca_obra'] = explode(',', $checkSeg);
            $repeat['caracteristicas_est_icono_orna_obra'] = $request->caracteristicas_estilisticas_obra;
            $repeat['observacoes_obra'] = $request->observacoes_obra;
            $repeat['obra_provisoria'] = isset($request->obra_provisoria) ? "1" : "0";
        }

        /* Parametrização do caminho onde as imagens ficam. */
        // Nome do primeiro folder
        $preBasePath =  'imagem';
        // Nome do segundo folder
        $basePath =  $preBasePath . '/obras';

        // Se o primeiro folder não existir
        if (! Storage::exists($preBasePath)) {
            // Ele será criado
            Storage::makeDirectory(public_path($preBasePath, 0755, true));
            // E o subfolder também (se o pré não existe, seus filhos também não existem)
            Storage::makeDirectory(public_path($basePath,0755, true));
        }else if (!is_dir($basePath)) {
            // Se não existir, cria ele
            mkdir(public_path($basePath));
        }

        /* Tratamento de dados para quando o folder de imagem do id a ser inserido já existe (não deve ser executado nunca, mas por precaução...) */
        // Parametrização do nome da pasta onde as imagens vão ficar
        $imagemaobra =  $basePath . '/' . $obraId;
        if (is_dir($imagemaobra)) {
            // Deleta tudo dentro dela
            array_map('unlink', glob(public_path($imagemaobra) . "/*.*"));
            // Remoção e recriação comentadas, mas deixadas aqui pra caso de algum problema já ter uma sugestão de solução
            //rmdir(public_path($imagemaobra));
            //mkdir(public_path($imagemaobra));
        } else {
            // Já que ela não existe, cria
            mkdir(public_path($imagemaobra));
        }

        /* Tratamento para inserção de fotos submetidas */
        // Se houver alguma foto submetida na requisição (útil pra evitar processamento desnecessário)
        if($request->hasFile('foto_frontal_obra') or
           $request->hasFile('foto_lateral_esquerda_obra') or
           $request->hasFile('foto_lateral_direita_obra') or
           $request->hasFile('foto_posterior_obra') or
           $request->hasFile('foto_superior_obra') or
           $request->hasFile('foto_inferior_obra')){

            // Descobre qual é a obra que acabou de ser inserida
            $insereObra = Obras::find($obraId);
            // Torna a inserção de timestamp como false (caso contrário a coluna UpdatedAt ganha um valor)
            $insereObra->timestamps = false;

            // Se houver foto frontal
            if ($request->file('foto_frontal_obra')) {
                // Seta o nome da imagem como frontal
                $imageName = 'Frontal_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_frontal_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                
                // Seta a coluna foto_frontal_obra como o caminho de onde a imagem está salva
                $insereObra->foto_frontal_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto lateral esquerda
            if ($request->file('foto_lateral_esquerda_obra')) {
                // Seta o nome da imagem como lateral esquerda
                $imageName = 'Lateral_esquerda_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_esquerda_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_lateral_esquerda_obra como o caminho de onde a imagem está salva
                $insereObra->foto_lateral_esquerda_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto lateral direita
            if ($request->file('foto_lateral_direita_obra')) {
                // Seta o nome da imagem como lateral direita
                $imageName = 'foto_lateral_direita_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_direita_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_lateral_direita_obra como o caminho de onde a imagem está salva
                $insereObra->foto_lateral_direita_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto posterior
            if ($request->file('foto_posterior_obra')) {
                // Seta o nome da imagem como posterior
                $imageName = 'Posterior_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_posterior_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_obra como o caminho de onde a imagem está salva
                $insereObra->foto_posterior_obra =$imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto superior
            if ($request->file('foto_superior_obra')) {
                // Seta o nome da imagem como superior
                $imageName = 'Superior_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_superior_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_obra como o caminho de onde a imagem está salva
                $insereObra->foto_superior_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto inferior
            if ($request->file('foto_inferior_obra')) {
                // Seta o nome da imagem como inferior
                $imageName = 'Inferior_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_inferior_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_obra como o caminho de onde a imagem está salva
                $insereObra->foto_inferior_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }
            // Salva as alterações feitas (evitando o timestamp)
            $insereObra->save();
        }

        // Se houver um id, é sinal de que o cadastro foi feito com sucesso (não contempla as atualizações para inserção das imagens)
        if ($obraId) {
            // Seta a mensagem de sucesso e o tipo de resposta como sucesso (classe bootstrap)
            $alertMsg = 'Obra cadastrada com sucesso!';
            $alertType = 'success';
        } else {
            // Seta a mensagem de falha e o tipo de resposta como perigo (classe bootstrap)
            $alertMsg = 'Falha ao cadastrar a obra!';
            $alertType = 'danger';
        }
        //print_r($repeat); die();

        // Redireciona para a url de criação de obra passando o alerta de mensagem e o tipo de alerta
        return redirect('/obra/criar')->with('repeat', $repeat)->with('alert_message', $alertMsg)->with('alert_type', $alertType);
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

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if(!is_null($accesses)){
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (ilimitado) ou não estiver na lista
            if(!in_array('0', $accesses) and !in_array(strval($obra['acervo_id']), $accesses)){
                // ele não é autorizado
                return view('unauthorized');
            }
        }else{
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Como as especificações não são chave estrangeira perfeita, o split da string é feita utilizando como separador a ,
        $especificacoes_array = explode(',', $obra->checkbox_especificacao_obra);
        $especificacoes = EspecificacaoObras::find($especificacoes_array);

        // Como as especificações de segurança não são chave estrangeira perfeita, o split da string é feita utilizando como separador a ,
        $especificacoes_seg_array = explode(',', $obra->checkbox_especificacao_seguranca_obra);
        $especificacoesSeg = EspecificacaoSegurancaObras::find($especificacoes_seg_array);

        // Retorna a visualização de detalhamento de obras com os dados coletados
        return view('admin.detalhar_obra', [
            'obra' => $obra,
            'especificacoes' => $especificacoes,
            'especificacoesSeg' => $especificacoesSeg
        ]);
    }

    public function editar(Request $request, $id){
        // Catalogadores não podem editar, visitantes podem VER
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '4', '5', '6'])){
            return view('unauthorized');
        }

        // Seleciona os dados de obras para edição
        $obra = Obras::select('obras.id', 'acervo_id', 'obras.created_at as criado_em', 'categoria_id', 'titulo_obra', 'foto_frontal_obra', 'foto_lateral_esquerda_obra', 'foto_lateral_direita_obra', 'foto_posterior_obra', 'foto_superior_obra', 'foto_inferior_obra', 'tesauro_id', 'altura_obra', 'largura_obra', 'profundidade_obra', 'comprimento_obra',  'diametro_obra',  'material_id_1',  'material_id_2',  'material_id_3',  'tecnica_id_1',  'tecnica_id_2',  'tecnica_id_3', 'seculo_id', 'ano_obra', 'autoria_obra', 'procedencia_obra', 'tombamento_id', 'estado_conservacao_obra_id', 'checkbox_especificacao_obra', 'condicoes_de_seguranca_obra_id', 'checkbox_especificacao_seguranca_obra', 'caracteristicas_est_icono_orna_obra', 'observacoes_obra', 'localizacao_obra_id','obras.usuario_insercao_id', 'name as usuario_cadastrante', 'obra_provisoria')
            ->where('obras.id', '=', intval($id))
            ->join('users as u1', 'u1.id', '=', 'obras.usuario_insercao_id')
            ->first();

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if(!is_null($accesses)){
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (ilimitado) ou não estiver na lista
            if(!in_array('0', $accesses) and !in_array(strval($obra['acervo_id']), $accesses)){
                // ele não é autorizado
                return view('unauthorized');
            }
        }else{
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Converte para inteiro todos os valores contidos nos arrays gerados pela separações das strings checkbox_especificacao_obra e checkbox_especificacao_seguranca_obra com o separador ,
        $check = array_map('intval', explode(',', $obra->checkbox_especificacao_obra));
        $checkSeg = array_map('intval', explode(',', $obra->checkbox_especificacao_seguranca_obra));

        // Seleciona os dados necessários para preencher os valores da lista
        $acervos = Acervos::select('id', 'nome_acervo')->get();
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

        // Chama a view de edição de obras
        return view('admin.editar_obra', [
            'obra' => $obra,
            'acervos' => $acervos,
            'categorias' => $categorias,
            'especificacoes' => $especificacoes,
            'check' => $check,
            'estados' => $estados,
            'localizacoes' => $localizacoes,
            'seculos' => $seculos,
            'tombamentos' => $tombamentos,
            'condicoes' => $condicoes,
            'especificacoesSeg' => $especificacoesSeg,
            'checkSeg' => $checkSeg,
            'materiais' => $materiais,
            'tecnicas' => $tecnicas,
            'tesauros' => $tesauros
        ]);
    }

    public function atualizar(Request $request, $id)
    {
        // Catalogadores e visitantes não podem editar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '4', '5'])){
            return view('unauthorized');
        }

        // Descobre quais anos são os limites do século escolhido
        $obra = Obras::select('seculo_id')->where('id', $id)->first();
        $seculo = Seculos::select('ano_inicio_seculo', 'ano_fim_seculo')->where('id', $obra['seculo_id'])->first();

        // Valida os dados
        $request->validate([
            'acervo_obra' => 'required|min:1|max:21',
            'categoria_obra'=>'required|min:1|max:21',
            'titulo_obra'=>'required|min:1|max:250',
            'tesauro_obra'=>'required|min:1|max:21',
            'localizacao_obra'=>'required|min:1|max:21',
            'condicao_seguranca_obra'=>'required|min:1|max:21',
            'tombamento_obra'=>'required|min:1|max:21',
            'estado_de_conservacao_obra'=>'required|min:1|max:21',
            'material_1_obra'=>'required|min:1|max:21',
            'tecnica_1_obra'=>'required|min:1|max:21',
            'seculo_obra'=>'required|min:1|max:21',
            'ano_obra' => 'nullable|max:5|gte:' . strval($seculo->ano_inicio_seculo) . '|lte:' . strval($seculo->ano_fim_seculo),
        ]);

        // Descobre qual user que fez a requisição
        $usuario = auth()->user('id');

        try{
            // Se existe uma especificação de obra e ela não está vazia
            if(isset($request->especificacao_obra) and !empty($request->especificacao_obra)){
                // Concatena os elementos do array usando como separador uma ,
                $check = implode(',', $request->especificacao_obra);
            } else {
                // Já que não existe dado para especificação de acervo, marca como uma string vazia
                $check = '';
            }

            // Se existe uma especificação de segurança de obra e ela não está vazia
            if(isset($request->especificacao_seg_obra) and !empty($request->especificacao_seg_obra)){
                // Concatena os elementos do array usando como separador uma ,
                $checkSeg = implode(',', $request->especificacao_seg_obra);
            } else {
                // Já que não existe dado para especificação de segurança de obra, marca como uma string vazia
                $checkSeg = '';
            }

            // Descobre quais acervos que o usuário tem acesso
            $accesses = auth()->user('id')['acesso_acervos'];

            // Se o acesso não for nulo
            if(!is_null($accesses)){
                // Faz o split do acesso usando vírgulas
                $accesses = explode(',', $accesses);
            }else{
                // Acesso nulo é sem acesso a nada
                return view('unauthorized');
            }

            if(!in_array('0', $accesses) and !in_array(strval($request->acervo_obra) , $accesses)){
                // Se não estiver no array, o usuário não pode inserir nesse acervo
                return view('unauthorized');
            }

            // Edita a obra que possui o id igual ao id passado na url
            $atualizaObra = Obras::where('id', '=', $id)
                ->update([
                'acervo_id' => $request->acervo_obra,
                'updated_at' => new \DateTime(),
                'usuario_atualizacao_id'=> $usuario->id,
                'categoria_id' => $request->categoria_obra,
                'titulo_obra' => $request->titulo_obra,
                'altura_obra' => $request->altura_obra,
                'largura_obra' => $request->largura_obra,
                'profundidade_obra' => $request->profundidade_obra,
                'comprimento_obra' => $request->comprimento_obra,
                'diametro_obra' => $request->diametro_obra,
                'tesauro_id' => $request->tesauro_obra,
                'localizacao_obra_id' => $request->localizacao_obra,
                'condicoes_de_seguranca_obra_id' => $request->condicao_seguranca_obra,
                'procedencia_obra' => $request->procedencia_obra,
                'tombamento_id' => $request->tombamento_obra,
                'seculo_id' => $request->seculo_obra,
                'ano_obra'=> $request->ano_obra,
                'autoria_obra'=> $request->autoria_obra,
                'estado_conservacao_obra_id'=> $request->estado_de_conservacao_obra,
                'material_id_1'=> $request->material_1_obra,
                'material_id_2'=> $request->material_2_obra,
                'material_id_3'=> $request->material_3_obra,
                'tecnica_id_1'=> $request->tecnica_1_obra,
                'tecnica_id_2'=> $request->tecnica_2_obra,
                'tecnica_id_3'=> $request->tecnica_3_obra,
                'checkbox_especificacao_obra'=> $check,
                'checkbox_especificacao_seguranca_obra'=> $checkSeg,
                'caracteristicas_est_icono_orna_obra'=> $request->caracteristicas_estilisticas_obra,
                'observacoes_obra'=> $request->observacoes_obra,
                'obra_provisoria' => isset($request->obra_provisoria) ? 1 : 0,
            ]);
            // Seta flag de sucesso
            $isSuccess = true;
        }catch(Exception $e){
            // Seta flag de falha
            $isSuccess = false;
        }

        /* Parametrização do caminho onde as imagens ficam. */
        // Nome do primeiro folder
        $preBasePath =  'imagem';
        // Nome do segundo folder
        $basePath =  $preBasePath . '/obras';


        // Se o primeiro folder não existir (é pra sempre existirem, mas, mais uma vez, checagem de segurança)
        if (!Storage::exists($preBasePath)) {
            // Ele será criado
            Storage::makeDirectory(public_path($preBasePath, 0755, true));
            // E o subfolder também (se o pré não existe, seus filhos também não existem)
            Storage::makeDirectory(public_path($basePath,0755, true));
        }else if (!is_dir($basePath)) {
            // Se não existir, cria ele
            mkdir(public_path($basePath));
        }

        /* Tratamento de dados para quando o folder de imagem do id a ser inserido já existe (não deve ser executado nunca, mas por precaução...) */
        // Parametrização do nome da pasta onde as imagens vão ficar
        $imagemaobra =  $basePath . '/' . $id;

        // Se a pasta não existir
        if (!Storage::exists($imagemaobra)) {
            // Já que ela não existe, cria
            Storage::makeDirectory(public_path($imagemaobra,0755, true));
        }

        /* Tratamento para inserção de fotos submetidas */
        // Se houver alguma foto submetida na requisição (útil pra evitar processamento desnecessário)
        if($request->hasFile('foto_frontal_obra') or
           $request->hasFile('foto_lateral_esquerda_obra') or
           $request->hasFile('foto_lateral_direita_obra') or
           $request->hasFile('foto_posterior_obra') or
           $request->hasFile('foto_superior_obra') or
           $request->hasFile('foto_inferior_obra')){

            // Descobre qual é a obra que acabou de ser inserida
            $atualizaObra = Obras::find($id);
            // Torna a inserção de timestamp como false (caso contrário a coluna UpdatedAt ganha um valor)
            $atualizaObra->timestamps = false;

            // Se houver foto frontal
            if ($request->file('foto_frontal_obra')) {
                // Seta o nome da imagem como frontal
                $imageName = 'Frontal_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_frontal_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                
                // Seta a coluna foto_frontal_obra como o caminho de onde a imagem está salva
                $atualizaObra->foto_frontal_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto lateral esquerda
            if ($request->file('foto_lateral_esquerda_obra')) {
                // Seta o nome da imagem como lateral esquerda
                $imageName = 'Lateral_esquerda_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_esquerda_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_lateral_esquerda_obra como o caminho de onde a imagem está salva
                $atualizaObra->foto_lateral_esquerda_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto lateral direita
            if ($request->file('foto_lateral_direita_obra')) {
                // Seta o nome da imagem como lateral direita
                $imageName = 'foto_lateral_direita_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_direita_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_lateral_direita_obra como o caminho de onde a imagem está salva
                $atualizaObra->foto_lateral_direita_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto posterior
            if ($request->file('foto_posterior_obra')) {
                // Seta o nome da imagem como posterior
                $imageName = 'Posterior_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_posterior_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_obra como o caminho de onde a imagem está salva
                $atualizaObra->foto_posterior_obra =$imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto superior
            if ($request->file('foto_superior_obra')) {
                // Seta o nome da imagem como superior
                $imageName = 'Superior_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_superior_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_obra como o caminho de onde a imagem está salva
                $atualizaObra->foto_superior_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }

            // Se houver foto inferior
            if ($request->file('foto_inferior_obra')) {
                // Seta o nome da imagem como inferior
                $imageName = 'Inferior_obra.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_inferior_obra)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemaobra) . '/' . $imageName)->encode('webp', 90);
                $imgfile = fopen(public_path($imagemaobra) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemaobra) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_obra como o caminho de onde a imagem está salva
                $atualizaObra->foto_inferior_obra = $imagemaobra . '/' . $imageName.'?x=' . $md5;
            }
            // Salva as alterações feitas (evitando o timestamp)
            $atualizaObra->save();
        }

        // Se houver um id, é sinal de que o cadastro foi feito com sucesso (não contempla as atualizações para inserção das imagens)
        if ($isSuccess) {
            // Seta a mensagem de sucesso e o tipo de resposta como sucesso (classe bootstrap)
            $alertMsg = 'Obra atualizada com sucesso!';
            $alertType = 'success';
        } else {
            // Seta a mensagem de falha e o tipo de resposta como perigo (classe bootstrap)
            $alertMsg = 'Falha ao atualizada a obra!';
            $alertType = 'danger';
        }

        // Redireciona para a url de edição de obra passando o alerta de mensagem e o tipo de alerta
        return redirect('/obra/editar/' . $request->id)->with('alert_message', $alertMsg)->with('alert_type', $alertType);
    }

    public function deletar(Request $request, $id){
        // Somente TI ou administradores podem deletar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2'])){
            return response()->json(['status' => 'error', 'msg' => 'Você não tem acesso para deletar esta obra.']);
        }

        try{
            // Descobre qual é a obra a ser deletada
            $acervoId = Obras::select('acervo_id')->where('id', '=', $id)->first()['acervo_id'];
        }catch(Exception $e){
            return response()->json(['status' => 'error', 'msg' => 'Registro inexistente. Talvez ele já tenha sido deletado.']);
        }

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if(!is_null($accesses)){
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);
        }else{
            // Acesso nulo é sem acesso a nada
            return response()->json(['status' => 'error', 'msg' => 'Você não tem acesso para deletar esta obra.']);
        }

        if(!in_array('0', $accesses) and !in_array(strval($acervoId) , $accesses)){
            // Se não estiver no array, o usuário não pode deletar essa obra porque não pertence ao acervo que ela tem acesso
            return response()->json(['status' => 'error', 'msg' => 'Você não tem acesso para deletar esta obra.']);
        }

        // Deleta a obra
        $obra = Obras::select()->where('id', '=', $id)->delete();
        //var_dump($obra);die;

        try{
            /* Parametrização do caminho onde as imagens ficam. */
            // Nome do primeiro folder
            $preBasePath =  'imagem';
            // Nome do segundo folder
            $basePath =  $preBasePath . '/obras';

            // Parametrização do nome da pasta onde as imagens estão
            $imagemaobra =  $basePath . '/' . $id;

            // Se a pasta existir
            if (is_dir($imagemaobra)) {
                // Delete o seu conteúdo
                array_map('unlink', glob(public_path($imagemaobra) . "/*.*"));
                // Apague a pasta
                rmdir(public_path($imagemaobra));
            }
            // Se existir um elemento obra (sinal de que foi deletada com sucesso)
            if ($obra) {
                // Retorne sucesso
                return response()->json(['status' => 'success', 'msg' => 'Obra deletada.']);
            } else { // caso contrário
                // Retorne falha
                return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar a obra.']);
            }
        }catch(Exception $e){ // Se houver qualquer falha
            // Retorne falha
            return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar a obra.']);
        }
        // Erro desconhecido (não é para acontecer)
        return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar a obra. Erro DESCONHECIDO']);;
    }
}
