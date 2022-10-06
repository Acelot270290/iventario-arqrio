@extends('layouts.app_slim')

@section('content')
<div class="container" style='margin-top:10%; margin-bottom: 7%;'>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Documentos') }}</div>

                <div class="card-body">


                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ url('assets/documentos/Contrato.pdf') }}" class="btn btn-primary">
                                    {{ __('Contrato') }}
                                </a>
                                <a href="{{ url('assets/documentos/diretoria.pdf') }}" class="btn btn-primary">
                                    {{ __('Diretoria') }}
                                </a>
                                <a href="{{ url('assets/documentos/Estatuto.pdf') }}" class="btn btn-primary">
                                    {{ __('Estatuto') }}
                                </a>


                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
