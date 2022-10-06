@extends('layouts.app')

@section('titulo', 'Cadastro de obra')

@section('content')

@php
    $allowEdit = ['1', '2', '3', '5'];
    $canOnlyView = ['6'];
    $repeat = session()->has('repeat') ? session()->pull('repeat') : [];
@endphp

<div class="main-content" style="min-height: 562px;">
  <section class="section">
    <div class="section-body">
      @if(session()->has('alert_type'))
      <div id="msg" class="alert alert-{{ session()->pull('alert_type') }} alert-dismissible fade show" role="alert">
        {{ session()->pull('alert_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      <div id="anoerror">
      </div>
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <form method="POST" action="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('adicionar_obra') }}@endif" name="criar_obra" accept-charset="utf-8"
              enctype="multipart/form-data">
              @if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) @csrf @endif
              <div class="card-header">
                <h4> Adicionar Obra </h4>
              </div>
              <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <div class="pretty p-icon p-smooth" style="display: inline-flex; flex-wrap: wrap; margin-right: 10px;">
                    @if(array_key_exists('obra_provisoria', $repeat))
                      @if($repeat['obra_provisoria'] == "1")
                        <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;" value="1" id="obra_provisoria" checked>
                      @else
                        <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;" value="1" id="obra_provisoria">
                      @endif
                    @else
                      @if(old('obra_provisoria') == "1")
                        <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;" value="1" id="obra_provisoria" checked>
                      @else
                        <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;" value="1" id="obra_provisoria">
                      @endif
                    @endif
                    <div class="state p-success">
                      <label style="margin-left: 10px;" for="obra_provisoria">Obra provisória</label>
                    </div>
                  </div>
                  <div class="pretty p-icon p-smooth" style="display: inline-flex; flex-wrap: wrap; margin-right: 10px; float: right;">
                    @if(old('repete_obra') == "1")
                      <input name="repete_obra" type="checkbox" style="margin-top: 3px;" value="1" id="repete_obra" checked>
                    @else
                      <input name="repete_obra" type="checkbox" style="margin-top: 3px;" value="1" id="repete_obra">
                    @endif
                    <div class="state p-success">
                      <label style="margin-left: 10px;" for="repete_obra">Repetir obra</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Categoria da obra</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <select name="categoria_obra" class="form-control">
                        @foreach ($categorias as $categoria)
                          @if(array_key_exists('categoria_id', $repeat))
                            @if($categoria->id == $repeat['categoria_id'])
                              <option value="{{ $categoria->id }}" selected>{{ $categoria->titulo_categoria }}</option>
                            @else
                              <option value="{{ $categoria->id }}">{{ $categoria->titulo_categoria }}</option>
                            @endif
                          @else
                            @if($categoria->is_default_categoria)
                              <option value="{{ $categoria->id }}" selected>{{ $categoria->titulo_categoria }}</option>
                            @else
                              <option value="{{ $categoria->id }}">{{ $categoria->titulo_categoria }}</option>
                            @endif
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <small class="text-danger">{{ $errors->first('categoria_obra') }}</small>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Acervo da obra</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <select name="acervo_obra" class="form-control select2">
                        <option value="">Selecione um Acervo</option>
                        @foreach ($acervos as $acervo)
                          @if(array_key_exists('acervo_id', $repeat))
                            @if($acervo->id == $repeat['acervo_id'])
                              <option value="{{ $acervo->id }}" selected>{{$acervo->id.' - ' .$acervo->nome_acervo }}</option>
                            @else
                              <option value="{{ $acervo->id }}">{{$acervo->id.' - ' .$acervo->nome_acervo }}</option>
                            @endif
                          @else
                            @if($acervo->id == old('acervo_obra'))
                              <option value="{{ $acervo->id }}" selected>{{$acervo->id.' - ' .$acervo->nome_acervo }}</option>
                            @else
                              @if($acervo->is_default_acervo)
                                <option value="{{ $acervo->id }}" selected>{{$acervo->id.' - ' .$acervo->nome_acervo }}</option>
                              @else
                                <option value="{{ $acervo->id }}">{{$acervo->id.' - ' .$acervo->nome_acervo }}</option>
                              @endif
                            @endif
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <small class="text-danger">{{ $errors->first('acervo_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Título</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" name="titulo_obra" value="{{ old('titulo_obra') }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('titulo_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Dimensões (cm)</label>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label>Altura</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-map-marker-alt text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('altura_obra', $repeat))
                        <input type="number" class="form-control" name="altura_obra" value="{{ $repeat['altura_obra'] }}">
                      @else
                        <input type="number" class="form-control" name="altura_obra" value="{{ old('altura_obra') }}">
                      @endif
                    </div>
                    <small class="text-danger">{{ $errors->first('altura_obra') }}</small>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Largura</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-road text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('largura_obra', $repeat))
                        <input type="number" class="form-control" name="largura_obra" value="{{ $repeat['largura_obra'] }}">
                      @else
                        <input type="number" class="form-control" name="largura_obra" value="{{ old('largura_obra') }}">
                      @endif
                    </div>
                    <small class="text-danger">{{ $errors->first('largura_obra') }}</small>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Profundidade</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-road text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('profundidade_obra', $repeat))
                        <input type="number" class="form-control" name="profundidade_obra" value="{{ $repeat['profundidade_obra'] }}">
                      @else
                        <input type="number" class="form-control" name="profundidade_obra" value="{{ old('profundidade_obra') }}">
                      @endif
                    </div>
                    <small class="text-danger">{{ $errors->first('profundidade_obra') }}</small>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Comprimento</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-road text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('comprimento_obra', $repeat))
                        <input type="number" class="form-control" name="comprimento_obra" value="{{ $repeat['comprimento_obra'] }}">
                      @else
                        <input type="number" class="form-control" name="comprimento_obra" value="{{ old('comprimento_obra') }}">
                      @endif
                    </div>
                    <small class="text-danger">{{ $errors->first('comprimento_obra') }}</small>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Diâmetro</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-street-view text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('diametro_obra', $repeat))
                        <input type="number" class="form-control" name="diametro_obra" value="{{ $repeat['diametro_obra'] }}">
                      @else
                        <input type="number" class="form-control" name="diametro_obra" value='{{ old('diametro_obra') }}'>
                      @endif
                    </div>
                    <small class="text-danger">{{ $errors->first('diametro_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>Tesauro</label>
                    <select name="tesauro_obra" class="form-control select2">
                      <option value="">Selecione um tesauro</option>
                      @foreach ($tesauros as $tesauro)
                        @if(array_key_exists('tesauro_id', $repeat))
                          <option value="{{ $tesauro->id }}" {{ $repeat['tesauro_id'] == $tesauro->id ? "selected" : "" }}>{{ $tesauro->titulo_tesauro }}</option>
                        @else
                          @if(old('tesauro_obra') !== null)
                            <option value="{{ $tesauro->id }}" {{ old("tesauro_obra") == $tesauro->id ? "selected" : "" }}>{{ $tesauro->titulo_tesauro }}</option>
                          @else
                            <option value="{{ $tesauro->id }}">{{ $tesauro->titulo_tesauro }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('tesauro_obra') }}</small>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModalTesauro" style="
                    margin-top: 10px;" title="Adicionar Localização">+</button>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Localização da obra</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <select name="localizacao_obra" class="form-control select2">
                        <option value="">Selecione uma localização</option>
                        @foreach ($localizacoes as $localizacao)
                          @if(array_key_exists('localizacao_obra_id', $repeat))
                            <option value="{{ $localizacao->id }}" {{ $repeat['localizacao_obra_id'] == $localizacao->id ? "selected" : "" }}>{{ $localizacao->nome_localizacao }}</option>
                          @else
                            @if(old('localizacao_obra') !== null)
                              <option value="{{ $localizacao->id }}" {{ old("localizacao_obra") == $localizacao->id ? "selected" : "" }}>{{ $localizacao->nome_localizacao }}</option>
                            @else
                              @if($localizacao->is_default_localizacao)
                                <option value="{{ $localizacao->id }}" selected>{{ $localizacao->nome_localizacao }}</option>
                              @else
                                <option value="{{ $localizacao->id }}">{{ $localizacao->nome_localizacao }}</option>
                              @endif
                            @endif
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <small class="text-danger">{{ $errors->first('localizacao_obra') }}</small>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModalLocalicacao" style="
                    margin-top: 10px;" title="Adicionar Localização">+</button>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Condições de Segurança</label>
                    <select name="condicao_seguranca_obra" class="form-control">
                      @foreach ($condicoes as $condicao)
                        @if(array_key_exists('condicoes_de_seguranca_obra_id', $repeat))
                            <option value="{{ $condicao->id }}" {{ $repeat['condicoes_de_seguranca_obra_id'] == $condicao->id ? "selected" : "" }}>{{ $condicao->titulo_condicao_seguranca_obra }}</option>
                        @else
                          @if(old('condicao_seguranca_obra') !== null)
                            <option value="{{ $condicao->id }}" {{ old("condicao_seguranca_obra") == $condicao->id ? "selected" : "" }}>{{ $condicao->titulo_condicao_seguranca_obra }}</option>
                          @else
                            @if($condicao->is_default_condicao_seguranca_obra)
                              <option value="{{ $condicao->id }}" selected>{{ $condicao->titulo_condicao_seguranca_obra }}</option>
                            @else
                              <option value="{{ $condicao->id }}">{{ $condicao->titulo_condicao_seguranca_obra }}</option>
                            @endif
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Origem/Procedência</label>
                    <div class="input-group">
                      @if(array_key_exists('procedencia_obra', $repeat))
                        <input type="text" class="form-control" name="procedencia_obra" value="{{ $repeat['procedencia_obra'] }}">
                      @else
                        <input type="text" class="form-control" name="procedencia_obra" value="{{ old('procedencia_obra') }}">
                      @endif
                    </div>
                    <small class="text-danger">{{ $errors->first('procedencia_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label>Tombamento</label>
                    <select name="tombamento_obra" class="form-control">
                      @foreach ($tombamentos as $tombamento)
                        @if(array_key_exists('tombamento_id', $repeat))
                          <option value="{{ $tombamento->id }}" {{ $repeat['tombamento_id'] == $tombamento->id ? "selected" : "" }}>{{ $tombamento->titulo_tombamento }}</option>
                        @else
                          @if(old('tombamento_obra') !== null)
                            <option value="{{ $tombamento->id }}" {{ old("tombamento_obra") == $tombamento->id ? "selected" : "" }}>{{ $tombamento->titulo_tombamento }}</option>
                          @else
                            @if($tombamento->is_default_tombamento)
                              <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                            @else
                              <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                            @endif
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Século</label>
                    <select name="seculo_obra" class="form-control">
                      @foreach ($seculos as $seculo)
                        @if(array_key_exists('seculo_id', $repeat))
                          <option value="{{ $seculo->id }}" {{ $repeat['seculo_id'] == $seculo->id ? "selected" : "" }}>{{ $seculo->titulo_seculo }}</option>
                        @else
                          @if(old('seculo_obra') !== null)
                            <option value="{{ $seculo->id }}" {{ old("seculo_obra") == $seculo->id ? "selected" : "" }}>{{ $seculo->titulo_seculo }}</option>
                          @else
                            @if($seculo->is_default_seculo)
                              <option value="{{ $seculo->id }}" selected>{{ $seculo->titulo_seculo }}</option>
                            @else
                              <option value="{{ $seculo->id }}">{{ $seculo->titulo_seculo }}</option>
                            @endif
                          @endif
                        @endif
                      @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('seculo_obra') }}</small>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Ano</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-check-circle text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('ano_obra', $repeat))
                      <input type="number" class="form-control" name="ano_obra" value="{{ $repeat['ano_obra'] }}">
                      @else
                      <input type="number" class="form-control" name="ano_obra" value="{{ old('ano_obra') }}">
                      @endif
                      </div>
                    <small class="text-danger">{{ $errors->first('ano_obra') }}</small>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Estado de Conservação</label>
                    <select name="estado_de_conservacao_obra" class="form-control">
                      @foreach ($estados as $estado)
                        @if(array_key_exists('estado_conservacao_obra_id', $repeat))
                          <option value="{{ $estado->id }}" {{ $repeat['estado_conservacao_obra_id'] == $estado->id ? "selected" : "" }}>{{ $estado->titulo_estado_conservacao_obra }}</option>
                        @else
                          @if(old('estado_de_conservacao_obra') !== null)
                            <option value="{{ $estado->id }}" {{ old("estado_de_conservacao_obra") == $estado->id ? "selected" : "" }}>{{ $estado->titulo_estado_conservacao_obra }}</option>
                          @else
                            @if($estado->is_default_estado_conservacao_obra)
                              <option value="{{ $estado->id }}" selected>{{ $estado->titulo_estado_conservacao_obra }}</option>
                            @else
                              <option value="{{ $estado->id }}">{{ $estado->titulo_estado_conservacao_obra }}</option>
                            @endif
                          @endif
                        @endif
                      @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('estado_de_conservacao_obra') }}</small>

                  </div>
                  <div class="form-group col-md-3">
                    <label>Autoria</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-check-circle text-info"></i>
                        </div>
                      </div>
                      @if(array_key_exists('ano_obra', $repeat))
                        <input type="text" class="form-control" name="autoria_obra" value="{{ $repeat['autoria_obra'] }}">
                      @else
                        <input type="text" class="form-control" name="autoria_obra" value="{{ old('autoria_obra') }}">
                      @endif
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Material 1</label>
                    <select name="material_1_obra" class="form-control select2">
                      <option value="">Selecione um Material</option>
                      @foreach ($materiais as $material)
                        @if(array_key_exists('material_id_1', $repeat))
                          <option value="{{ $material->id }}" {{ $repeat['material_id_1'] == $material->id ? "selected" : "" }}>{{ $material->titulo_material }}</option>
                        @else
                          @if(old('material_1_obra') !== null)
                            <option value="{{ $material->id }}" {{ old("material_1_obra") == $material->id ? "selected" : "" }}>{{ $material->titulo_material }}</option>
                          @else
                            <option value="{{ $material->id }}">{{ $material->titulo_material }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModalMaterial" style="
                    margin-top: 10px;" title="Adicionar Material">+</button>
                    <small class="text-danger">{{ $errors->first('material_1_obra') }}</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Material 2</label>
                    <select name="material_2_obra" class="form-control select2">
                      <option value="">Selecione um Material</option>
                      @foreach ($materiais as $material)
                        @if(array_key_exists('material_id_2', $repeat))
                          <option value="{{ $material->id }}" {{ $repeat['material_id_2'] == $material->id ? "selected" : "" }}>{{ $material->titulo_material }}</option>
                        @else
                          @if(old('material_2_obra') !== null)
                            <option value="{{ $material->id }}" {{ old("material_2_obra") == $material->id ? "selected" : "" }}>{{ $material->titulo_material }}</option>
                          @else
                            <option value="{{ $material->id }}">{{ $material->titulo_material }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Material 3</label>
                    <select name="material_3_obra" class="form-control select2">
                      <option value="">Selecione um Material</option>
                      @foreach ($materiais as $material)
                        @if(array_key_exists('material_id_3', $repeat))
                          <option value="{{ $material->id }}" {{ $repeat['material_id_3'] == $material->id ? "selected" : "" }}>{{ $material->titulo_material }}</option>
                        @else
                          @if(old('material_3_obra') !== null)
                            <option value="{{ $material->id }}" {{ old("material_3_obra") == $material->id ? "selected" : "" }}>{{ $material->titulo_material }}</option>
                          @else
                            <option value="{{ $material->id }}">{{ $material->titulo_material }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Técnica 1</label>
                    <select name="tecnica_1_obra" class="form-control select2">
                      <option value="">Selecione uma Técnica</option>
                      @foreach ($tecnicas as $tecnica)
                        @if(array_key_exists('tecnica_id_1', $repeat))
                          <option value="{{ $tecnica->id }}" {{ $repeat['tecnica_id_1'] == $tecnica->id ? "selected" : "" }}>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                          @if(old('tecnica_1_obra') !== null)
                            <option value="{{ $tecnica->id }}" {{ old("tecnica_1_obra") == $tecnica->id ? "selected" : "" }}>{{ $tecnica->titulo_tecnica }}</option>
                          @else
                            <option value="{{ $tecnica->id }}">{{ $tecnica->titulo_tecnica }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModalTecnica" style="
                    margin-top: 10px;" title="Adicionar Técnica">+</button>
                    <small class="text-danger">{{ $errors->first('tecnica_1_obra') }}</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Técnica 2</label>
                    <select name="tecnica_2_obra" class="form-control select2">
                      <option value="">Selecione uma Técnica</option>
                      @foreach ($tecnicas as $tecnica)
                        @if(array_key_exists('tecnica_id_2', $repeat))
                          <option value="{{ $tecnica->id }}" {{ $repeat['tecnica_id_2'] == $tecnica->id ? "selected" : "" }}>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                          @if(old('tecnica_2_obra') !== null)
                            <option value="{{ $tecnica->id }}" {{ old("tecnica_2_obra") == $tecnica->id ? "selected" : "" }}>{{ $tecnica->titulo_tecnica }}</option>
                          @else
                            <option value="{{ $tecnica->id }}">{{ $tecnica->titulo_tecnica }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Técnica 3</label>
                    <select name="tecnica_3_obra" class="form-control select2">
                      <option value="">Selecione uma Técnica</option>
                      @foreach ($tecnicas as $tecnica)
                        @if(array_key_exists('tecnica_id_3', $repeat))
                          <option value="{{ $tecnica->id }}" {{ $repeat['tecnica_id_3'] == $tecnica->id ? "selected" : "" }}>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                          @if(old('tecnica_3_obra') !== null)
                            <option value="{{ $tecnica->id }}" {{ old("tecnica_3_obra") == $tecnica->id ? "selected" : "" }}>{{ $tecnica->titulo_tecnica }}</option>
                          @else
                            <option value="{{ $tecnica->id }}">{{ $tecnica->titulo_tecnica }}</option>
                          @endif
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Especificações</label>
                    <div style="display: flex; flex-wrap: wrap;">
                      @foreach ($especificacoes as $especificacao)
                        <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                          @if(array_key_exists('checkbox_especificacao_obra', $repeat))
                            <input name="especificacao_obra[]" type="checkbox" style="margin-top: 3px;" value="{{ $especificacao->id }}" id="especificacao_obra_{{ $especificacao->id }}" {{ in_array($especificacao->id, $repeat['checkbox_especificacao_obra']) ? 'checked' : '' }}>
                          @else
                            <input name="especificacao_obra[]" type="checkbox" style="margin-top: 3px;" value="{{ $especificacao->id }}" id="especificacao_obra_{{ $especificacao->id }}" {{ in_array($especificacao->id, old('especificacao_obra',[])) ? 'checked' : '' }}>
                          @endif
                          <div class="state p-success">
                            <label style="margin-left: 10px;" for="especificacao_obra_{{ $especificacao->id }}">{{ $especificacao->titulo_especificacao_obra }}</label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModalEspecificacaoObras" style="
                    margin-top: 10px;" title="Adicionar Especificações e Obras">+</button>
                    <small class="text-danger">{{ $errors->first('especificacao_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Especificações de segurança</label>
                    <div style="display: flex; flex-wrap: wrap;">
                      @foreach ($especificacoesSeg as $especificacaoSeg)
                        <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                          @if(array_key_exists('checkbox_especificacao_seguranca_obra', $repeat))
                            <input name="especificacao_seg_obra[]" type="checkbox" style="margin-top: 3px;" value="{{ $especificacaoSeg->id }}" id="especificacao_seg_obra_{{ $especificacaoSeg->id }}" {{ in_array($especificacaoSeg->id,  $repeat['checkbox_especificacao_seguranca_obra']) ? 'checked' : '' }}>
                          @else
                            <input name="especificacao_seg_obra[]" type="checkbox" style="margin-top: 3px;" value="{{ $especificacaoSeg->id }}" id="especificacao_seg_obra_{{ $especificacaoSeg->id }}" {{ in_array($especificacaoSeg->id, old('especificacao_seg_obra',[])) ? 'checked' : '' }}>
                          @endif
                          <div class="state p-success">
                            <label style="margin-left: 10px;" for="especificacao_seg_obra_{{ $especificacaoSeg->id }}">{{ $especificacaoSeg->titulo_especificacao_seguranca_obra }}</label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModalEspecificacaoSegurancaObras" style="
                    margin-top: 10px;" title="Adicionar Especificações de seguranã de Obras">+</button>
                    <small class="text-danger">{{ $errors->first('especificacao_seg_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Características estilísticas/iconográficas e ornamentais</label>
                    @if(array_key_exists('caracteristicas_est_icono_orna_obra', $repeat))
                      <textarea class="form-control" name="caracteristicas_estilisticas_obra" style="min-height: 200px;">{{ $repeat['caracteristicas_est_icono_orna_obra'] }}</textarea>
                    @else
                      <textarea class="form-control" name="caracteristicas_estilisticas_obra" style="min-height: 200px;">{{ old('caracteristicas_estilisticas_obra') }}</textarea>
                    @endif
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Observações</label>
                    @if(array_key_exists('observacoes_obra', $repeat))
                      <textarea class="form-control" name="observacoes_obra" style="min-height: 200px;">{{ $repeat['observacoes_obra'] }}</textarea>
                    @else
                      <textarea class="form-control" name="observacoes_obra" style="min-height: 200px;">{{ old('observacoes_obra') }}</textarea>
                    @endif
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>Foto Frontal</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_frontal_obra">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_frontal_obra"></div>
                    </div>
                    <input type="hidden" name="usuario_id" value="3">
                  </div>
                  <div class="form-group col-md-3">
                    <label>Foto Lateral Esquerda</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_lateral_esquerda_obra">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <input type="hidden" name="user_foto">
                      <div id="image_holder_lateral_esquerda_obra"></div>
                    </div>
                    <input type="hidden" name="usuario_id" value="3">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>Foto Lateral Direita</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_lateral_direita_obra">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_lateral_direita_obra"></div>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Foto Posterior</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_posterior_obra">
                    </div>
                    <div id="user_foto"></div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_posterior_obra"></div>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Foto Superior</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_superior_obra">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_superior_obra"></div>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Foto Inferior</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_inferior_obra">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_inferior_obra"></div>
                    </div>
                    <input type="hidden" name="usuario_id" value="3">
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary" @if(in_array(strval(auth()->user('id')['id_cargo']), $canOnlyView)) disabled @endif>Salvar</button>
                <a href="{{route('home')}}" class=" btn btn-dark">voltar</a>
              </div>
            </form>
          </div>
        </div>
        <!-- Home da área restrita -->
      </div>
    </div>
  </section>
</div>

{{--}} Inclubde das modeais {{---}}
@include('admin.modal.cadastroLocalicacaoObra')
@include('admin.modal.cadastroTesauro')
@include('admin.modal.cadastroMaterial')
@include('admin.modal.cadastroTecnica')
@include('admin.modal.cadastroEspecificacaoObras')
@include('admin.modal.cadastroEspecificacaoSegurancaObras')
@include('sweetalert::alert')

<script>
  // Parametrização de variáveis
  @foreach ($seculos as $seculo)
    @if ($seculo['is_default_seculo'])
      var min = {{ $seculo['ano_inicio_seculo'] }};
      var max = {{ $seculo['ano_fim_seculo'] }};
    @endif
  @endforeach
  $(document).ready(function() {
    function minMaxAno(){
    // Checa o valor do século e seta o minimo e o máximo
    @foreach ($seculos as $seculo)
        @if ($seculo['titulo_seculo'] != 'Anterior a XVI') else @endif if($('select[name="seculo_obra"]').val() == '{{ $seculo['id'] }}'){
            window.min = {{ $seculo['ano_inicio_seculo'] }};
            window.max = {{ $seculo['ano_fim_seculo'] }};
            }
    @endforeach

        // Seta os valores
        $('input[name="ano_obra"]').attr('max', window.max);
        $('input[name="ano_obra"]').attr('min', window.min);
   }

   $('select[name="seculo_obra"]').change(function() {
        minMaxAno();
   });

   $('input[name="ano_obra"]').bind('keyup mouseup', function (e) {
        if(e.keyCode !== 46 && e.keyCode !== 8 ){
            if (((parseInt($('input[name="ano_obra"]').val()) > window.max) || (parseInt($('input[name="ano_obra"]').val()) < window.min)) && ($('input[name="ano_obra"]').val() != "")) {
                e.preventDefault();
                var errorBox = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    O ano não corresponde ao século selecionado.<br>
                    <span style="margin-left:10px;">Ano mínimo: <b>` + window.min + `</b></span><br>
                    <span style="margin-left:10px;">Ano máximo: <b>` + window.max + `</b></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="notification-warn-mini"></div>`;
            $("#anoerror").html("");
            $("#anoerror").append(errorBox);
            } else {
                $("#anoerror").html("");
            }
        }
    });

    function ajax_sub(control, image_holder){
        //Get count of selected files
        var countFiles = control[0].files.length;
        var imgPath = control[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        image_holder.empty();
        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof(FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "thumb-image",
                            "style": "width:100px; max-height: 200px;"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL(control[0].files[i]);
                }
            } else {
                alert("Este navegador não suporta FileReader.");
            }
        } else {
        alert("Por favor, selecione apenas com formatos válidos.");
        }
    }

    $("input[name='foto_frontal_obra']").on('change', function() {
        ajax_sub($("input[name='foto_frontal_obra']"), $("#image_holder_frontal_obra"));
    });
    $("input[name='foto_lateral_esquerda_obra']").on('change', function() {
        ajax_sub($("input[name='foto_lateral_esquerda_obra']"), $("#image_holder_lateral_esquerda_obra"));
    });
    $("input[name='foto_lateral_direita_obra']").on('change', function() {
        ajax_sub($("input[name='foto_lateral_direita_obra']"), $("#image_holder_lateral_direita_obra"));
    });
      $("input[name='foto_posterior_obra']").on('change', function() {
      ajax_sub($("input[name='foto_posterior_obra']"), $("#image_holder_posterior_obra"));
    });
    $("input[name='foto_superior_obra']").on('change', function() {
      ajax_sub($("input[name='foto_superior_obra']"), $("#image_holder_superior_obra"));
    });
    $("input[name='foto_inferior_obra']").on('change', function() {
      ajax_sub($("input[name='foto_inferior_obra']"), $("#image_holder_inferior_obra"));
    });
  });

</script>

@endsection
