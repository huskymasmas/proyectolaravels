@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($configuracion) ? 'Editar Configuración' : 'Nueva Configuración' }}</h2>

   
       <form 
        action="{{ isset($configuracion) 
            ? route('Configuracion.update', $configuracion->id_Configuracion) 
            : route('Configuracion.store') }}" 
        method="POST">
        @csrf
        @if(isset($configuracion))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="id_Proyecto">Proyecto</label>
            <select name="id_Proyecto" id="id_Proyecto" class="form-control">
                <option value="">-- Seleccionar --</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}"
                        {{ (isset($configuracion) && $configuracion->id_Proyecto == $proyecto->id_Proyecto) ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Parametros">Parámetros</label>
            <input type="text" name="Parametros" id="Parametros" class="form-control" 
                   value="{{ old('Parametros', $configuracion->Parametros ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="Valor">Valor</label>
            <input type="text" name="Valor" id="Valor" class="form-control" 
                   value="{{ old('Valor', $configuracion->Valor ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="NOTAS">Notas</label>
            <textarea name="NOTAS" id="NOTAS" class="form-control">{{ old('NOTAS', $configuracion->NOTAS ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">
            {{ isset($configuracion) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('Configuracion.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
