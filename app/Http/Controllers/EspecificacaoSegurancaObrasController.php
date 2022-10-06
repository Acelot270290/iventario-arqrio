<?php

namespace App\Http\Controllers;

use App\Models\EspecificacaoSegurancaObras;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EspecificacaoSegurancaObrasController extends Controller
{
    public function adicionar(Request $request){
       
        //Atribuindo na variavel o input da localização

        $especificacao_seguranca_obra = $request->cadatro_nome_especificacao_seguranca_obra;
        
        //Verificação caso já exista o registro
        $existe = EspecificacaoSegurancaObras::select('id','titulo_especificacao_seguranca_obra','descricao_especificacao_seguranca_obra')
        ->where('titulo_especificacao_seguranca_obra', $especificacao_seguranca_obra)
        ->get();
    

        if(count($existe)>0){

        Alert::error('Dados já Existente', 'A Especificação '.$especificacao_seguranca_obra. ' já Existe');
        return back();

        }elseif(empty($especificacao_seguranca_obra)){

            Alert::error('Erro', 'Precisa Digitar os dados');
        return back();

        }else{
            
        $especificacaoDeSeguranca = new EspecificacaoSegurancaObras();
        $especificacaoDeSeguranca->titulo_especificacao_seguranca_obra = $request->cadatro_nome_especificacao_seguranca_obra;
        $especificacaoDeSeguranca->save();

        Alert::success('Especificação Salvo', 'Registro salvo com sucesso!');
        return back();

        }
        

        

    }
}
