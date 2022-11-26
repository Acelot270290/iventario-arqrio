@extends('layouts.app')

@section('titulo', 'Buscar Obras')

@section('content')

<div class="busca-obras-publico" style="min-height: 562px;">
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
            <form id="busca_form" type="POST" action="" name="criar_obra" accept-charset="utf-8" enctype="multipart/form-data">
              @csrf
              <div class="card-header">
                <h4> Busca Obras </h4>
              </div>
              <div class="card-body">
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
                        <option value="" {{ old("categoria_obra") == null ? "selected" : "" }}>-</option>
                        @foreach ($categorias as $categoria)
                          @if($categoria->id == old('categoria_obra'))
                            <option value="{{ $categoria->id }}" selected>{{ $categoria->titulo_categoria }}</option>
                          @else
                            <option value="{{ $categoria->id }}">{{ $categoria->titulo_categoria }}</option>
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
                        <option value="" {{ old("acervo_obra") == null ? "selected" : "" }}>-</option>
                        @foreach ($acervos as $acervo)
                        @if($acervo->id == old('acervo_obra'))
                        <option value="{{ $acervo->id }}" selected>{{ $acervo->nome_acervo }}</option>
                        @else
                        @if($acervo->is_default_acervo)
                        <option value="{{ $acervo->id }}" selected>{{ $acervo->nome_acervo }}</option>
                        @else
                        <option value="{{ $acervo->id }}">{{ $acervo->nome_acervo }}</option>
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
                  <div class="form-group col-md-4">
                    <label>Tesauro</label>
                    <select name="tesauro_obra" class="form-control select2">
                      <option value="" {{ old("tesauro_obra") == null ? "selected" : "" }}>-</option>
                      @foreach ($tesauros as $tesauro)
                      @if(old('tesauro_obra') !== null)
                      <option value="{{ $tesauro->id }}" {{ old("tesauro_obra")==$tesauro->id ? "selected" : "" }}>{{
                        $tesauro->titulo_tesauro }}</option>
                      @else
                      <option value="{{ $tesauro->id }}">{{ $tesauro->titulo_tesauro }}</option>
                      @endif
                      @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('tesauro_obra') }}</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Localização da obra</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user text-info"></i>
                        </div>
                      </div>
                      <select name="localizacao_obra" class="form-control select2">
                        <option value="" {{ old("localizacao_obra") == null ? "selected" : "" }}>-</option>
                        @foreach ($localizacoes as $localizacao)
                          @if(old('localizacao_obra') !== null)
                            <option value="{{ $localizacao->id }}" {{ old("localizacao_obra")==$localizacao->id ? "selected": "" }}>{{ $localizacao->nome_localizacao }}</option>
                          @else
                            <option value="{{ $localizacao->id }}">{{ $localizacao->nome_localizacao }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <small class="text-danger">{{ $errors->first('localizacao_obra') }}</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Origem/Procedência</label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="procedencia_obra"
                        value="{{ old('procedencia_obra') }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('procedencia_obra') }}</small>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label>Tombamento</label>
                    <select name="tombamento_obra" class="form-control">
                      <option value="" {{ old("tombamento_obra") == null ? "selected" : ""}}>-</option>
                      @foreach ($tombamentos as $tombamento)
                        @if(old('tombamento_obra') !== null)
                          <option value="{{ $tombamento->id }}" {{ old("tombamento_obra")==$tombamento->id ? "selected" : ""}}>{{ $tombamento->titulo_tombamento }}</option>
                        @else
                          <option value="{{ $tombamento->id }}">{{ $tombamento->titulo_tombamento }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Século</label>
                      <select name="seculo_obra" class="form-control">
                        <option value="" {{ old("seculo_obra") == null ? "selected" : ""}}>-</option>
                        @foreach ($seculos as $seculo)
                          @if(old('seculo_obra') !== null)
                            <option value="{{ $seculo->id }}" {{ old("seculo_obra") == $seculo->id ? "selected" : "" }}>{{
                            $seculo->titulo_seculo }}</option>
                          @else
                            <option value="{{ $seculo->id }}">{{ $seculo->titulo_seculo }}</option>
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
                      <input type="number" class="form-control" name="ano_obra" value="{{ old('ano_obra') }}">
                    </div>
                    <small class="text-danger">{{ $errors->first('ano_obra') }}</small>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Estado de Conservação</label>
                      <select name="estado_de_conservacao_obra" class="form-control">
                        <option value="" {{ old("estado_de_conservacao_obra") == null ? "selected" : ""}}>-</option>
                        @foreach ($estados as $estado)
                          @if(old('estado_de_conservacao_obra') !== null)
                          <option value="{{ $estado->id }}" {{ old("estado_de_conservacao_obra")==$estado->id ? "selected" :"" }}>{{ $estado->titulo_estado_conservacao_obra }}</option>
                          @else
                            <option value="{{ $estado->id }}">{{ $estado->titulo_estado_conservacao_obra }}</option>
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
                      <input type="text" class="form-control" name="autoria_obra" value="{{ old('autoria_obra') }}">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Material</label>
                    <select name="material_obra" class="form-control select2">
                      <option value="" {{ old("material_obra") == null ? "selected" : "" }}>-</option>
                      @foreach ($materiais as $material)
                        @if(old('material_obra') !== null)
                          <option value="{{ $material->id }}" {{ old("material_obra")==$material->id ? "selected" : ""}}>{{ $material->titulo_material }}</option>
                        @else
                          <option value="{{ $material->id }}">{{ $material->titulo_material }}</option>
                        @endif
                      @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('material_obra') }}</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Técnica</label>
                    <select name="tecnica_obra" class="form-control select2">
                      <option value="" {{ old("tecnica_obra") == null ? "selected" : "" }}>-</option>
                      @foreach ($tecnicas as $tecnica)
                        @if(old('tecnica_obra') !== null)
                          <option value="{{ $tecnica->id }}" {{ old("tecnica_obra")==$tecnica->id ? "selected" : "" }}>{{$tecnica->titulo_tecnica }}</option>
                        @else
                          <option value="{{ $tecnica->id }}">{{ $tecnica->titulo_tecnica }}</option>
                        @endif
                      @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('tecnica_obra') }}</small>
                  </div>
                  <div class="form-group col-md-4">
                  </div>
                </div>
                {{--}}
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Especificações</label>
                    <div style="display: flex; flex-wrap: wrap;">
                      @foreach ($especificacoes as $especificacao)
                      <div class="pretty p-icon p-smooth" style="display: flex; flex-wrap: wrap; margin-right: 10px;">
                        <input name="especificacao_obra[]" type="checkbox" style="margin-top: 3px;"
                          value="{{ $especificacao->id }}" id="especificacao_obra_{{ $especificacao->id }}" {{
                          in_array($especificacao->id, old('especificacao_obra',[])) ? 'checked' : '' }}>
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
                {{--}}
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary" id="buscar"><i class="fas fa-search"></i>&nbsp;Buscar</button>
                <a href="{{ route('portal') }}" class=" btn btn-dark">voltar</a>
              </div>
            </form>
          </div>
          <div class="card">
                  <div class="card-body">
                    <div id="buscaObras" class="list-unstyled row clearfix">
                    </div>
                  </div>
                </div>
        </div>
        <!-- Home da área restrita -->
      </div>
    </div>
  </section>
</div>

<script>
  $(document).on('submit', '#busca_form', function(e) {
      e.preventDefault();
      let id = $(this).attr('data-id');
      $.ajax({
          url: '{{ route("busca_obras_publico_form") }}',
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': $('input[name=_token]').val()
          },
          data: $(this).serialize(),
      }).done(function(data) {
          console.log(data);
          $('#buscaObras').empty().append(data);
      });
  });

</script>

@endsection