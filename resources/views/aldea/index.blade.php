@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Listado de Aldeas</h2>

    <a href="{{ route('aldea.create') }}" class="btn btn-primary mb-3">Nueva Aldea</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Proyecto</th>
                <th>Estado</th>
                <th>Creado Por</th>
                <th>Fecha Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aldeas as $aldea)
            <tr>
                <td>{{ $aldea->id_aldea }}</td>
                <td>{{ $aldea->Nombre }}</td>
                <td>{{ $aldea->proyecto->Nombre ?? 'Sin proyecto' }}</td>
                <td>{{ $aldea->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ $aldea->Creado_por }}</td>
                <td>{{ $aldea->Fecha_creacion }}</td>
                <td>
                    <a href="{{ route('aldea.edit', $aldea->id_aldea) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('aldea.destroy', $aldea->id_aldea) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta aldea?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
