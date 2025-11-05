@extends('layouts.app')

@section('content')
  <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
<div class="container">
    <h2>{{ isset($dosificacion) ? 'Editar Dosificación' : 'Nueva Dosificación' }}</h2>

    <form action="{{ isset($dosificacion) ? route('dosificacion.update', $dosificacion->id_Dosificacion) : route('dosificacion.store') }}" method="POST">
        @csrf
        @if(isset($dosificacion)) @method('PUT') @endif

        <div class="mb-3">
            <label>Tipo de Dosificación</label>
            <select name="id_Tipo_dosificacion" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id_Tipo_dosificacion }}" 
                        {{ isset($dosificacion) && $dosificacion->id_Tipo_dosificacion == $tipo->id_Tipo_dosificacion ? 'selected' : '' }}>
                        {{ $tipo->Nombre }}
                    </option>
                @endforeach
            </select>

            <label>Proyecto</label>
            <select name="id_Proyecto" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                @foreach($proyecto as $proyectos)
                    <option value="{{ $proyectos->id_Proyecto }}" 
                        {{ isset($dosificacion) && $dosificacion->id_Proyecto == $proyectos->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyectos->Nombre }}
                    </option>
                @endforeach
            </select>

        </div>

        

      

        <div class="mb-3"><label>Cemento (kg/m3)</label>
            <input  name="Cemento" class="form-control" value="{{ $dosificacion->Cemento ?? '' }}" required>
        </div>

        <div class="mb-3"><label>Arena (m3/m3)</label>
            <input  name="Arena" class="form-control" value="{{ $dosificacion->Arena ?? '' }}" required>
        </div>

        <div class="mb-3"><label>Pedrín (m3/m3)</label>
            <input name="Pedrin" class="form-control" value="{{ $dosificacion->Pedrin ?? '' }}" required>
        </div>

        <div class="mb-3"><label>Aditivo (L/m3)</label>
            <input  name="Aditivo" class="form-control" value="{{ $dosificacion->Aditivo ?? '' }}">
        </div>

        <div class="mb-3"><label>Nota</label>
            <textarea name="Nota" class="form-control">{{ $dosificacion->Nota ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('dosificacion.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</div>
</div>
@endsection
