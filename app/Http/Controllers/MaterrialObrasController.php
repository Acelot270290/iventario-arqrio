<?php

namespace App\Http\Controllers;

use App\Models\Materiais;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MaterrialObrasController extends Controller
{
    public function adicionar(Request $request){
       
        //Atribuindo na variavel o input da localização

        $nome_material = $request->cadatro_nome_material;
        //Verificação caso já exista o registro
        $existe = Materiais::select('id','titulo_material','descricao_material')
        ->where('titulo_material', $nome_material)
        ->get();
        
      

        if(count($existe)>0){

        Alert::error('Dados já Existente', 'Material '.$nome_material. ' já Existe');
        return back();

        }elseif(empty($nome_material)){

            Alert::error('Erro', 'Precisa Digitar os dados');
        return back();

        }else{
            
        $Material = new Materiais();
        $Material->titulo_material = $request->cadatro_nome_material;
        $Material->save();

        Alert::success('Material Salvo', 'Registro salvo com sucesso!');
        return back();

        }
        

        

    }
}
