@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Proyectos</h2>


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
   <br>
   <br>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Ubicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectos as $proyecto)
                <tr>
                    <td>{{ $proyecto->id_Proyecto }}</td>
                    <td>{{ $proyecto->Nombre }}</td>
                    <td>{{ $proyecto->detalle->Descripcion ?? '' }}</td>
                    <td>{{ $proyecto->detalle->Ubicacion ?? '' }}</td>
                    <td>
                        <a href="{{ route('proyectos.edit', $proyecto->id_Proyecto) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('proyectos.destroy', $proyecto->id_Proyecto) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('¿Seguro que quieres eliminar este proyecto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection