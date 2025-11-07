@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($trabajo) ? 'Editar Trabajo' : 'Crear Trabajo' }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($trabajo) ? route('trabajo.update', $trabajo->id_trabajos) : route('trabajo.store') }}" method="POST">
        @csrf
        @if(isset($trabajo))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Proyecto</label>
            <select name="id_Proyecto" class="form-select" required>
                <option value="">Seleccione Proyecto</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}" {{ old('id_Proyecto', $trabajo->id_Proyecto ?? '') == $proyecto->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Numero_face" class="form-label">Número Face</label>
            <input type="number" step="0.01" name="Numero_face" id="Numero_face" class="form-control" 
                   value="{{ old('Numero_face', $trabajo->Numero_face ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="Nombre_face" class="form-label">Nombre Face</label>
            <input type="text" name="Nombre_face" id="Nombre_face" class="form-control" 
                   value="{{ old('Nombre_face', $trabajo->Nombre_face ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado Trabajo</label>
            <select name="id_Estado_trabajo" class="form-select" required>
                <option value="">Seleccione Estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id_Estado_trabajo }}" {{ old('id_Estado_trabajo', $trabajo->id_Estado_trabajo ?? '') == $estado->id_Estado_trabajo ? 'selected' : '' }}>
                        {{ $estado->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Cantidad" class="form-label">Cantidad</label>
            <input type="number" step="0.01" name="Cantidad" id="Cantidad" class="form-control" 
                   value="{{ old('Cantidad', $trabajo->Cantidad ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unidad</label>
            <select name="id_Unidades" class="form-select" required>
                <option value="">Seleccione Unidad</option>
                @foreach($unidades as $unidad)
                    <option value="{{ $unidad->id_Unidades }}" {{ old('id_Unidades', $trabajo->id_Unidades ?? '') == $unidad->id_Unidades ? 'selected' : '' }}>
                        {{ $unidad->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="Estado" class="form-select" required>
                <option value="1" {{ old('Estado', $trabajo->Estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('Estado', $trabajo->Estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($trabajo) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('trabajo.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
