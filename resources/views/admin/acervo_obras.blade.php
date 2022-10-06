@extends('layouts.app')

@section('titulo', 'Obras cadastradas')

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
          <h4>Obras</h4>
          <div class="card-header-form">
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped" id="table-obras">
              <thead>
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
              </thead>
              <tbody>
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
                  <td>{{ $obra->titulo_obra }}@if($obra->obra_provisoria == 1)<div style="border-radius: 11px; border-color: rgb(255, 71, 111); background-color:rgb(255, 204, 215); border-width: 2px; border-style: dashed; margin-left: 2%; padding-left: 2%; padding-right: 2%; color: rgb(255, 71, 111); display: inline-block; font-family: 'Baloo Paaji';">T</div>@endif
                  </td>
                  <td>{{ $obra->titulo_tesauro }}</td>
                  <td>{{ $obra->acervo_id.'-'. $obra->nome_acervo }}</td>
                  <td>{{ $obra->titulo_material_1 }}</td>
                  <td>{{ $obra->titulo_seculo }}</td>
                  <td id="interacoes">
                    <a href="{{ route('detalhar_obra', ['id' => $obra->id]) }}" class="btn btn-outline-success"target="_blank"><i
                        class="far fa-eye"></i></a>
                    <a href="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('editar_obra', ['id' => $obra->id]) }} @else # @endif" class="btn btn-outline-primary" @if(in_array(strval(auth()->user('id')['id_cargo']), $canOnlyView)) disabled @endif target="_blank"><i class="fas fa-edit"></i></a>
                    @if(in_array(strval(auth()->user('id')['id_cargo']), $allowDelete))
                    <a href="#" class="btn btn-danger deletanovo" id="{{ $obra->id }}"
                      name="{{ $obra->titulo_obra }}"><i class="fas fa-trash"></i></a>
                    @endif
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
          url: '/obra/deletar/'+ id_obra,
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
