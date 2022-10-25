<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // Authenticate

        //Verifica o Token do user antes de carregar a pagina, caso o user esteja logado joga pra home
       
       if(!empty(Auth::user()->remember_token)){
        return redirect('home');
       }
        
        return view('auth.login');
    }
}
