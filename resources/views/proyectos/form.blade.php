
@include('header')
<div class="container">
    <h2>{{ isset($proyecto) ? 'Editar Proyecto' : 'Crear Proyecto' }}</h2>

    <form action="{{ isset($proyecto) ? route('proyectos.update', $proyecto->id_Proyecto) : route('proyectos.store') }}" method="POST">
        @csrf
        @if(isset($proyecto))
            @method('PUT')
        @endif

        <div class="form-group mb-3">
            <label for="Nombre">Nombre del Proyecto</label>
            <input type="text" name="Nombre" id="Nombre" class="form-control" 
                value="{{ old('Nombre', $proyecto->Nombre ?? '') }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="Descripcion">Descripción</label>
            <input type="text" name="Descripcion" id="Descripcion" class="form-control" 
                value="{{ old('Descripcion', $proyecto->detalle->Descripcion ?? '') }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="Ubicacion">Ubicación</label>
            <input type="text" name="Ubicacion" id="Ubicacion" class="form-control" 
                value="{{ old('Ubicacion', $proyecto->detalle->Ubicacion ?? '') }}">
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($proyecto) ? 'Actualizar' : 'Guardar' }}</button>
    </form>
</div>