@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($estadoTrabajo) ? 'Editar Estado' : 'Crear Estado' }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($estadoTrabajo) ? route('estado_trabajo.update', $estadoTrabajo->id_Estado_trabajo) : route('estado_trabajo.store') }}" method="POST">
        @csrf
        @if(isset($estadoTrabajo))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="Nombre" class="form-label">Nombre</label>
            <input type="text" name="Nombre" id="Nombre" class="form-control" 
                   value="{{ old('Nombre', $estadoTrabajo->Nombre ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="Estado" class="form-select" required>
                <option value="1" {{ old('Estado', $estadoTrabajo->Estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('Estado', $estadoTrabajo->Estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ isset($estadoTrabajo) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('estado_trabajo.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
