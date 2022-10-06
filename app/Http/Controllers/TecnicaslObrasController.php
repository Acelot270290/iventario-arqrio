<?php

namespace App\Http\Controllers;

use App\Models\Tecnicas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TecnicaslObrasController extends Controller
{
    public function adicionar(Request $request){
       
        //Atribuindo na variavel o input da localização

        $nome_tecnica = $request->cadatro_nome_tecnica;
        
        //Verificação caso já exista o registro
        $existe = Tecnicas::select('id','titulo_tecnica','descricao_tecnica')
        ->where('titulo_tecnica', $nome_tecnica)
        ->get();
    

        if(count($existe)>0){

        Alert::error('Dados já Existente', 'A Técnica '.$nome_tecnica. ' já Existe');
        return back();

        }elseif(empty($nome_tecnica)){

            Alert::error('Erro', 'Precisa Digitar os dados');
        return back();

        }else{
            
        $tecnica = new Tecnicas();
        $tecnica->titulo_tecnica = $request->cadatro_nome_tecnica;
        $tecnica->save();

        Alert::success('Técnica Salvo', 'Registro salvo com sucesso!');
        return back();

        }
        

        

    }
}
