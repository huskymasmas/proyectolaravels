@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bodega para Proyectos</h2>

    <button class="btn btn-primary mb-3" onclick="resetForm()">Nuevo Registro</button>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filtro por proyecto -->
    <form method="GET" class="mb-2">
        <select name="id_Proyecto" onchange="this.form.submit()" class="form-select w-auto">
            <option value="">Todos los proyectos</option>
            @foreach ($proyectos as $proy)
                <option value="{{ $proy->id_Proyecto }}" {{ request('id_Proyecto') == $proy->id_Proyecto ? 'selected' : '' }}>
                    {{ $proy->Nombre }}
                </option>
            @endforeach
        </select>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Material</th>
                <th>Unidad</th>
                <th>Cant. Max</th>
                <th>Usado</th>
                <th>Almacenado</th>

                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->id_Bodega_para_proyectos }}</td>
                <td>{{ $item->proyecto->Nombre ?? '' }}</td>
                <td>{{ $item->Material }}</td>
                <td>{{ $item->unidades->Nombre ?? '' }}</td>
                <td>{{ $item->Cantidad_maxima }}</td>
                <td>{{ $item->Usado }}</td>
                <td>{{ $item->Almazenado }}</td>
        
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editItem({{ $item->id_Bodega_para_proyectos }})">Editar</button>
                    <form action="{{ route('bodegaparaproyectos.destroy', $item->id_Bodega_para_proyectos) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este registro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bodegaForm" method="POST" action="{{ route('bodegaparaproyectos.store') }}">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">
        <input type="hidden" name="id_Bodega_para_proyectos" id="id_Bodega_para_proyectos">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bodega para Proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Proyecto</label>
                    <select name="id_Proyecto" id="id_Proyecto" class="form-select">
                        @foreach($proyectos as $proy)
                            <option value="{{ $proy->id_Proyecto }}">{{ $proy->Nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_Proyecto')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-2">
                    <label>Material</label>
                    <input type="text" name="Material" id="Material" class="form-control">
                    @error('Material')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-2">
                    <label>Unidad</label>
                    <select name="id_Unidades" id="id_Unidades" class="form-select">
                        @foreach($unidades as $u)
                            <option value="{{ $u->id_Unidades }}">{{ $u->Nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_Unidades')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-2">
                    <label>Cantidad Máxima</label>
                    <input type="number" step="0.001" name="Cantidad_maxima" id="Cantidad_maxima" class="form-control">
                    @error('Cantidad_maxima')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-2">
                    <label>Usado</label>
                    <input type="number" step="0.001" name="Usado" id="Usado" class="form-control">
                    @error('Usado')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-2">
                    <label>Almacenado</label>
                    <input type="number" step="0.001" name="Almazenado" id="Almazenado" class="form-control">
                    @error('Almazenado')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

           

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script>
function resetForm() {
    var form = document.getElementById('bodegaForm');

    // Restaurar action a store
    form.action = "{{ route('bodegaparaproyectos.store') }}";
    document.getElementById('formMethod').value = 'POST';

    // Limpiar inputs
    document.getElementById('id_Bodega_para_proyectos').value = '';
    document.getElementById('Material').value = '';
    document.getElementById('Cantidad_maxima').value = '';
    document.getElementById('Usado').value = '';
    document.getElementById('Almazenado').value = '';

    // Limpiar selects y poner primer valor
    var selectProyecto = document.getElementById('id_Proyecto');
    if(selectProyecto.options.length > 0) selectProyecto.selectedIndex = 0;

    var selectUnidad = document.getElementById('id_Unidades');
    if(selectUnidad.options.length > 0) selectUnidad.selectedIndex = 0;

    // Abrir modal
    var modal = new bootstrap.Modal(document.getElementById('modalForm'));
    modal.show();
}

function editItem(id) {
    fetch('{{ url("bodegaparaproyectos") }}/' + id + '/edit')
    .then(response => response.json())
    .then(data => {
        var form = document.getElementById('bodegaForm');
        form.action = '{{ url("bodegaparaproyectos") }}/' + data.id_Bodega_para_proyectos;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('id_Bodega_para_proyectos').value = data.id_Bodega_para_proyectos;
        document.getElementById('Material').value = data.Material;
        document.getElementById('Cantidad_maxima').value = data.Cantidad_maxima;
        document.getElementById('Usado').value = data.Usado;
        document.getElementById('Almazenado').value = data.Almazenado;
        document.getElementById('id_Proyecto').value = data.id_Proyecto;
        document.getElementById('id_Unidades').value = data.id_Unidades;

        var modal = new bootstrap.Modal(document.getElementById('modalForm'));
        modal.show();
    })
    .catch(err => console.error(err));
}

// Si hay errores de validación, abrir modal automáticamente
@if($errors->any())
var myModal = new bootstrap.Modal(document.getElementById('modalForm'));
myModal.show();
@endif
</script>

@endsection
