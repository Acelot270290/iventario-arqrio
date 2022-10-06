@extends('layouts.app')

@section('titulo', 'Cadastro de Acervo')

@section('content')

@php
    $allowEdit = ['1', '2'];
    $canOnlyView = [];
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
            <form method="POST" action="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('adicionar_usuario') }} @endif" name="criar_usuario" accept-charset="utf-8" enctype="multipart/form-data">
              @if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) @csrf @endif
              <div class="card-header">
                <h4> Adicionar Usuário </h4>
              </div>
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Nome do usuário</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" name="nome_usuario" value="{{ old('nome_usuario') }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('nome_usuario') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>E-mail</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-map-marker-alt text-info"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control cep" id="email_usuario" name="email_usuario" value="{{ old('email_usuario') }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('email_usuario') }}</small>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Estado</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-road text-info"></i>
                        </div>
                      </div>
                      <select name="estado_usuario" class="form-control">
                        <option value="0" selected> Ativo </option>
                        <option value="1"> Inativo </option>
                      </select>
                    </div>
                     <small class="text-danger">{{ $errors->first('estado_usuario') }}</small>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Cargo</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-road text-info"></i>
                        </div>
                      </div>
                      <select name="cargo_usuario" class="form-control">
                        @foreach ($cargos as $cargo)
                          @if(old('cargo_usuario') !== null)
                            <option value="{{ $cargo->id }}" {{ old("cargo_usuario") == $cargo->id ? "selected" : "" }}>{{ $cargo->nome_cargo }}</option>
                          @else
                            <option value="{{ $cargo->id }}" {{ $cargo->is_default_cargo == 1 ? "selected" : "" }}>{{ $cargo->nome_cargo }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                     <small class="text-danger">{{ $errors->first('estado_usuario') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>A quais acervos este usuário tem acesso?</label>
                    <div style="display: flex; flex-wrap: wrap;">
                      <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                        <input name="acesso_acervo_usuario[]" type="checkbox" style="margin-top: 3px;" value="0" id="acesso_acervo_usuario_all">
                        <div class="state p-success">
                          <label style="margin-left: 10px;" for="acesso_acervo_usuario_all">Todos acervos</label>
                        </div>
                      </div>
                      @foreach ($acervos as $acervo)
                        <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                            <input name="acesso_acervo_usuario[]" type="checkbox" style="margin-top: 3px;" value="{{ $acervo->id }}" id="acesso_acervo_usuario_{{ $acervo->id }}" {{ in_array($acervo->id, old('acesso_acervo_usuario', [])) ? 'checked' : '' }} class="notall">
                          <div class="state p-success">
                            <label style="margin-left: 10px;" for="acesso_acervo_usuario_{{ $acervo->id }}">{{ $acervo->id }} - {{ $acervo->nome_acervo }}</label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <small class="text-danger">{{ $errors->first('acesso_acervo_usuario') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Foto</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-image text-info"></i>
                        </div>
                      </div>
                      <input type="file" class="form-control" name="foto_usuario">
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <div id="box-foto-usuario">
                      <div id="image_holder_user"></div>
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

  $("input[name='foto_usuario']").on('change', function() {
    ajax_sub($("input[name='foto_usuario']"), $("#image_holder_user"));
  });

  $("#acesso_acervo_usuario_all").on('change', function() {
    if($("#acesso_acervo_usuario_all").is(":checked")) {
      $("input.notall").attr("disabled", true);
      $("input.notall").prop("checked", false);
    } else {
      $("input.notall").removeAttr("disabled");
    }
  });
});

</script>

@endsection
