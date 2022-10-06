<div class="modal fade" id="addModalTecnica" tabindex="-1" role="dialog" aria-labelledby="formModal"
aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="formModal">Cadastrar Técnica</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form class=""  action="{{route('adicionar_tecnica')}}" method="post">
        {!! csrf_field() !!}
        <div class="form-group">
          <label>Técnica</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-street-view"></i>
              </div>
            </div>
            <input type="text" class="form-control" placeholder="Insira a Técnica" name="cadatro_nome_tecnica">
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