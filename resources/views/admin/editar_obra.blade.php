@extends('layouts.app')

@section('titulo', "Editar Obra - ID: " . $obra['id'])

@section('content')

@php
    $allowEdit = ['1', '2', '4', '5'];
    $canOnlyView = ['6'];
@endphp

@if(is_null(auth()->user('id')['acesso_acervos']))
  <script>window.location = "/unauthorized";</script>
@else
  @php
    $accesses = explode(',', auth()->user('id')['acesso_acervos']);
  @endphp
  @if(!in_array('0', $accesses) and !in_array(strval($obra['acervo_id']), $accesses))
    <script>window.location = "/unauthorized";</script>
  @else
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
              <form method="POST" action="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('atualizar_obra', ['id' => $obra['id']]) }} @endif" name="atualizar_acervo"
                accept-charset="utf-8" enctype="multipart/form-data">
                @if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) @csrf @endif
                <div class="card-header">
                  <h4> Editar Obra (ID: {{ $obra['id'] }}) </h4>
                </div>
                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                        @if(old('obra_provisoria') == "1")
                          <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;"
                                value="1" id="obra_provisoria" checked>
                        @else
                        @if($obra['obra_provisoria'] == 1)
                          <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;"
                                value="1" id="obra_provisoria" checked>
                        @else
                          <input name="obra_provisoria" type="checkbox" style="margin-top: 3px;"
                                value="1" id="obra_provisoria">
                        @endif
                        @endif
                        <div class="state p-success">
                          <label style="margin-left: 10px;" for="obra_provisoria">Obra provisória</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Categoria da obra</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-user text-info"></i>
                          </div>
                        </div>
                        <select name="categoria_obra" class="form-control">
                          @foreach ($categorias as $categoria)
                          @if (old('categoria_obra') == $categoria->id)
                          <option value="{{ $categoria->id }}" selected>{{ $categoria->titulo_categoria }}</option>
                          @else
                          @if ($categoria->id == intval($obra['categoria_id']))
                          <option value="{{ $categoria->id }}" selected>{{ $categoria->titulo_categoria }}</option>
                          @else
                          @if ($categoria->is_default_categoria)
                          <option value="{{ $categoria->id }}" selected>{{ $categoria->titulo_categoria }}</option>
                          @else
                          <option value="{{ $categoria->id }}">{{ $categoria->titulo_categoria }}</option>
                          @endif
                          @endif
                          @endif
                          @endforeach
                        </select>
                      </div>
                      <small class="text-danger">{{ $errors->first('categoria_obra') }}</small>
                    </div>
                    <div class="form-group col-md-4">
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
                          @if (old('acervo_obra') == $acervo->id)
                          <option value="{{ $acervo->id }}" selected>{{ $acervo->nome_acervo }}</option>
                          @else
                          @if ($acervo->id == intval($obra['acervo_id']))
                          <option value="{{ $acervo->id }}" selected>{{ $acervo->nome_acervo }}</option>
                          @else
                          @if ($acervo->is_default_acervo)
                          <option value="{{ $acervo->id }}" selected>{{ $acervo->nome_acervo }}</option>
                          @else
                          <option value="{{ $acervo->id }}">{{ $acervo->nome_acervo }}</option>
                          @endif
                          @endif
                          @endif
                          @endforeach
                        </select>
                      </div>
                      <small class="text-danger">{{ $errors->first('acervo_obra') }}</small>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Cadastrado por</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-user text-info"></i>
                          </div>
                        </div>
                        <input type="text" class="form-control" name="cadastrante_obra"
                          value="{{ $obra['usuario_cadastrante'] }}" readonly>
                      </div>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Cadastrado em</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-user text-info"></i>
                          </div>
                        </div>
                        <input type="text" class="form-control" name="data_cadastro_obra"
                          value="{{ $obra['criado_em'] }}" readonly>
                      </div>
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
                        <input type="text" class="form-control" name="titulo_obra"
                          value="{{ old('titulo_obra') !== null ? old('titulo_obra') : $obra['titulo_obra'] }}">
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
                        <input type="number" class="form-control" name="altura_obra"
                          value="{{ old('altura_obra') !== null ? old('altura_obra') : $obra['altura_obra'] }}">
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
                        <input type="number" class="form-control" name="largura_obra"
                          value="{{ old('largura_obra') !== null ? old('largura_obra') : $obra['largura_obra'] }}">
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
                        <input type="number" class="form-control" name="profundidade_obra"
                          value="{{ old('profundidade_obra') !== null ? old('profundidade_obra') : $obra['profundidade_obra'] }}">
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
                        <input type="number" class="form-control" name="comprimento_obra"
                          value="{{ old('comprimento_obra') !== null ? old('comprimento_obra') : $obra['comprimento_obra'] }}">
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
                        <input type="number" class="form-control" name="diametro_obra" value='{{ old(' diametro_obra')
                          !==null ? old('diametro_obra') : $obra['diametro_obra'] }}'>
                      </div>
                      <small class="text-danger">{{ $errors->first('diâmetro_obra') }}</small>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-3">
                      <label>Tesauro</label>
                      <select name="tesauro_obra" class="form-control select2">
                        <option value="">Selecione um Tesauro</option>
                        @foreach ($tesauros as $tesauro)
                        @if(old('tesauro_obra') == $tesauro->id)
                        <option value="{{ $tesauro->id }}" {{ old("tesauro_obra")==$tesauro->id ? "selected" : "" }}>{{
                          $tesauro->titulo_tesauro }}</option>
                        @else
                        @if($tesauro->id == $obra['tesauro_id'] and old('tesauro_obra') == null)
                        <option value="{{ $tesauro->id }}" selected>{{ $tesauro->titulo_tesauro }}</option>
                        @else
                        <option value="{{ $tesauro->id }}">{{ $tesauro->titulo_tesauro }}</option>
                        @endif
                        @endif
                        @endforeach
                      </select>
                      <small class="text-danger">{{ $errors->first('tesauro_obra') }}</small>
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
                          <option value="">Selecione uma Localização</option>
                          @foreach ($localizacoes as $localizacao)
                          @if (old('localizacao_obra') == $localizacao->id)
                          <option value="{{ $localizacao->id }}" selected>{{ $localizacao->nome_localizacao }}</option>
                          @else
                          @if ($localizacao->id == $obra['localizacao_obra_id'] and old('localizacao_obra') == null)
                          <option value="{{ $localizacao->id }}" selected>{{ $localizacao->nome_localizacao }}</option>
                          @else
                          <option value="{{ $localizacao->id }}">{{ $localizacao->nome_localizacao }}</option>
                          @endif
                          @endif
                          @endforeach
                        </select>
                      </div>
                      <small class="text-danger">{{ $errors->first('localizacao_obra') }}</small>
                    </div>
                    <div class="form-group col-md-3">
                      <label>Condições de Segurança</label>
                      <select name="condicao_seguranca_obra" class="form-control">
                        @foreach ($condicoes as $condicao)
                        @if (old('condicao_seguranca_obra') == $condicao->id)
                        <option value="{{ $condicao->id }}" selected>{{ $condicao->titulo_condicao_seguranca_obra }}
                        </option>
                        @else
                        @if ($condicao->id == $obra['condicoes_de_seguranca_obra_id'] and old('condicao_seguranca_obra')
                        == null)
                        <option value="{{ $condicao->id }}" selected>{{ $condicao->titulo_condicao_seguranca_obra }}
                        </option>
                        @else
                        <option value="{{ $condicao->id }}">{{ $condicao->titulo_condicao_seguranca_obra }}</option>
                        @endif
                        @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label>Origem/Procedência</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="procedencia_obra" value="{{ old('titulo_procedencia_obraobra') !== null ? old('procedencia_obra') : $obra['procedencia_obra'] }}">
                      </div>
                      <small class="text-danger">{{ $errors->first('procedencia_obra') }}</small>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <label>Tombamento</label>
                      <select name="tombamento_obra" class="form-control select2">
                        <option value="">Selecione um Tombamento</option>
                        @foreach ($tombamentos as $tombamento)
                        @if (old('tombamento_obra') == $tombamento->id)
                        <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                        @else
                        @if ($tombamento->id == $obra['tombamento_id'] and old('tombamento_obra') == null)
                        <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                        @else
                        <option value="{{ $tombamento->id }}">{{ $tombamento->titulo_tombamento }}</option>
                        @endif
                        @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Século</label>
                      <select name="seculo_obra" class="form-control">
                        @foreach ($seculos as $seculo)
                        @if (old('seculo_obra') == $seculo->id)
                        <option value="{{ $seculo->id }}" selected>{{ $seculo->titulo_seculo }}</option>
                        @else
                        @if ($seculo->id == $obra['seculo_id'] and old('seculo_obra') == null)
                        <option value="{{ $seculo->id }}" selected>{{ $seculo->titulo_seculo }}</option>
                        @else
                        <option value="{{ $seculo->id }}">{{ $seculo->titulo_seculo }}</option>
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
                        <input type="number" class="form-control" name="ano_obra"
                          value="{{ old('ano_obra') !== null ? old('ano_obra') : $obra['ano_obra'] }}">
                      </div>
                      <small class="text-danger">{{ $errors->first('ano_obra') }}</small>
                    </div>
                    <div class="form-group col-md-3">
                      <label>Estado de Conservação</label>
                      <select name="estado_de_conservacao_obra" class="form-control">
                        @foreach ($estados as $estado)
                        @if (old('estado_de_conservacao_obra') == $estado->id)
                        <option value="{{ $estado->id }}" selected>{{ $estado->titulo_estado_conservacao_obra }}</option>
                        @else
                        @if ($estado->id == $obra['estado_conservacao_obra_id'] and old('estado_de_conservacao_obra') ==
                        null)
                        <option value="{{ $estado->id }}" selected>{{ $estado->titulo_estado_conservacao_obra }}</option>
                        @else
                        <option value="{{ $estado->id }}">{{ $estado->titulo_estado_conservacao_obra }}</option>
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
                        <input type="text" class="form-control" name="autoria_obra"
                          value="{{ old('autoria_obra') !== null ? old('autoria_obra') : $obra['autoria_obra'] }}">
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Material 1</label>
                      <select name="material_1_obra" class="form-control select2">
                        <option value="">Selecione um Material</option>
                        @foreach ($materiais as $material)
                        @if (old('material_1_obra') == $material->id)
                        <option value="{{ $material->id }}" selected>{{ $material->titulo_material }}</option>
                        @else
                        @if($material->id == $obra['material_id_1'] and old('material_1_obra') == null)
                        <option value="{{ $material->id }}" selected>{{ $material->titulo_material }}</option>
                        @else
                        <option value="{{ $material->id }}">{{ $material->titulo_material }}</option>
                        @endif
                        @endif
                        @endforeach
                      </select>
                      <small class="text-danger">{{ $errors->first('material_1_obra') }}</small>
                    </div>
                    <div class="form-group col-md-4">
                      <label>Material 2</label>
                      <select name="material_2_obra" class="form-control select2">
                        <option value="">Selecione um Material</option>
                        @foreach ($materiais as $material)
                        @if (old('material_2_obra') == $material->id)
                        <option value="{{ $material->id }}" selected>{{ $material->titulo_material }}</option>
                        @else
                        @if($material->id == $obra['material_id_2'] and old('material_2_obra') == null)
                        <option value="{{ $material->id }}" selected>{{ $material->titulo_material }}</option>
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
                        @if(old('material_3_obra') == $material->id)
                        <option value="{{ $material->id }}" selected>{{ $material->titulo_material }}</option>
                        @else
                        @if($material->id == $obra['material_id_3'] and old('material_3_obra') == null)
                        <option value="{{ $material->id }}" selected>{{ $material->titulo_material }}</option>
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
                        @if (old('tecnica_1_obra') == $tecnica->id)
                        <option value="{{ $tecnica->id }}" selected>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                        @if($tecnica->id == $obra['tecnica_id_1'] and old('tecnica_1_obra') == null)
                        <option value="{{ $tecnica->id }}" selected>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                        <option value="{{ $tecnica->id }}">{{ $tecnica->titulo_tecnica }}</option>
                        @endif
                        @endif
                        @endforeach
                      </select>
                      <small class="text-danger">{{ $errors->first('tecnica_1_obra') }}</small>

                    </div>
                    <div class="form-group col-md-4">
                      <label>Técnica 2</label>
                      <select name="tecnica_2_obra" class="form-control select2">
                        <option value="">Selecione uma Técnica</option>
                        @foreach ($tecnicas as $tecnica)
                        @if (old('tecnica_2_obra') == $tecnica->id)
                        <option value="{{ $tecnica->id }}" selected>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                        @if($tecnica->id == $obra['tecnica_id_2'] and old('tecnica_2_obra') == null)
                        <option value="{{ $tecnica->id }}" selected>{{ $tecnica->titulo_tecnica }}</option>
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
                        @if (old('tecnica_3_obra') == $tecnica->id)
                        <option value="{{ $tecnica->id }}" selected>{{ $tecnica->titulo_tecnica }}</option>
                        @else
                        @if($tecnica->id == $obra['tecnica_id_3'] and old('tecnica_3_obra') == null)
                        <option value="{{ $tecnica->id }}" selected>{{ $tecnica->titulo_tecnica }}</option>
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
                          @if(is_array(old('especificacao_obra')) and in_array($especificacao->id,
                          old('especificacao_obra')))
                          <input name="especificacao_obra[]" type="checkbox" style="margin-top: 3px;"
                            value="{{ $especificacao->id }}" id="especificacao_obra_{{ $especificacao->id }}" checked>
                          @else
                          @if(in_array($especificacao->id, $check) and old('especificacao_obra') == null)
                          <input name="especificacao_obra[]" type="checkbox" style="margin-top: 3px;"
                            value="{{ $especificacao->id }}" id="especificacao_obra_{{ $especificacao->id }}" checked>
                          @else
                          <input name="especificacao_obra[]" type="checkbox" style="margin-top: 3px;"
                            value="{{ $especificacao->id }}" id="especificacao_obra_{{ $especificacao->id }}">
                          @endif
                          @endif
                          <div class="state p-success">
                            <label style="margin-left: 10px;" for="especificacao_obra_{{ $especificacao->id }}">{{
                              $especificacao->titulo_especificacao_obra }}</label>
                          </div>
                        </div>
                        @endforeach
                      </div>
                      <small class="text-danger">{{ $errors->first('especificacao_obra') }}</small>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label>Especificações de segurança</label>
                      <div style="display: flex; flex-wrap: wrap;">
                        @foreach ($especificacoesSeg as $especificacaoSeg)
                        <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                          @if(is_array(old('especificacao_seg_obra')) and in_array($especifiespecificacaoSegcacao->id,
                          old('especificacao_seg_obra')))
                          <input name="especificacao_seg_obra[]" type="checkbox" style="margin-top: 3px;"
                            value="{{ $especificacaoSeg->id }}" id="especificacao_seg_obra_{{ $especificacaoSeg->id }}"
                            checked>
                          @else
                          @if(in_array($especificacaoSeg->id, $checkSeg) and old('especificacao_seg_obra') == null)
                          <input name="especificacao_seg_obra[]" type="checkbox" style="margin-top: 3px;"
                            value="{{ $especificacaoSeg->id }}" id="especificacao_seg_obra_{{ $especificacaoSeg->id }}"
                            checked>
                          @else
                          <input name="especificacao_seg_obra[]" type="checkbox" style="margin-top: 3px;"
                            value="{{ $especificacaoSeg->id }}" id="especificacao_seg_obra_{{ $especificacaoSeg->id }}">
                          @endif
                          @endif
                          <div class="state p-success">
                            <label style="margin-left: 10px;" for="especificacao_seg_obra_{{ $especificacaoSeg->id }}">{{
                              $especificacaoSeg->titulo_especificacao_seguranca_obra }}</label>
                          </div>
                        </div>
                        @endforeach
                      </div>
                      <small class="text-danger">{{ $errors->first('especificacao_seg_obra') }}</small>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label>Características estilísticas/iconográficas e ornamentais</label>
                      <textarea class="form-control" name="caracteristicas_estilisticas_obra"
                        style="min-height: 200px;">{{ old('caracteristicas_estilisticas_obra') !== null ? old('caracteristicas_estilisticas_obra') : $obra['caracteristicas_est_icono_orna_obra'] }}</textarea>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label>Observações</label>
                      <textarea class="form-control" name="observacoes_obra"
                        style="min-height: 200px;">{{ old('observacoes_obra') !== null ? old('observacoes_obra') : $obra['observacoes_obra'] }}</textarea>
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
                        <div id="image_holder_frontal_obra">
                          @if($obra['foto_frontal_obra'])
                          <img src="{{ asset($obra['foto_frontal_obra']) }}" class="thumb-image"
                            style="width:100px; max-height: 200px;">
                          @endif
                        </div>
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
                        <div id="foto_holder_lateral_esquerda_obra">
                          @if($obra['foto_lateral_esquerda_obra'])
                          <img src="{{ asset($obra['foto_lateral_esquerda_obra']) }}" class="thumb-image"
                            style="width:100px; max-height: 200px;">
                          @endif
                        </div>
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
                        <div id="foto_holder_lateral_direita_obra">
                          @if($obra['foto_lateral_direita_obra'])
                          <img src="{{ asset($obra['foto_lateral_direita_obra']) }}" class="thumb-image"
                            style="width:100px; max-height: 200px;">
                          @endif
                        </div>
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
                        <div id="foto_holder_posterior_obra">
                          @if($obra['foto_posterior_obra'])
                          <img src="{{ asset($obra['foto_posterior_obra']) }}" class="thumb-image"
                            style="width:100px; max-height: 200px;">
                          @endif
                        </div>
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
                        <div id="foto_holder_superior_obra">
                          @if($obra['foto_superior_obra'])
                          <img src="{{ asset($obra['foto_superior_obra']) }}" class="thumb-image"
                            style="width:100px; max-height: 200px;">
                          @endif
                        </div>
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
                        <div id="foto_holder_inferior_obra">
                          @if($obra['foto_inferior_obra'])
                          <img src="{{ asset($obra['foto_inferior_obra']) }}" class="thumb-image"
                            style="width:100px; max-height: 200px;">
                          @endif
                        </div>
                      </div>
                      <input type="hidden" name="usuario_id" value="3">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" @if(in_array(strval(auth()->user('id')['id_cargo']), $canOnlyView)) disabled @endif>Salvar</button>
                  <a href="{{ route('home') }}" class=" btn btn-dark">voltar</a>
                </div>
              </form>
            </div>
          </div>
          <!-- Home da área restrita -->
        </div>
      </div>
    </section>
  </div>

  <script>
    $(document).ready(function() {

              function limpa_formulário_cep() {
                  // Limpa valores do formulário de cep.
                  $("#endereco_acervo").val("");
                  $("#bairro_acervo").val("");
                  $("#cidade_acervo").val("");
                  $("#UF_acervo").val("");
              }

              //Quando o campo cep perde o foco.
              $("#cep_acervo").blur(function() {

                  //Nova variável "cep" somente com dígitos.
                  var cep = $(this).val().replace(/\D/g, '');

                  //Verifica se campo cep possui valor informado.
                  if (cep != "") {

                      //Expressão regular para validar o CEP.
                      var validacep = /^[0-9]{8}$/;

                      //Valida o formato do CEP.
                      if(validacep.test(cep)) {

                          //Consulta o webservice viacep.com.br/
                          $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                              if (!("erro" in dados)) {
                                  //Atualiza os campos com os valores da consulta.
                                  $("#endereco_acervo").val(dados.logradouro);
                                  $("#bairro_acervo").val(dados.bairro);
                                  $("#cidade_acervo").val(dados.localidade);
                                  $("#UF_acervo").val(dados.uf);
                              } //end if.
                              else {
                                  //CEP pesquisado não foi encontrado.
                                  limpa_formulário_cep();
                                  alert("CEP não encontrado.");
                              }
                          });
                      } //end if.
                      else {
                          //cep é inválido.
                          limpa_formulário_cep();
                          alert("Formato de CEP inválido.");
                      }
                  } //end if.
                  else {
                      //cep sem valor, limpa formulário.
                      limpa_formulário_cep();
                  }
              });
          });

  // Parametrização de variáveis
    @foreach($seculos as $seculo)
      @if($seculo['is_default_seculo'])
        var min = {{ $seculo['ano_inicio_seculo'] }};
        var max = {{ $seculo['ano_fim_seculo'] }};
        var anoOk = false;
      @endif
    @endforeach

     function minMaxAno(){
       // Checa o valor do século e seta o minimo e o máximo
      @foreach ($seculos as $seculo)
        @if ($seculo['titulo_seculo'] != 'Anterior a XVI') else @endif if($('select[name="seculo_acervo"]').val() == '{{ $seculo['id'] }}'){
    window.min = {{ $seculo['ano_inicio_seculo'] }};
    window.max = {{ $seculo['ano_fim_seculo'] }};
  }
      @endforeach

       // Seta os valores
       $('input[name="ano_acervo"]').attr('max', window.max);
       $('input[name="ano_acervo"]').attr('min', window.min);
     }

     $('select[name="seculo_acervo"]').change(function() {
       minMaxAno();
     });

     $('input[name="ano_acervo"]').bind('keyup mouseup', function (e) {
       if(e.keyCode !== 46 // keycode for delete
                && e.keyCode !== 8 // keycode for backspace
              ){
           if (((parseInt($('input[name="ano_acervo"]').val()) > window.max) || (parseInt($('input[name="ano_acervo"]').val()) < window.min)) && ($('input[name="ano_acervo"]').val() != "")) {
            e.preventDefault();
            var errorBox = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
            O ano não corresponde ao século selecionado.<br>
            <span style="margin-left:10px;">Ano mínimo: <b>` + window.min + `</b></span><br>
            <span style="margin-left:10px;">Ano máximo: <b>` + window.max + `</b></span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div id="notification-warn-mini"></div>`; @php #` @endphp
            $("#anoerror").html("");
            $("#anoerror").append(errorBox);
            window.anoOk = true;
           } else {
             $("#anoerror").html("");
            window.anoOk = False;
           }
       }
     });

     $(document).ready(function() {
          function ajax_sub(control, image_holder){
            //Get count of selected files
            var countFiles = control[0].files.length;
            var imgPath = control[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
              if (typeof(FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++)
                {
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
            ajax_sub($("input[name='foto_lateral_esquerda_obra']"), $("#foto_holder_lateral_esquerda_obra"));
          });
           $("input[name='foto_lateral_direita_obra']").on('change', function() {
            ajax_sub($("input[name='foto_lateral_direita_obra']"), $("#foto_holder_lateral_direita_obra"));
          });
           $("input[name='foto_posterior_obra']").on('change', function() {
            ajax_sub($("input[name='foto_posterior_obra']"), $("#foto_holder_posterior_obra"));
          });
          $("input[name='foto_superior_obra']").on('change', function() {
            ajax_sub($("input[name='foto_superior_obra']"), $("#foto_holder_superior_obra"));
          });
           $("input[name='foto_inferior_obra']").on('change', function() {
            ajax_sub($("input[name='foto_inferior_obra']"), $("#foto_holder_inferior_obra"));
          });
        });

  </script>
  @endif
@endif



@endsection
