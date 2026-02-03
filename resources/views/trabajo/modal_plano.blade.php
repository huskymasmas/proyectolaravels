<div class="modal fade" id="modalPlano" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('planos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Subir Plano</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          
          <input type="hidden" name="id_trabajo" value="{{ $trabajo->id_trabajo }}">

          <div class="mb-3">
            <label>Nombre del plano</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Archivo</label>
            <input type="file" name="plano" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-success">Guardar Plano</button>
        </div>

      </form>
    </div>
  </div>
</div>
