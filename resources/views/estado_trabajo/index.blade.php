@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Estados de Trabajo</h2>
    <a href="{{ route('estado_trabajo.create') }}" class="btn btn-primary mb-3">Nuevo Estado</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $estado)
            <tr>
                <td>{{ $estado->Nombre }}</td>
                <td>{{ $estado->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                <td>
                    <a href="{{ route('estado_trabajo.edit', $estado->id_Estado_trabajo) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('estado_trabajo.destroy', $estado->id_Estado_trabajo) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
