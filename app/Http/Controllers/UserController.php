<?php

namespace App\Http\Controllers;

use App\Mail\CadastroUserMail;
use App\Mail\MensagemMail;
use App\Models\Acervos;
use App\Models\Cargos;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Image;
use Str;

class UserController extends Controller
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
        // Seleciona os dados dos usuários
        $usuarios = User::select('users.id', 'name', 'email', 'image', 'id_cargo', 'nome_cargo', 'estado')
            ->join('cargos as f', 'f.id', '=', 'id_cargo')
            ->orderBy('users.id', 'ASC')
            ->get();
        
        
        return view('admin.usuarios', [
            'usuarios' => $usuarios
        ]);
    }

    public function criar(Request $request)
    {
        // Revisores não podem criar, visitantes podem VER
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2'])){
            return view('unauthorized');
        }
        
        $acervos = Acervos::select('acervos.id', 'nome_acervo')
            ->orderBy('acervos.id', 'ASC')
            ->get();
        
        // Seleciona os cargos existentes
        $cargos = Cargos::select('cargos.id', 'nome_cargo', 'is_default_cargo')
        ->orderBy('cargos.id', 'ASC')
        ->get();

        // Chama a criação de acervo
        return view('admin.criar_usuario', [
            'acervos' => $acervos,
            'cargos' => $cargos
        ]);
    }

    public function adicionar(Request $request)
    {
        // Apenas administradores e TI podem criar
        if(!in_array(strval(auth()->user('id')['id_cargo']), ['1', '2'])){
            return view('unauthorized');
        }

        // Valida os dados
        $request->validate([
            'nome_usuario' => 'required|min:2|max:191',
            'email_usuario' => 'required|min:5|email:rfc,dns',
            'estado_usuario' => 'required|integer|gte:0|lte:1',
            'acesso_acervo_usuario' => 'array|min:1',
            'cargo_usuario' => 'required',
        ]);

        // Checa se o email passado já está cadastrado
        $hasMail = User::where('email', '=', $request->email_usuario)->first();

        // O email já está na base de dados
        if ($hasMail !== null) {
            // Seta a mensagem de falha e o tipo de resposta como perigo (classe bootstrap)
            $alertMsg = 'O e-mail já está cadastrado na base de dados!';
            $alertType = 'danger';

            // Redireciona para a url de criação de acervo passando o alerta de mensagem e o tipo de alerta
            return redirect('/usuarios/criar')->with('alert_message', $alertMsg)->with('alert_type', $alertType);
        }

        // Se existe acesso de acervo para esse usuário e ele não está vazio
        if (isset($request->acesso_acervo_usuario) and !empty($request->acesso_acervo_usuario)) {
            // Concatena os elementos do array usando como separador uma ,
            $check = implode(',', $request->acesso_acervo_usuario);
        } else {
            // Já que não existe dado para especificação de acesso, marca como uma string vazia (Usuário não pode ver NADA!)
            $check = '';
        }
        
        $object = new User();
        // Insere os dados em acervos e retorna o id do elemento inserido
        $timestamp = $object->freshTimestampString();

        $pwd = Str::random(8);

        /* Mandar o email aqui, adicionei o texto em NovoCadastroNotification.php que está aberto. Depois que fizer, teste por favor, o resto está todo construído. */

        $user= new User();
        $userId = $user->insertGetId([
            'created_at' => $timestamp,
            'id_cargo' => $request->cargo_usuario,
            'name' => $request->nome_usuario,
            'email' => $request->email_usuario,
            'password' => Hash::make($pwd),
            'estado' => $request->estado_usuario,
            'acesso_acervos' => $check,
        ]);

        $enviando  = Mail::to($request->email_usuario)->send(new CadastroUserMail($userId, $pwd));
    
        /* Parametrização do caminho onde as imagens ficam. */
        // Nome do primeiro folder
        $preBasePath =  'assets/img';
        // Nome do segundo folder
        $basePath =  $preBasePath . '/users';

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

        /* Tratamento para inserção de fotos submetidas */
        // Se houver alguma foto submetida na requisição (útil pra evitar processamento desnecessário)
        if ($request->hasFile('foto_usuario')) {
            // Descobre qual é o acervo que acabou de ser inserido
            $insereUser = User::find($userId);
            // Torna a inserção de timestamp como false (caso contrário a coluna UpdatedAt ganha um valor)
            $insereUser->timestamps = false;

            // Se houver foto frontal
            if ($request->file('foto_usuario')) {
                // Seta o nome da imagem como frontal
                $imageName = pathinfo($request->foto_usuario->getClientOriginalName(), PATHINFO_FILENAME). '_' . date('now') . '.webp';
                // Cria um objeto de imagem com a imagem fornecida e marca a orientação
                $img = Image::make($request->foto_usuario)->orientate();
                // Redimensiona pra 450px x auto mantendo a proporção
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Salva a imagem com a codificação webp e dpi de 90
                $img->save(public_path($basePath) . '/' . $imageName)->encode('webp', 90);

                // Seta a coluna image como o caminho de onde a imagem está salva
                $insereUser->image = $imageName;
            }else{
                // Seta a coluna image como o caminho de onde a imagem está salva
                $insereUser->image = 'semfoto.jpg';
            }

            // Salva as alterações feitas (evitando o timestamp)
            $insereUser->save();
        }

        // Se houver um id, é sinal de que o cadastro foi feito com sucesso (não contempla as atualizações para inserção das imagens)
        if ($userId) {
            // Seta a mensagem de sucesso e o tipo de resposta como sucesso (classe bootstrap)
            $alertMsg = 'Usuário cadastrado com sucesso!';
            $alertType = 'success';
        } else {
            // Seta a mensagem de falha e o tipo de resposta como perigo (classe bootstrap)
            $alertMsg = 'Falha ao cadastrar o usuário!';
            $alertType = 'danger';
        }

        // Redireciona para a url de criação de acervo passando o alerta de mensagem e o tipo de alerta
        return redirect('/usuarios/criar')->with('alert_message', $alertMsg)->with('alert_type', $alertType);
    }
}
