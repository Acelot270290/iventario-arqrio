<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TopoController extends Controller
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
        // Pega os dados dos usuários necessários para o layout ???
        $usuarios = User::select('users.id', 'name', 'email', 'image', 'id_funcao','nome_funcao', 'estado')
            ->join('funcoes as f', 'f.id', '=', 'id_funcao')
            ->get();
        
        // Chama a view dos layouts
        return view('layouts.app', [
            'usuarios' => $usuarios
        ]);
    }

    public function sair (Request $request)
    {
        // Desloga
        Auth::logout();
        // Invalida a sessão
        $request->session()->invalidate();
        // Gera um novo token de sessão
        $request->session()->regenerateToken();
        // Redireciona pra home
        return redirect('/');
    }
}
