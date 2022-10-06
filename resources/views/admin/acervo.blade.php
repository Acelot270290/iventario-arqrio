@extends('layouts.app')

@section('titulo', 'Acervos cadastrados')

@php
    $allowEdit = ['1', '2', '4', '5'];
    $canOnlyView = ['6'];
    $allowDelete = ['1', '2'];
@endphp

<div class="main-content">
  @section('content')

  {!! csrf_field() !!}

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Acervos</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped" id="table-obras">
              <thead>
                <tr>
                  <th style="padding-left:25px; text-align: center;">Id</th>
                  <th style="text-align: center;">Fachada Principal</th>
                  <th>Nome</th>
                  <th>Cidade</th>
                  <th>UF</th>
                  <th>Século</th>
                  <th>Ano de construção</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($acervos as $acervo)
                  <tr>
                  <td style="padding-left:25px; text-align: center;">{{ $acervo->id }}</td>
                  <td class="align-middle text-center">
                    <a href="{{ route('detalhar_acervo', ['id' => $acervo->id]) }}">
                      @if($acervo->foto_frontal_acervo)
                      <img class="team-member-sm" src="{{ url($acervo->foto_frontal_acervo) }}"
                        alt="acervo_{{ $acervo->id }}" data-toggle="tooltip" title="acervo_{{ $acervo->id }}"
                        data-original-title="Foto frontal">
                      @else
                      <img class="team-member-sm" src="{{ url('assets/img/noimg.png') }}" alt="acervo_{{ $acervo->id }}"
                        data-toggle="tooltip" title="acervo_{{ $acervo->id }}" data-original-title="Foto frontal">
                      @endif
                    </a>
                  </td>
                  <td>{{ $acervo->nome_acervo }}</td>
                  <td>{{ $acervo->cidade_acervo }}</td>
                  <td>{{ $acervo->UF_acervo }}</td>
                  <td>{{ $acervo->titulo_seculo }}</td>
                  <td>{{ $acervo->ano_construcao_acervo }}</td>
                  <td>
                    <a href="{{ route('detalhar_acervo', ['id' => $acervo->id]) }}" class="btn btn-outline-success" target="_blank">
                      <i class="far fa-eye"></i>
                    </a>
                    <a href="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('editar_acervo', ['id' => $acervo->id]) }} @else # @endif" class="btn btn-outline-primary" @if(in_array(strval(auth()->user('id')['id_cargo']), $canOnlyView)) disabled @endif target="_blank">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('acervo_obras', ['id' => $acervo->id]) }}" class="btn btn-outline-info" target="_blank">
                      <i class="fas fa-camera"></i>
                    </a>
                    <a href="#" class="btn btn-outline-danger deletanovo" id="{{ $acervo->id }}"
                      name="{{ $acervo->nome_acervo }}">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(".deletanovo").click(function (e) {
  e.preventDefault();
  let id_acervo = $(this).attr('id');
  let nome_acervo = $(this).attr('name');
  var botao = $(this);

  swal({
    title: 'Tem certeza?',
    text: 'Deseja deletar o acervo ' + nome_acervo + '?',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: '/acervo/deletar/' + id_acervo,
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
