@extends('layouts.app')

@section('titulo', 'Admin')

<div class="main-content">
  @section('content')
  <section class="section">
    <h1>Links rápidos</h1>
    <div class="row ">
      <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1">
      </div>
      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <a href="/home">
          <div class="card">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                    <div class="card-content">
                      <h5 class="font-15">Pesquisar</h5>
                    </div>
                  </div>
                  <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pl-0">
                    <div class="banner-img">
                      <img style="max-width:140px; max-height:140px;" src="assets/img/banner/pesquisar.svg" alt="Uma obra de arte sobreposta por uma lupa, indicando 'buscar'.">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <a href="{{ route('criar_acervo') }}">
          <div class="card">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                    <div class="card-content">
                      <h5 class="font-15">Inserir Acervo</h5>
                    </div>
                  </div>
                  <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pl-0">
                    <div class="banner-img">
                      <img style="max-width:140px; max-height:140px;" src="assets/img/banner/inserir_acervo.svg" alt="Imagem de uma construção com uma seta para baixo na lateral superior esquerda, indicando 'inserção de um novo acervo'.">
                    </div>
                  </div>
                  <div style="height: 1.15em; width: 100%;"></div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <a href="{{ route('acervo') }}">
          <div class="card">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                    <div class="card-content">
                      <h5 class="font-15">Listar Acervos</h5>
                    </div>
                  </div>
                  <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pl-0">
                    <div class="banner-img">
                      <img style="max-width:140px; max-height:140px;" src="assets/img/banner/listar_acervos.svg" alt="Imagem de uma construção comuma lista sobreposta na lateral superior esquerda, indicando 'listar os acervos existentes'.">
                    </div>
                  </div>
                  <div style="height: 1.15em; width: 100%;"></div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <a href="{{ route('criar_obra') }}">
          <div class="card">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                    <div class="card-content">
                      <h5 class="font-15">Inserir Obra</h5>
                    </div>
                  </div>
                  <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pl-0">
                    <div class="banner-img">
                      <img style="max-width:140px; max-height:140px;" src="assets/img/banner/inserir_obras.svg" alt="Obras de arte sobrepostas por uma seta para baixo na laterial superior direita, indicando 'inserção de uma nova obra'.">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-12">
        <div class="card">
          <a href="{{ route('obra') }}">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                    <div class="card-content">
                      <h5 class="font-15">Listar Obras</h5>
                    </div>
                  </div>
                  <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pl-0">
                    <div class="banner-img">
                      <img style="max-width:140px; max-height:140px;" src="assets/img/banner/listar_obras.svg" alt="Obras de arte sobrepostas por uma lista na lateral superior direita, indicando 'listar as obras existentes'.">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1">
      </div>
    </div>
    <h1>Informações úteis</h1>
    <div class="row ">
      <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
          <div class="card-statistic-4">
            <div class="align-items-center justify-content-between">
              <div class="row ">
                <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                  <div class="card-content">
                    <h5 class="font-15">Usuários</h5>
                    <h2 class="mb-3 font-18">{{ $usuarios_total }}</h2>
                    @foreach ($estatisticasAcervo as $key => $estatisticaAcervo)
                    <p class="mb-0">&nbsp;</p>
                    @endforeach
                  </div>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                  <div class="banner-img">
                    <img style="max-width:140px; max-height:140px;" src="assets/img/banner/usuarios.svg" alt="Pessoas, indicando 'usuários'. Da esquerda para a direita, um rapaz de gravata, uma moça vestida com uma blusa amarela e um rapaz de jaqueta e óculos.">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
          <div class="card-statistic-4">
            <div class="align-items-center justify-content-between">
              <div class="row ">
                <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                  <div class="card-content">
                    <h5 class="font-15">Acervos</h5>
                    <h2 class="mb-3 font-18">{{ $acervos_total }}</h2>
                    @foreach ($estatisticasAcervo as $key => $estatisticaAcervo)
                    <p class="mb-0">
                      @if($estatisticaAcervo > 0)
                      <span class="col-green">{{ $estatisticaAcervo }}</span> {{ $estatisticaAcervo > 1 ? 'acervos foram
                      cadastrados' : 'acervo foi cadastrado' }} {{ $key > 1 ? 'nos últimos ' . $key . ' dias' : 'no
                      último dia' }}.
                      @else
                      @if($estatisticaAcervo < 0) <span class="col-orange">{{ $estatisticaAcervo }}</span> {{
                        $estatisticaAcervo < 1 ? 'acervos foram descadastrados' : 'acervo foi descadastrado' }} {{ $key>
                          1 ? 'nos últimos ' . $key . ' dias' : 'no último dia' }}.
                          @else
                          Nenhum acervo cadastrado {{ $key > 1 ? 'nos últimos ' . $key . ' dias' : 'no último dia' }}.
                          @endif
                          @endif
                    </p>
                    @endforeach
                  </div>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                  <div class="banner-img">
                    <img style="max-width:140px; max-height:140px;" src="assets/img/banner/acervos.svg" alt="Uma construção.">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
          <div class="card-statistic-4">
            <div class="align-items-center justify-content-between">
              <div class="row ">
                <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                  <div class="card-content">
                    <h5 class="font-15">Obras</h5>
                    <h2 class="mb-3 font-18">{{ $obras_total }}</h2>
                    @foreach ($estatisticasObra as $key => $estatisticaObra)
                    <p class="mb-0">
                      @if($estatisticaObra > 0)
                      <span class="col-green">{{ $estatisticaObra }}</span> {{ $estatisticaObra > 1 ? 'obras foram
                      cadastradas' : 'obra foi cadastrada' }} {{ $key > 1 ? 'nos últimos ' . $key . ' dias' : 'no último
                      dia' }}.
                      @else
                      @if($estatisticaObra < 0) <span class="col-orange">{{ $estatisticaObra }}</span> {{
                        $estatisticaObra < 1 ? 'obras foram descadastradas' : 'obra foi descadastrada' }} {{ $key> 1 ?
                          'nos últimos ' . $key . ' dias' : 'no último dia' }}.
                          @else
                          Nenhuma obra cadastrada {{ $key > 1 ? 'nos últimos ' . $key . ' dias' : 'no último dia' }}.
                          @endif
                          @endif
                    </p>
                    @endforeach
                  </div>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6 pl-0">
                  <div class="banner-img">
                    <img style="max-width:140px; max-height:140px;" src="assets/img/banner/obras.svg" alt="Obras de arte.">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Últimas 10 obras cadastradas</h4>
            <div class="card-header-form"></div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              {!! csrf_field() !!}
              <table class="table table-striped">
                <tr>
                  <th style="padding-left:25px; text-align: center;">Id</th>
                  <th style="text-align: center;">Foto Principal</th>
                  <th>Título</th>
                  <th>Tesauro</th>
                  <th>Acervo</th>
                  <th>Material</th>
                  <th>Século</th>
                  <th>Ações</th>
                </tr>
                @foreach ($obras as $obra)
                <tr>
                  <td style="padding-left:25px; text-align: center;">{{ $obra->id }}</td>
                  <td class="align-middle text-center">
                    <a href="{{ route('detalhar_obra', ['id' => $obra->id]) }}">
                      @if($obra->foto_frontal_obra)
                      <img class="team-member-sm" src="{{ asset($obra->foto_frontal_obra) }}" alt="obra_{{ $obra->id }}"
                        data-toggle="tooltip" title="obra_{{ $obra->id }}" data-original-title="Foto frontal">
                      @else
                      <img class="team-member-sm" src="{{ asset('img/noimg.png') }}" alt="obra_{{ $obra->id }}"
                        data-toggle="tooltip" title="obra_{{ $obra->id }}" data-original-title="Foto frontal">
                      @endif
                    </a>
                  </td>
                  <td>{{ $obra->titulo_obra }}</td>
                  <td>{{ $obra->titulo_tesauro }}</td>
                  <td>{{ $obra->nome_acervo }}</td>
                  <td>{{ $obra->titulo_material_1 }}</td>
                  <td>{{ $obra->titulo_seculo }}</td>
                  <td id="interacoes">
                    <a href="{{ route('detalhar_obra', ['id' => $obra->id]) }}" class="btn btn-outline-success"target="_blank">
                      <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('editar_obra', ['id' => $obra->id]) }}" class="btn btn-outline-primary" target="_blank">
                      <i class="fas fa-edit"></i>
                    </a>
                    @if(in_array(strval(auth()->user('id')['id_cargo']), ['1', '2']))
                    <a href="#" class="btn btn-danger deletanovo" id="{{ $obra->id }}" name="{{ $obra->titulo_obra }}">
                      <i class="fas fa-trash"></i>
                    </a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script>
  $(".deletanovo").click(function (e) {
  e.preventDefault();
  let id_obra = $(this).attr('id');
  let titulo_obra = $(this).attr('name');
  var botao = $(this);

  swal({
    title: 'Tem certeza?',
    text: 'Deseja deletar a obra ' + titulo_obra + '?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: '/obra/deletar/' + id_obra,
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('input[name=_token]').val()
          }}).done(function(data) {
          if(data.status == 'success') {
            swal('Sucesso!', data.msg, 'success');
            botao.parent().parent().remove();
          }else{
            swal('Erro!', data.msg, 'error');
          }  
        });
        
      }
    });
});

</script>

@endsection