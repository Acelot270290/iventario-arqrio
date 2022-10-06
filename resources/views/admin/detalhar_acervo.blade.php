@extends('layouts.app')

@section('titulo', "Detalhamento de acervo ID: " . $acervo->id)

@section('content')

@php
    $allowEdit = ['1', '2', '4', '5'];
    $canOnlyView = ['6'];
    $allowDelete = ['1', '2'];
@endphp

@if(is_null(auth()->user('id')['acesso_acervos']))
  <script>window.location = "/unauthorized";</script>
@else
  @php
    $accesses = explode(',', auth()->user('id')['acesso_acervos']);
  @endphp
  @if(!in_array('0', $accesses) and !in_array(strval($acervo->id), $accesses))
    <script>window.location = "/unauthorized";</script>
  @else
<div class="main-content">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Detalhamento de acervo ID: {{ !is_null($acervo->id) ? $acervo->id : '-' }}</h3>
                        @if(in_array(intval(auth()->user('id')['id_cargo']), [1, 2, 4, 5]))
                        <div style="position: absolute; right: 0px; margin-right: 5%;">
                            <a href="@if(in_array(strval(auth()->user('id')['id_cargo']), $allowEdit)) {{ route('editar_acervo', ['id' => $acervo->id]) }} @else # @endif" class="btn btn-outline-primary" @if(in_array(strval(auth()->user('id')['id_cargo']), $canOnlyView)) disabled @endif><i class="fas fa-edit"></i>Editar acervo ID: {{ $acervo->id }}</a>
                        </div>
                        <div style="position: absolute; right: 170px; margin-right: 5%;">
                            <a href="{{ route('acervo_obras', ['id' => $acervo->id]) }}" class="btn btn-outline-info" target="_blank">
                                <i class="fas fa-camera"></i>Obras contidas em: {{ $acervo->id }}
                            </a>
                        </div>
                        @else
                        <div style="position: absolute; right: 0px; margin-right: 5%;">
                            <a href="{{ route('acervo_obras', ['id' => $acervo->id]) }}" class="btn btn-outline-info">
                                <i class="fas fa-camera"></i>Obras contidas em: {{ $acervo->id }}
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @if(!is_null($acervo->foto_frontal_acervo))
                                        <div class="carousel-item active">
                                            <img class="d-block w-100" src="{{ asset($acervo->foto_frontal_acervo) }}"
                                                alt="First slide">
                                        </div>
                                        @endif
                                        @if(!is_null($acervo->foto_lateral_1_acervo))
                                        <div class="carousel-item">
                                            <img class="d-block w-100"
                                                src="{{ asset($acervo->foto_lateral_1_acervo) }}" alt="Second slide">
                                        </div>
                                        @endif
                                        @if(!is_null($acervo->foto_lateral_2_acervo))
                                        <div class="carousel-item">
                                            <img class="d-block w-100"
                                                src="{{ asset($acervo->foto_lateral_2_acervo) }}" alt="Second slide">
                                        </div>
                                        @endif
                                        @if(!is_null($acervo->foto_posterior_acervo))
                                        <div class="carousel-item">
                                            <img class="d-block w-100"
                                                src="{{ asset($acervo->foto_posterior_acervo) }}" alt="Second slide">
                                        </div>
                                        @endif
                                        @if(!is_null($acervo->foto_cobertura_acervo))
                                        <div class="carousel-item">
                                            <img class="d-block w-100"
                                                src="{{ asset($acervo->foto_cobertura_acervo) }}" alt="Second slide">
                                        </div>
                                        @endif
                                        @if(!is_null($acervo->plantas_situacao_acervo))
                                        <div class="carousel-item">
                                            <img class="d-block w-100"
                                                src="{{ asset($acervo->plantas_situacao_acervo) }}" alt="Second slide">
                                        </div>
                                        @endif
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Anterior</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Próximo</span>
                                    </a>
                                </div>
                            </div>
                            <div class="form-group col-md-7">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <h5>{{ !is_null($acervo->nome_acervo) ? $acervo->nome_acervo : '-' }}</h5>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <b>Nome do acervo:</b> {{ !is_null($acervo->nome_acervo) ? $acervo->nome_acervo
                                        : '-' }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <b>Logradouro:</b> {{ !is_null($acervo->endereco_acervo) ?
                                        $acervo->endereco_acervo : '-' }} Nᵒ {{
                                        !is_null($acervo->numero_endereco_acervo) ? $acervo->numero_endereco_acervo :
                                        '-'}} - {{ !is_null($acervo->bairro_acervo) ? $acervo->bairro_acervo : '-' }} -
                                        {{ !is_null($acervo->cidade_acervo) ? $acervo->cidade_acervo : '-' }} - {{
                                        !is_null($acervo->UF_acervo) ? $acervo->UF_acervo : '-' }} CEP: {{
                                        !is_null($acervo->cep_acervo) ? $acervo->cep_acervo : '-' }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <b>Breve descrição da fachada e planta:</b> {{
                                        !is_null($acervo->descricao_fachada_planta_acervo) ?
                                        $acervo->descricao_fachada_planta_acervo : '-' }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <b>Estado de conservação:</b> {{
                                        !is_null($acervo->titulo_estado_conservacao_acervo) ?
                                        $acervo->titulo_estado_conservacao_acervo : '-' }}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b>Século:</b> {{ !is_null($acervo->titulo_seculo) ? $acervo->titulo_seculo :
                                        '-' }}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b>Ano de construção:</b> {{ !is_null($acervo->ano_construcao_acervo) ?
                                        $acervo->ano_construcao_acervo : '-' }}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b>Tombamento:</b> {{ !is_null($acervo->titulo_tombamento) ?
                                        $acervo->titulo_tombamento : '-' }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <b>Especificações de conservação:</b>
                                        @if(empty($especificacoes))
                                        -
                                        @else
                                        @foreach($especificacoes as $especificacao)
                                        {{ $especificacao->titulo_especificacao_acervo }}@if (!$loop->last),@endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <b>Cadastrado por:</b> {{ !is_null($acervo->usuario_cadastrante) ?
                                        $acervo->usuario_cadastrante : '-' }}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b>Cadastrado em:</b> {{ !is_null($acervo->created_at) ? date('d-m-Y',
                                        strtotime($acervo->created_at)) : '-' }}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b>Revisado por:</b> {{ !is_null($acervo->usuario_revisor) ?
                                        $acervo->usuario_revisor : '-' }}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b>Revisado em:</b> {{ !is_null($acervo->updated_at) ? date('d-m-Y',
                                        strtotime($acervo->updated_at)) : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('acervo') }}" class="btn btn-dark">voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif
@endsection
