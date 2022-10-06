<?php

namespace App\Http\Controllers;

use App\Models\EspecificacaoAcervos;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EspecificacaoAcervosController extends Controller
{
    public function adicionar(Request $request){
       
        //Atribuindo na variavel o input da localização

        $nome_especificacao_acevos = $request->cadatro_nome_especificacao_acevos;
        
        //Verificação caso já exista o registro
        $existe = EspecificacaoAcervos::select('id','titulo_especificacao_acervo','descricao_especificacao_acervo')
        ->where('titulo_especificacao_acervo', $nome_especificacao_acevos)
        ->get();
    

        if(count($existe)>0){

        Alert::error('Dados já Existente', 'A Especificação '.$nome_especificacao_acevos. ' já Existe');
        return back();

        }elseif(empty($nome_especificacao_acevos)){

            Alert::error('Erro', 'Precisa Digitar os dados');
        return back();

        }else{
            
        $EspecificacaoAcervos = new EspecificacaoAcervos();
        $EspecificacaoAcervos->titulo_especificacao_acervo = $request->cadatro_nome_especificacao_acevos;
        $EspecificacaoAcervos->save();

        Alert::success('Especificação Salvo', 'Registro salvo com sucesso!');
        return back();

        }
        

        

    }
}
