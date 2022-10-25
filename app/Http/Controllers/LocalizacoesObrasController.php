<?php

namespace App\Http\Controllers;

use App\Models\LocalizacoesObras;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LocalizacoesObrasController extends Controller
{
    public function adicionar(Request $request)
    {

        //Atribuindo na variavel o input da localização

        $nome_localicacao = $request->cadatro_nome_localicacao;
        //Verificação caso já exista o registro
        $existe = LocalizacoesObras::select('id', 'nome_localizacao', 'descricao_localizacao')
            ->where('nome_localizacao', $nome_localicacao)
            ->get();

        // print_r($existe);die;

        if (count($existe) > 0) {

            Alert::error('Dados já Existente', 'Localização ' . $nome_localicacao . '</b> já Existe');
            return back();
        } elseif (empty($nome_tesauro)) {

            Alert::error('Erro', 'Precisa Digitar os dados');
            return back();
        } else {


            $localicacaoObras = new LocalizacoesObras();
            $localicacaoObras->nome_localizacao = $request->cadatro_nome_localicacao;
            $localicacaoObras->save();

            Alert::success('LTesauro Salvo', 'Registro salvo com sucesso!');
            return back();
        }
    }
}
