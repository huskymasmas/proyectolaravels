@extends('layouts.app')

@section('content')
<div class="container">

    <h3>{{ isset($item) ? 'Editar Tipo de Dosificación' : 'Crear Tipo de Dosificación' }}</h3>

    <form method="POST"
        action="{{ isset($item) ? route('tipo_dosificacion.update', $item->id_Tipo_dosificacion) : route('tipo_dosificacion.store') }}">
        
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="Nombre" class="form-control"
                value="{{ $item->Nombre ?? old('Nombre') }}" required>
        </div>

        <button class="btn btn-success">
            {{ isset($item) ? 'Actualizar' : 'Guardar' }}
        </button>

        <a href="{{ route('tipo_dosificacion.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>

</div>
@endsection
