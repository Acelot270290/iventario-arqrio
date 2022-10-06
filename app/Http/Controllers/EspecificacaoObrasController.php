<?php

namespace App\Http\Controllers;

use App\Models\EspecificacaoObras;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EspecificacaoObrasController extends Controller
{
    public function adicionar(Request $request){
       
        //Atribuindo na variavel o input da localização

        $nome_especificacao_seguranca_obra = $request->cadatro_nome_especificacao_seguranca_obra;
        
        //Verificação caso já exista o registro
        $existe = EspecificacaoObras::select('id','titulo_especificacao_obra','descricao_especificacao_obra')
        ->where('titulo_especificacao_obra', $nome_especificacao_seguranca_obra)
        ->get();
    

        if(count($existe)>0){

        Alert::error('Dados já Existente', 'A Especificação '.$nome_especificacao_seguranca_obra. ' já Existe');
        return back();

        }elseif(empty($nome_especificacao_seguranca_obra)){

            Alert::error('Erro', 'Precisa Digitar os dados');
        return back();

        }else{
            
        $Especificacao = new EspecificacaoObras();
        $Especificacao->titulo_especificacao_obra = $request->cadatro_nome_especificacao_seguranca_obra;
        $Especificacao->save();

        Alert::success('Especificação Salvo', 'Registro salvo com sucesso!');
        return back();

        }
        

        

    }
}
