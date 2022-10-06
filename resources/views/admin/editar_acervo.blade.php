@extends('layouts.app')

@section('titulo', "Editar Acervo - ID: " . $acervo['id'])

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
  @if(!in_array('0', $accesses) and !in_array(strval($acervo['id']), $accesses))
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
            <form method="POST" action="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('atualizar_acervo', ['id' => $acervo['id']]) }} @endif"
              name="atualizar_acervo" accept-charset="utf-8" enctype="multipart/form-data">
              @if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) @csrf @endif
              <div class="card-header">
                <h4> Editar Acervo {{ $acervo['nome_acervo'] }}</h4>
              </div>
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-md-8">
                    <label>Nome do monumento</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" name="nome_acervo"
                        value="{{ old('nome_acervo') !== null ? old('nome_acervo') : $acervo['nome_acervo'] }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('nome_acervo') }}</small>
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
                        value="{{ $acervo['usuario_cadastrante'] }}" readonly>
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
                        value="{{ $acervo['criado_em'] }}" readonly>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label>CEP</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-map-marker-alt text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control cep" id="cep_acervo" name="cep_acervo"
                        value="{{ old('cep_acervo') !== null ? old('cep_acervo') : $acervo['cep_acervo'] }}"
                        maxlength="9">
                    </div>
                    <small class="text-danger">{{ $errors->first('cep_acervo') }}</small>
                  </div>
                  <div class="form-group col-md-8">
                    <label>Endereço</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-road text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" id="endereco_acervo" name="endereco_acervo"
                        value="{{ old('endereco_acervo') !== null ? old('endereco_acervo') : $acervo['endereco_acervo'] }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('endereco_acervo') }}</small>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Número</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-street-view text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" id="numero_endereco_acervo" name="numero_endereco_acervo"
                        value="{{ old('numero_endereco_acervo') !== null ? old('numero_endereco_acervo') : $acervo['numero_endereco_acervo'] }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('numero_endereco_acervo') }}</small>
                  </div>
                  <div class="form-row">
                  </div>
                  <div class="form-group col-md-4">
                    <label>Bairro</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-directions text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" id="bairro_acervo" name="bairro_acervo"
                        value="{{ old('bairro_acervo') !== null ? old('bairro_acervo') : $acervo['bairro_acervo'] }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('bairro_acervo') }}</small>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Cidade</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-location-arrow text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" id="cidade_acervo" name="cidade_acervo"
                        value="{{ old('cidade_acervo') !== null ? old('cidade_acervo') : $acervo['cidade_acervo'] }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('cidade_acervo') }}</small>

                  </div>
                  <div class="form-group col-md-2">
                    <label>Estado</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-map text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control uf" id="UF_acervo" name="UF_acervo"
                        value="{{ old('UF_acervo') !== null ? old('UF_acervo') : $acervo['UF_acervo'] }}" maxlength="2">
                    </div>
                    <small class="text-danger">{{ $errors->first('UF_acervo') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>Tombamento</label>
                    <select name="tombamento_acervo" class="form-control">
                      @foreach ($tombamentos as $tombamento)
                      @if (old('tombamento_acervo') == $tombamento->id)
                      <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                      @else
                      @if($tombamento->id == $acervo['tombamento_id'])
                      <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                      @else
                      @if($tombamento->is_default_tombamento)
                      <option value="{{ $tombamento->id }}" selected>{{ $tombamento->titulo_tombamento }}</option>
                      @else
                      <option value="{{ $tombamento->id }}">{{ $tombamento->titulo_tombamento }}</option>
                      @endif
                      @endif
                      @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Século</label>
                    <select name="seculo_acervo" class="form-control">
                      @foreach ($seculos as $seculo)

                      @if (old('seculo_acervo') == $seculo->id)
                      <option value="{{ $seculo->id }}" selected>{{ $seculo->titulo_seculo }}</option>
                      @else
                      @if($seculo->id == $acervo['seculo_id'])
                      <option value="{{ $seculo->id }}" selected>{{ $seculo->titulo_seculo }}</option>
                      @else
                      @if($seculo->is_default_seculo and !isset($acervo['seculo_id']))
                      <option value="{{ $seculo->id }}" selected>{{ $seculo->titulo_seculo }}</option>
                      @else
                      <option value="{{ $seculo->id }}">{{ $seculo->titulo_seculo }}</option>
                      @endif
                      @endif
                      @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Ano de Construção</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-check-circle text-info"></i>
                        </div>
                      </div>
                      <input type="number" class="form-control" name="ano_acervo"
                        value="{{ old('ano_acervo') !== null ? old('ano_acervo') : $acervo['ano_construcao_acervo'] }}">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Estado de Conservação</label>
                    <select name="estado_conservacao_acervo" class="form-control">
                      @foreach ($estados as $estado)
                      @if (old('estado_conservacao_acervo') == $estado->id)
                      <option value="{{ $estado->id }}" selected>{{ $estado->titulo_estado_conservacao_acervo }}
                      </option>
                      @else
                      @if($estado->id == $acervo['estado_conservacao_acervo_id'])
                      <option value="{{ $estado->id }}" selected>{{ $estado->titulo_estado_conservacao_acervo }}
                      </option>
                      @else
                      @if($estado->is_default_estado_conservacao_acervo and
                      !isset($acervo['estado_conservacao_acervo_id']))
                      <option value="{{ $estado->id }}" selected>{{ $estado->titulo_estado_conservacao_acervo }}
                      </option>
                      @else
                      <option value="{{ $estado->id }}">{{ $estado->titulo_estado_conservacao_acervo }}</option>
                      @endif
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
                        @if(in_array($especificacao->id, old('especificacao_acervo',[])))
                        <input name="especificacao_acervo[]" type="checkbox" style="margin-top: 3px;"
                          value="{{ $especificacao->id }}" id="{{ $especificacao->id }}" {{
                          old('especificacao_acervo')==$especificacao->id ? 'checked' : '' }}>
                        @else
                        @if(is_array($check) and in_array($especificacao->id, $check) and old('especificacao_acervo') ==
                        null)
                        <input name="especificacao_acervo[]" type="checkbox" style="margin-top: 3px;"
                          value="{{ $especificacao->id }}" id="{{ $especificacao->id }}" checked>
                        @else
                        <input name="especificacao_acervo[]" type="checkbox" style="margin-top: 3px;"
                          value="{{ $especificacao->id }}" id="{{ $especificacao->id }}">
                        @endif
                        @endif
                        <div class="state p-success">
                          <label style="margin-left: 10px;" for="{{ $especificacao->id }}">{{
                            $especificacao->titulo_especificacao_acervo }}</label>
                        </div>
                      </div>
                      @endforeach
                      <small class="text-danger">{{ $errors->first('especificacao_acervo') }}</small>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Breve descrição da fachada e planta</label>
                    <textarea class="form-control" name="descricao_acervo"
                      style="min-height: 200px;">{{ old('descricao_acervo') !== null ? old('descricao_acervo') : $acervo['descricao_fachada_planta_acervo'] }}</textarea>
                    <div>
                      <small class="text-danger">{{ $errors->first('descricao_acervo') }}</small>
                    </div>
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
                      <input type="file" class="form-control" name="foto_frontal_acervo">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="image_holder_frontal_acervo">
                      @if($acervo['foto_frontal_acervo'])
                      <img src="{{ asset($acervo['foto_frontal_acervo']) }}" class="thumb-image"
                        style="width:100px; max-height: 200px;">
                      @endif
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Foto Lateral Esquerda</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_lateral_1_acervo">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_lateral1_acervo">
                        @if($acervo['foto_lateral_1_acervo'])
                        <img src="{{ asset($acervo['foto_lateral_1_acervo']) }}" class="thumb-image"
                          style="width:100px; max-height: 200px;">
                        @endif
                      </div>
                    </div>
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
                      <input type="file" class="form-control" name="foto_lateral_2_acervo">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_lateral2_acervo">
                        @if($acervo['foto_lateral_2_acervo'])
                        <img src="{{ asset($acervo['foto_lateral_2_acervo']) }}" class="thumb-image"
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
                      <input type="file" class="form-control" name="foto_posterior_acervo">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_posterior_acervo">
                        @if($acervo['foto_posterior_acervo'])
                        <img src="{{ asset($acervo['foto_posterior_acervo']) }}" class="thumb-image"
                          style="width:100px; max-height: 200px;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>Foto Cobertura</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_cobertura_acervo">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_cobertura_acervo">
                        @if($acervo['foto_cobertura_acervo'])
                        <img src="{{ asset($acervo['foto_cobertura_acervo']) }}" class="thumb-image"
                          style="width:100px; max-height: 200px;">
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Foto Planta/Situação</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="plantas_situacao_acervo">
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <div id="box-foto-usuario">
                      <div id="image_holder_plantas_situcao_acervo">
                        @if($acervo['plantas_situacao_acervo'])
                        <img src="{{ asset($acervo['plantas_situacao_acervo']) }}" class="thumb-image"
                          style="width:100px; max-height: 200px;">
                        @endif
                      </div>
                    </div>
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
  @foreach ($seculos as $seculo)
    @if ($seculo['is_default_seculo'])
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

        $("input[name='foto_frontal_acervo']").on('change', function() {
          ajax_sub($("input[name='foto_frontal_acervo']"), $("#image_holder_frontal_acervo"));
        });
        $("input[name='foto_lateral_1_acervo']").on('change', function() {
          ajax_sub($("input[name='foto_lateral_1_acervo']"), $("#image_holder_lateral1_acervo"));
        });
         $("input[name='foto_lateral_2_acervo']").on('change', function() {
          ajax_sub($("input[name='foto_lateral_2_acervo']"), $("#image_holder_lateral2_acervo"));
        });
         $("input[name='foto_posterior_acervo']").on('change', function() {
          ajax_sub($("input[name='foto_posterior_acervo']"), $("#image_holder_posterior_acervo"));
        });
        $("input[name='foto_cobertura_acervo']").on('change', function() {
          ajax_sub($("input[name='foto_cobertura_acervo']"), $("#image_holder_cobertura_acervo"));
        });
         $("input[name='plantas_situacao_acervo']").on('change', function() {
          ajax_sub($("input[name='plantas_situacao_acervo']"), $("#image_holder_plantas_situcao_acervo"));
        });
      });
</script>
@endif
@endif
@endsection
