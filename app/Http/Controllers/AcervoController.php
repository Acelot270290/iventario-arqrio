<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Acervos;
use App\Models\EspecificacaoAcervos;
use App\Models\EstadoConservacaoAcervos;
use App\Models\Obras;
use App\Models\Seculos;
use App\Models\Tombamentos;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Image;

class AcervoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Somente usuários logados podem acessar esse controller
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Seleciona os dados de acervos para serem mostrados na listagem de acervos
        $acervos = Acervos::select('acervos.id', 'nome_acervo', 'cidade_acervo', 'UF_acervo', 'foto_frontal_acervo', 'seculo_id', 'titulo_seculo','acervos.created_at', 'ano_construcao_acervo');

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if (!is_null($accesses)) {
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (Acesso 0 é acesso a tudo)
            if (strval($accesses[0]) != '0') {
                // Para cada acesso
                foreach ($accesses as $access) {
                    // Faz o where com operador or para o id da obra
                    $acervos->orwhere('acervos.id', '=', $access);
                }
            }
        } else {
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        $acervos = $acervos->join('seculos as s', 's.id', '=', 'seculo_id')
            ->orderBy('acervos.id', 'ASC')
            ->get();

        return view('admin.acervo', [
            'acervos' => $acervos
        ]);
    }

    public function criar(Request $request)
    {
        // Revisores não podem criar, visitantes podem VER
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '3', '5', '6'])){
            return view('unauthorized');
        }

        // Seleciona as especificações, estados, séculos e tombamentos para preencher os dados de uma ficha em branco (checkboxes, select, ...)
        $especificacoes = EspecificacaoAcervos::select('id', 'titulo_especificacao_acervo')->orderBy('titulo_especificacao_acervo', 'ASC')->get();
        $estados = EstadoConservacaoAcervos::select('id', 'titulo_estado_conservacao_acervo', 'is_default_estado_conservacao_acervo')->get();
        $seculos = Seculos::select('id', 'titulo_seculo', 'ano_inicio_seculo', 'ano_fim_seculo', 'is_default_seculo')->get();
        $tombamentos = Tombamentos::select('id', 'titulo_tombamento', 'is_default_tombamento')->get();

        // Chama a criação de acervo
        return view('admin.criar_acervo', [
            'especificacoes' => $especificacoes,
            'estados' => $estados,
            'seculos' => $seculos,
            'tombamentos' => $tombamentos
        ]);
    }

    public function adicionar(Request $request)
    {
        // Revisores e visitantes não podem criar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '3', '5'])){
            return view('unauthorized');
        }

        // Descobre quais anos são os limites do século escolhido
        $seculo = Seculos::select('ano_inicio_seculo', 'ano_fim_seculo')->where('id', $request->seculo_acervo)->first();

        // Valida os dados
        $request->validate([
            'nome_acervo' => 'required|min:2|max:191',
            'cep_acervo' => 'required|min:1|max:9',
            'endereco_acervo' => 'required|min:1|max:250',
            'numero_endereco_acervo' => 'max:6',
            'bairro_acervo' => 'required|min:1|max:50',
            'cidade_acervo' => 'required|min:1|max:50',
            'UF_acervo' => 'required|min:2|max:2',
            'tombamento_acervo' => 'required|min:1|max:21',
            'seculo_acervo' => 'required|min:1|max:21',
            'ano_acervo' => 'nullable|max:5|gte:' . strval($seculo->ano_inicio_seculo) . '|lte:' . strval($seculo->ano_fim_seculo),
            'estado_conservacao_acervo' => 'required|min:1|max:21',
            'descricao_acervo' => 'required|max:10000',
        ]);

        // Descobre qual user que fez a requisição
        $usuario = auth()->user('id');

        // Se existe uma especificação de acervo e ela não está vazia
        if (isset($request->especificacao_acervo) and !empty($request->especificacao_acervo)) {
            // Concatena os elementos do array usando como separador uma ,
            $check = implode(',', $request->especificacao_acervo);
        } else {
            // Já que não existe dado para especificação de acervo, marca como uma string vazia
            $check = '';
        }
        $object = new Acervos();
        // Insere os dados em acervos e retorna o id do elemento inserido
        $timestamp = $object->freshTimestampString();

        $acervoId = Acervos::insertGetId([
            'id' => $request->id,
            'created_at'=>$timestamp,
            'nome_acervo' => $request->nome_acervo,
            'cep_acervo' => $request->cep_acervo,
            'endereco_acervo' => $request->endereco_acervo,
            'numero_endereco_acervo' => $request->numero_endereco_acervo,
            'bairro_acervo' => $request->bairro_acervo,
            'cidade_acervo' => $request->cidade_acervo,
            'UF_acervo' => $request->UF_acervo,
            'tombamento_id' => $request->tombamento_acervo,
            'seculo_id' => $request->seculo_acervo,
            'ano_construcao_acervo' => $request->ano_acervo,
            'estado_conservacao_acervo_id' => $request->estado_conservacao_acervo,
            'checkbox_especificacao_acervo' => $check,
            'descricao_fachada_planta_acervo' => $request->descricao_acervo,
            'usuario_insercao_id' => $usuario->id,
        ]);

        /* Parametrização do caminho onde as imagens ficam. */
        // Nome do primeiro folder
        $preBasePath =  'imagem';
        // Nome do segundo folder
        $basePath =  $preBasePath . '/acervos';

        // Se o primeiro folder não existir
        if (!Storage::exists($preBasePath)) {
            // Ele será criado
            Storage::makeDirectory(public_path($preBasePath, 0755, true));
            // E o subfolder também (se o pré não existe, seus filhos também não existem)
            Storage::makeDirectory(public_path($basePath,0755, true));
        } else if (!is_dir($basePath)) { // Caso o primeiro folder exista, checa se o segundo não existe
            // Se não existir, cria ele
            mkdir(public_path($basePath));
        }

        /* Tratamento de dados para quando o folder de imagem do id a ser inserido já existe (não deve ser executado nunca, mas por precaução...) */
        // Parametrização do nome da pasta onde as imagens vão ficar
        $imagemacervo =  $basePath . '/' . $acervoId;

        // Se a pasta já existir
        if (is_dir($imagemacervo)) {
            // Deleta tudo dentro dela
            array_map('unlink', glob(public_path($imagemacervo) . "/*.*"));
            // Remoção e recriação comentadas, mas deixadas aqui pra caso de algum problema já ter uma sugestão de solução
            //rmdir(public_path($imagemacervo));
            //mkdir(public_path($imagemacervo));
        } else {
            // Já que ela não existe, cria
            mkdir(public_path($imagemacervo));
        }

        /* Tratamento para inserção de fotos submetidas */
        // Se houver alguma foto submetida na requisição (útil pra evitar processamento desnecessário)
        if (
            $request->hasFile('foto_frontal_acervo') or
            $request->hasFile('foto_lateral_1_acervo') or
            $request->hasFile('foto_lateral_2_acervo') or
            $request->hasFile('foto_posterior_acervo') or
            $request->hasFile('foto_cobertura_acervo') or
            $request->hasFile('plantas_situacao_acervo')
        ) {

            // Descobre qual é o acervo que acabou de ser inserido
            $insereAcervo = Acervos::find($acervoId);
            // Torna a inserção de timestamp como false (caso contrário a coluna UpdatedAt ganha um valor)
            $insereAcervo->timestamps = false;

            // Se houver foto frontal
            if ($request->file('foto_frontal_acervo')) {
                // Seta o nome da imagem como frontal
                $imageName = 'Frontal_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_frontal_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);

                // Seta a coluna foto_frontal_acervo como o caminho de onde a imagem está salva
                $insereAcervo->foto_frontal_acervo = $imagemacervo . '/' . $imageName;
            }

            // Se houver foto lateral esquerda
            if ($request->file('foto_lateral_1_acervo')) {
                // Seta o nome da imagem como lateral esquerda
                $imageName = 'Lateral_Esquerda_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_1_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);

                // Seta a coluna foto_lateral_1_acervo como o caminho de onde a imagem está salva
                $insereAcervo->foto_lateral_1_acervo = $imagemacervo . '/' . $imageName;
            }

            // Se houver foto lateral direita
            if ($request->file('foto_lateral_2_acervo')) {
                // Seta o nome da imagem como lateral direita
                $imageName = 'Lateral_Direita_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_2_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Seta a coluna foto_lateral_2_acervo como o caminho de onde a imagem está salva
                $insereAcervo->foto_lateral_2_acervo = $imagemacervo . '/' . $imageName;
            }

            // Se houver foto posterior
            if ($request->file('foto_posterior_acervo')) {
                // Seta o nome da imagem como posterior
                $imageName = 'Posterior_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_posterior_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Seta a coluna foto_posterior_acervo como o caminho de onde a imagem está salva
                $insereAcervo->foto_posterior_acervo = $imagemacervo . '/' . $imageName;
            }

            // Se houver foto cobetura
            if ($request->file('foto_cobertura_acervo')) {
                // Seta o nome da imagem como cobetura
                $imageName = 'Cobertura_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_cobertura_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Seta a coluna foto_cobertura_acervo como o caminho de onde a imagem está salva
                $insereAcervo->foto_cobertura_acervo = $imagemacervo . '/' . $imageName;
            }

            // Se houver foto situação
            if ($request->file('plantas_situacao_acervo')) {
                // Seta o nome da imagem como situação
                $imageName = 'Plantas_Situacao_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->plantas_situacao_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Seta a coluna foto_cobertura_acervo como o caminho de onde a imagem está salva
                $insereAcervo->plantas_situacao_acervo = $imagemacervo . '/' . $imageName;
            }
            // Salva as alterações feitas (evitando o timestamp)
            $insereAcervo->save();
        }

        // Se houver um id, é sinal de que o cadastro foi feito com sucesso (não contempla as atualizações para inserção das imagens)
        if ($acervoId) {
            // Seta a mensagem de sucesso e o tipo de resposta como sucesso (classe bootstrap)
            $alertMsg = 'Acervo cadastrado com sucesso!';
            $alertType = 'success';
        } else {
            // Seta a mensagem de falha e o tipo de resposta como perigo (classe bootstrap)
            $alertMsg = 'Falha ao cadastrar o acervo!';
            $alertType = 'danger';
        }

        // Redireciona para a url de criação de acervo passando o alerta de mensagem e o tipo de alerta
        return redirect('/acervo/criar')->with('alert_message', $alertMsg)->with('alert_type', $alertType);
    }

    public function detalhar(Request $request, $id)
    {
        // Seleciona os dados de acervos para detalhamento (query completa com as devidas associações)
        $acervo = Acervos::select('acervos.id', 'acervos.created_at','acervos.updated_at','nome_acervo', 'cep_acervo', 'endereco_acervo', 'numero_endereco_acervo', 'bairro_acervo', 'cidade_acervo', 'UF_acervo', 'descricao_fachada_planta_acervo', 'foto_frontal_acervo', 'titulo_estado_conservacao_acervo', 'ano_construcao_acervo', 'tombamento_id', 'titulo_tombamento', 'seculo_id', 'titulo_seculo', 'especificacao_acervo_id', 'titulo_especificacao_acervo', 'usuario_insercao_id', 'u1.name as usuario_cadastrante', 'usuario_atualizacao_id', 'u2.name as usuario_revisor', 'checkbox_especificacao_acervo',)
            ->where('acervos.id', '=', intval($id))
            ->join('estado_conservacao_acervos as ec', 'ec.id', '=', 'estado_conservacao_acervo_id')
            ->join('tombamentos as t', 't.id', '=', 'tombamento_id')
            ->join('seculos as s', 's.id', '=', 'seculo_id')
            ->leftJoin('especificacao_acervos as ea', 'ea.id', '=', 'especificacao_acervo_id')
            ->join('users as u1', 'u1.id', '=', 'usuario_insercao_id')
            ->leftJoin('users as u2', 'u2.id', '=', 'usuario_atualizacao_id')
            ->first();

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if (!is_null($accesses)) {
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (ilimitado) ou não estiver na lista
            if (!in_array('0', $accesses) and !in_array(strval($id), $accesses)) {
                // ele não é autorizado
                return view('unauthorized');
            }
        } else {
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Como as especificações não são chave estrangeira perfeita, o split da string é feita utilizando como separador a ,
        $especificacoes_array = explode(',', $acervo->checkbox_especificacao_acervo);

        // Busque todas as especificações que estão no array
        $especificacoes = EspecificacaoAcervos::find($especificacoes_array);

        // Chame a view de detalhamento de acervos
        return view('admin.detalhar_acervo', [
            'acervo' => $acervo,
            'especificacoes' => $especificacoes
        ]);
    }

    public function editar(Request $request, $id)
    {
        // Catalogadores não podem editar, visitantes podem VER
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '4', '5', '6'])){
            return view('unauthorized');
        }

        // Seleciona os dados de acervos para edição
        $acervo = Acervos::select('acervos.id', 'acervos.created_at as criado_em', 'nome_acervo', 'cep_acervo', 'endereco_acervo', 'numero_endereco_acervo', 'bairro_acervo', 'cidade_acervo', 'UF_acervo', 'descricao_fachada_planta_acervo', 'foto_frontal_acervo', 'foto_lateral_1_acervo', 'foto_lateral_2_acervo', 'foto_posterior_acervo', 'foto_cobertura_acervo', 'plantas_situacao_acervo', 'estado_conservacao_acervo_id', 'ano_construcao_acervo', 'tombamento_id', 'seculo_id', 'especificacao_acervo_id', 'checkbox_especificacao_acervo', 'name as usuario_cadastrante', 'usuario_atualizacao_id')
            ->where('acervos.id', '=', intval($id))
            ->join('users as u1', 'u1.id', '=', 'usuario_insercao_id')
            ->first();

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if (!is_null($accesses)) {
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (ilimitado) ou não estiver na lista
            if (!in_array('0', $accesses) and !in_array(strval($id), $accesses)) {
                // ele não é autorizado
                return view('unauthorized');
            }
        } else {
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Converte para inteiro todos os valores contidos no array gerado pela separação da string checkbox_especificacao_acervo com o separador ,
        $check = array_map('intval', explode(',', $acervo->checkbox_especificacao_acervo));

        // Seleciona os dados necessários para preencher os valores da lista
        $especificacoes = EspecificacaoAcervos::select('id', 'titulo_especificacao_acervo')->orderBy('titulo_especificacao_acervo', 'ASC')->get();
        $estados = EstadoConservacaoAcervos::select('id', 'titulo_estado_conservacao_acervo', 'is_default_estado_conservacao_acervo')->get();
        $seculos = Seculos::select('id', 'titulo_seculo', 'ano_inicio_seculo', 'ano_fim_seculo', 'is_default_seculo')->get();
        $tombamentos = Tombamentos::select('id', 'titulo_tombamento', 'is_default_tombamento')->get();

        // Chama a view de edição de acervos
        return view('admin.editar_acervo', [
            'acervo' => $acervo,
            'especificacoes' => $especificacoes,
            'estados' => $estados,
            'seculos' => $seculos,
            'tombamentos' => $tombamentos,
            'check' => $check
        ]);
    }

    public function atualizar(Request $request, $id)
    {
        // Catalogadores e visitantes não podem editar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2', '4', '5'])){
            return view('unauthorized');
        }

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if (!is_null($accesses)) {
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (ilimitado) ou não estiver na lista
            if (!in_array('0', $accesses) and !in_array(strval($id), $accesses)) {
                // ele não é autorizado
                return view('unauthorized');
            }
        } else {
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Descobre quais anos são os limites do século escolhido
        $acervo = Acervos::select('seculo_id')->where('id', $id)->first();
        $seculo = Seculos::select('ano_inicio_seculo', 'ano_fim_seculo')->where('id', $acervo['seculo_id'])->first();

        // Valida os dados
        $request->validate([
            'nome_acervo' => 'required|min:2|max:191',
            'cep_acervo' => 'required|min:1|max:9',
            'endereco_acervo' => 'required|min:1|max:250',
            'numero_endereco_acervo' => 'max:6',
            'bairro_acervo' => 'required|min:1|max:50',
            'cidade_acervo' => 'required|min:1|max:50',
            'UF_acervo' => 'required|min:2|max:2',
            'tombamento_acervo' => 'required|min:1|max:21',
            'seculo_acervo' => 'required|min:1|max:21',
            'ano_acervo' => 'nullable|max:5|gte:' . strval($seculo->ano_inicio_seculo) . '|lte:' . strval($seculo->ano_fim_seculo),
            'estado_conservacao_acervo' => 'required|min:1|max:21',
            'descricao_acervo' => 'required|max:10000',
        ]);

        // Descobre qual user que fez a requisição
        $usuario = auth()->user('id');

        /* Parametrização do caminho onde as imagens ficam. */
        // Nome do primeiro folder
        $preBasePath =  'imagem';
        // Nome do segundo folder
        $basePath =  $preBasePath . '/acervos';

        // Se o primeiro folder não existir (é pra sempre existirem, mas, mais uma vez, checagem de segurança)
        if (!Storage::exists($preBasePath)) {
            // Ele será criado
            Storage::makeDirectory(public_path($preBasePath, 0755, true));
            // E o subfolder também (se o pré não existe, seus filhos também não 
            // E o subfolder também (se o pré não existe, seus filhos também não existem)
            Storage::makeDirectory(public_path($basePath));
        } else if (!is_dir($basePath)) { // Caso o primeiro folder exista, checa se o segundo não existe
            // Se não existir, cria ele
            mkdir(public_path($basePath));
        }

        /* Tratamento de dados para quando o folder de imagem do id a ser inserido já existe (não deve ser executado nunca, mas por precaução...) */
        // Parametrização do nome da pasta onde as imagens vão ficar
        $imagemacervo =  $basePath . '/' . $id;

        // Se a pasta não existir
        if (!Storage::exists($imagemacervo)) {
            // Já que ela não existe, cria
            Storage::makeDirectory(public_path($imagemacervo, 0755, true));
        }


        try {
            // Se existe uma especificação de acervo e ela não está vazia
            if (isset($request->especificacao_acervo) and !empty($request->especificacao_acervo)) {
                // Concatena os elementos do array usando como separador uma ,
                $check = implode(',', $request->especificacao_acervo);
            } else {
                // Já que não existe dado para especificação de acervo, marca como uma string vazia
                $check = '';
            }

            // Edita o acervo que possui o id igual ao id passado na url
            $editandoAcervo = Acervos::where('id', '=', $id)
                ->update([
                    'nome_acervo' => $request->nome_acervo,
                    'updated_at' => new \DateTime(),
                    'cep_acervo' => $request->cep_acervo,
                    'endereco_acervo' => $request->endereco_acervo,
                    'numero_endereco_acervo' => $request->numero_endereco_acervo,
                    'bairro_acervo' => $request->bairro_acervo,
                    'cidade_acervo' => $request->cidade_acervo,
                    'UF_acervo' => $request->UF_acervo,
                    'tombamento_id' => $request->tombamento_acervo,
                    'seculo_id' => $request->seculo_acervo,
                    'ano_construcao_acervo' => $request->ano_acervo,
                    'estado_conservacao_acervo_id' => $request->estado_conservacao_acervo,
                    'checkbox_especificacao_acervo' => $check,
                    'descricao_fachada_planta_acervo' => $request->descricao_acervo,
                    'usuario_atualizacao_id' => $usuario->id,
                ]);
            // Seta flag de sucesso
            $isSuccess = true;
        } catch (Exception $e) {
            // Seta flag de falha
            $isSuccess = false;
        }

        /* Tratamento para inserção de fotos submetidas */
        // Se houver alguma foto submetida na requisição (útil pra evitar processamento desnecessário)
        if (
            $request->hasFile('foto_frontal_acervo') or
            $request->hasFile('foto_lateral_1_acervo') or
            $request->hasFile('foto_lateral_2_acervo') or
            $request->hasFile('foto_posterior_acervo') or
            $request->hasFile('foto_cobertura_acervo') or
            $request->hasFile('plantas_situacao_acervo')
        ) {

            // Descobre qual é o acervo que acabou de ser inserido
            $updateAcervo = Acervos::find($id);
            // Adição sem timestamp (importante pra não flagar como update)
            $updateAcervo->timestamps = false;

            // Se houver foto frontal
            if ($request->file('foto_frontal_acervo')) {
                // Seta o nome da imagem como frontal
                $imageName = 'Frontal_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_frontal_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Descobre o hash da imagem
                $imgfile = fopen(public_path($imagemacervo) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemacervo) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_frontal_acervo como o caminho de onde a imagem está salva
                $updateAcervo->foto_frontal_acervo = $imagemacervo . '/' . $imageName . '?x=' . $md5;
            }

            // Se houver foto lateral esquerda
            if ($request->file('foto_lateral_1_acervo')) {
                // Seta o nome da imagem como lateral esquerda
                $imageName = 'Lateral_Esquerda_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_1_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Descobre o hash da imagem
                $imgfile = fopen(public_path($imagemacervo) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemacervo) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);

                // Seta a coluna foto_lateral_1_acervo como o caminho de onde a imagem está salva
                $updateAcervo->foto_lateral_1_acervo = $imagemacervo . '/' . $imageName . '?x=' . $md5;
            }

            // Se houver foto lateral direita
            if ($request->file('foto_lateral_2_acervo')) {
                // Seta o nome da imagem como lateral direita
                $imageName = 'Lateral_Direita_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_lateral_2_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Descobre o hash da imagem
                $imgfile = fopen(public_path($imagemacervo) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemacervo) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_lateral_2_acervo como o caminho de onde a imagem está salva
                $updateAcervo->foto_lateral_2_acervo = $imagemacervo . '/' . $imageName . '?x=' . $md5;
            }

            // Se houver foto posterior
            if ($request->file('foto_posterior_acervo')) {
                // Seta o nome da imagem como posterior
                $imageName = 'Posterior_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_posterior_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);

                // Descobre o hash da imagem
                $imgfile = fopen(public_path($imagemacervo) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemacervo) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_posterior_acervo como o caminho de onde a imagem está salva
                $updateAcervo->foto_posterior_acervo = $imagemacervo . '/' . $imageName . '?x=' . $md5;
            }

            // Se houver foto cobetura
            if ($request->file('foto_cobertura_acervo')) {
                // Seta o nome da imagem como cobetura
                $imageName = 'Cobertura_Acervo.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_cobertura_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);

                // Descobre o hash da imagem
                $imgfile = fopen(public_path($imagemacervo) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemacervo) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);
                // Seta a coluna foto_cobertura_acervo como o caminho de onde a imagem está salva
                $updateAcervo->foto_cobertura_acervo = $imagemacervo . '/' . $imageName . '?x=' . $md5;
            }

            // Se houver foto situação
            if ($request->file('plantas_situacao_acervo')) {
                // Seta o nome da imagem como situação
                $imageName = 'Plantas_Situacao_Acervo.webp';

                $img = Image::make($request->plantas_situacao_acervo)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($imagemacervo) . '/' . $imageName)->encode('webp', 90);
                // Descobre o hash da imagem
                $imgfile = fopen(public_path($imagemacervo) . '/' . $imageName, 'r');
                $data = fread($imgfile, filesize(public_path($imagemacervo) . '/' . $imageName));
                $md5 = md5($data); 
                fclose($imgfile);

                // Seta a coluna foto_cobertura_acervo como o caminho de onde a imagem está salva
                $updateAcervo->plantas_situacao_acervo = $imagemacervo . '/' . $imageName . '?x=' . $md5;
            }
            // Salva as alterações feitas (evitando o timestamp)
            $updateAcervo->save();
        }

        // Se houver um id, é sinal de que o cadastro foi feito com sucesso (não contempla as atualizações para inserção das imagens)
        if ($isSuccess) {
            // Seta a mensagem de sucesso e o tipo de resposta como sucesso (classe bootstrap)
            $alertMsg = 'Acervo atualizado com sucesso!';
            $alertType = 'success';
        } else {
            // Seta a mensagem de falha e o tipo de resposta como perigo (classe bootstrap)
            $alertMsg = 'Falha ao atualizar o acervo!';
            $alertType = 'danger';
        }

        // Redireciona para a url de edição de acervo passando o alerta de mensagem e o tipo de alerta
       
        return redirect('/acervo/editar/' . $request->id)->with('alert_message', $alertMsg)->with('alert_type', $alertType);
    }

    public function deletar(Request $request, $id){
        // Somente TI ou administradores podem deletar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2'])){
            return view('unauthorized');
        }

        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if (!is_null($accesses)) {
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se o acesso não for 0 (ilimitado) ou não estiver na lista
            if (!in_array('0', $accesses) and !in_array(strval($id), $accesses)) {
                // ele não é autorizado
                return view('unauthorized');
            }
        } else {
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Descobre se existe alguma obra associada à esse acervo
        $obras = Obras::select()->where('acervo_id', '=', $id)->get();

        // Se obra tiver algum resultado, significa de que existe pelo menos alguma obra associada com o acervo
        if ($obras->count() == 0) {

            try{
                // Deleta o acervo
                $acervo = Acervos::select()->where('id', '=', $id)->delete();
            }catch(Exception $e){
                return response()->json(['status' => 'error', 'msg' => 'Registro inexistente. Talvez ele já tenha sido deletado.']);
            }
            try {
                /* Parametrização do caminho onde as imagens ficam. */
                // Nome do primeiro folder
                $preBasePath =  'imagem';
                // Nome do segundo folder
                $basePath =  $preBasePath . '/acervos';

                // Parametrização do nome da pasta onde as imagens estão
                $imagemacervo =  $basePath . '/' . $id;

                // Se a pasta existir
                if (!Storage::exists($imagemacervo)) {
                    // Delete o seu conteúdo
                    array_map('unlink', glob(public_path($imagemacervo) . "/*.*"));
                    // Apague a pasta
                    rmdir(public_path($imagemacervo));
                }
                // Se existir um elemento obra
                if ($acervo) {
                    // Retorne sucesso
                    return response()->json(['status' => 'success', 'msg' => 'Acervo deletado.']);
                } else { // caso contrário
                    // Retorne falha
                    return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar o acervo.']);
                }
            } catch (Exception $e) { // Se houver qualquer falha
                // Retorne falha
                return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar o acervo.']);
            }
        } else {
            // Lista de ids de acervo
            $ids = [];
            foreach ($obras as $obra) {
                array_push($ids, $obra['id']);
            }
            return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar o acervo pois ele contém obras associadas! As obras são: ' . implode(', ', $ids)]);;
        }
        return response()->json(['status' => 'error', 'msg' => 'Ops.. Não conseguimos deletar o acervo. Erro DESCONHECIDO']);;
    }

    public function getObrasAcervo(Request $request, $id){
        
        // Descobre quais acervos que o usuário tem acesso
        $accesses = auth()->user('id')['acesso_acervos'];

        // Se o acesso não for nulo
        if(!is_null($accesses)){
            // Faz o split do acesso usando vírgulas
            $accesses = explode(',', $accesses);

            // Se não tiver acesso total (0) ou não tiver o acesso
            if($accesses[0] != 0 and !in_array($id, $accesses)){
                // Usuário não pode acessar
                return view('unauthorized');
            }
        }else{
            // Acesso nulo é sem acesso a nada
            return view('unauthorized');
        }

        // Descobre se existe alguma obra associada à esse acervo
        //$obras = Obras::select()->where('acervo_id', '=', $id)->get();

        //print_r($obras);die;

        // Seleciona os dados de obras para serem dispostas na listagem de obras
        $obras = Obras::select('obras.id', 'titulo_obra', 'tesauro_id', 'titulo_tesauro', 'acervo_id', 'nome_acervo', 'material_id_1', 'm1.titulo_material as titulo_material_1', 'material_id_2', 'm2.titulo_material as titulo_material_2', 'material_id_3', 'm3.titulo_material as titulo_material_3', 'foto_frontal_obra', 'obras.seculo_id', 'titulo_seculo', 'obra_provisoria')
            ->orwhere('acervo_id', '=', $id)
            ->join('seculos as s', 's.id', '=', 'obras.seculo_id')
            ->join('tesauros as t', 't.id', '=', 'tesauro_id')
            ->join('acervos as a', 'a.id', '=', 'acervo_id')
            ->leftjoin('materiais as m1', 'm1.id', '=', 'material_id_1')
            ->leftjoin('materiais as m2', 'm2.id', '=', 'material_id_2')
            ->leftjoin('materiais as m3', 'm3.id', '=', 'material_id_3')
            ->orderBy('obras.id', 'ASC')
            ->get();
        
        $user = auth()->user('id_cargo');

        // Retorna a view de listagem de obras do acervo escolhido
        return view('admin.acervo_obras', compact('obras','user'));
    }
}
