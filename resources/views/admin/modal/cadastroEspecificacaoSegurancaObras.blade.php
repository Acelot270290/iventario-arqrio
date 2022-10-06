<div class="modal fade" id="addModalEspecificacaoSegurancaObras" tabindex="-1" role="dialog" aria-labelledby="formModal"
aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="formModal">Cadastrar Especifiações de Segurança de Obras</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form class=""  action="{{route('adicionar_especificacao_seg_obras')}}" method="post">
        {!! csrf_field() !!}
        <div class="form-group">
          <label>Especifiação de Segurança Obra</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-street-view"></i>
              </div>
            </div>
            <input type="text" class="form-control" placeholder="Insira a Especifiação de Segurança Obra" name="cadatro_nome_especificacao_seguranca_obra">
          </div>
        </div>
        <div class="form-group mb-0">
        </div>
        <button type="submit" class="btn btn-primary m-t-15 waves-effect">Salvar</button>
      </form>
    </div>
  </div>
</div>
</div>