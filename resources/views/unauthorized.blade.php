@extends('layouts.app')

@section('titulo', 'Acesso não autorizado!')

<div class="main-content">
  @section('content')
  <section class="section">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h2> Acesso não autorizado! </h2>
          </div>
          <div class="card-body p-0">
            <div style="text-align: center; margin-top: 5%;">
              <img style="max-width:300px; max-height:300px;" src="/assets/img/erro.svg" alt="Exclamação em um símbolo de atenção da cor vermelha.">
              <div style="height: 100%; padding: 4%; font-family: 'Baloo Paaji'; font-size: large;">
                Desculpe, mas você não tem acesso à esta página. Caso você deva ter acesso, mas ainda vê esta mensagem, contacte um administrador relatando o seu problema.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
