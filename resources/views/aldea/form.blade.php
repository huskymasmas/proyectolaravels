@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ $aldea->exists ? 'Editar Aldea' : 'Nueva Aldea' }}</h2>

    <form action="{{ $aldea->exists ? route('aldea.update', $aldea->id_aldea) : route('aldea.store') }}" method="POST">
        @csrf
        @if($aldea->exists)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="Nombre" class="form-control" value="{{ old('Nombre', $aldea->Nombre) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Proyecto</label>
            <select name="id_Proyecto" class="form-select" required>
                <option value="">Seleccione un proyecto</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}" {{ old('id_Proyecto', $aldea->id_Proyecto) == $proyecto->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="Estado" class="form-select" required>
                <option value="1" {{ old('Estado', $aldea->Estado) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('Estado', $aldea->Estado) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('aldea.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
