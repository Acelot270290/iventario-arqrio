<?php

namespace App\Http\Controllers;

use App\Models\Tesauros;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TesauroObrasController extends Controller
{
    public function adicionar(Request $request){
       
        //Atribuindo na variavel o input da localização

        $nome_tesauro = $request->cadatro_nome_tesauro;
        //Verificação caso já exista o registro
        $existe = Tesauros::select('id','titulo_tesauro','descricao_tesauro')
        ->where('titulo_tesauro', $nome_tesauro)
        ->get();
        
      

        if(count($existe)>0){

        Alert::error('Dados já Existente', 'Tesauro '.$nome_tesauro. 'já Existe');
        return back();

        }elseif(empty($nome_tesauro)){

            Alert::error('Erro', 'Precisa Digitar os dados');
        return back();

        }else{
            
        $tesauroObras = new Tesauros();
        $tesauroObras->titulo_tesauro = $request->cadatro_nome_tesauro;
        $tesauroObras->save();

        Alert::success('Localização Salva', 'Registro salvo com sucesso!');
        return back();

        }
        

        

    }
}
