@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        {{ isset($utilidad) ? 'Editar Utilidad' : 'Registrar Nueva Utilidad' }}
    </h2>

    <form action="{{ isset($utilidad) ? route('utilidades.update', $utilidad->id_utilidades) : route('utilidades.store') }}" method="POST">
        @csrf
        @if(isset($utilidad)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Valor</label>
                <input type="number" name="Valor" class="form-control" step="0.01"
                       value="{{ old('Valor', $utilidad->Valor ?? '') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Unidad</label>
                <select name="id_Unidades" class="form-select" required>
                    <option value="">Seleccione una unidad</option>
                    @foreach($unidades as $unidad)
                        <option value="{{ $unidad->id_Unidades }}"
                            {{ old('id_Unidades', $utilidad->id_Unidades ?? '') == $unidad->id_Unidades ? 'selected' : '' }}>
                            {{ $unidad->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Proyecto</label>
                <select name="id_Proyecto" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}"
                            @if(isset($utilidad) && $utilidad->proyectos->contains('id_Proyecto', $proyecto->id_Proyecto)) selected @endif>
                            {{ $proyecto->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Detalle</label>
                <input type="text" name="Detalle" class="form-control" value="{{ old('Detalle', $utilidad->Detalle ?? '') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Cálculo (%)</label>
                <input type="number" name="Calculo" class="form-control" step="0.01"
                       value="{{ old('Calculo', $utilidad->Calculo ?? '') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Resultado</label>
                <input type="number" name="Resultado" class="form-control" 
                       value="{{ old('Resultado', $utilidad->Resultado ?? '') }}" readonly>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="Descripcion" class="form-control" rows="3">{{ old('Descripcion', $utilidad->Descripcion ?? '') }}</textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Guardar
            </button>
            <a href="{{ route('utilidades.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
