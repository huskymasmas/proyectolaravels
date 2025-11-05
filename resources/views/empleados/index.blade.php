@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Listado de Empleados</h2>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">Nuevo Empleado</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>DPI</th>
                <th>Nómina</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empleados as $e)
                <tr>
                    <td>{{ $e->id_Empleados }}</td>
                    <td>{{ $e->Nombres }} {{ $e->Apellido }}</td>
                    <td>{{ $e->DPI }}</td>
                    <td>{{ $e->nomina->total_pago ?? 'Sin nómina' }}</td>
                    <td>{{ $e->rol->Nombre ?? 'Sin rol' }}</td>
                    <td>{{ $e->Estado ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <a href="{{ route('empleados.edit', $e->id_Empleados) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('empleados.destroy', $e->id_Empleados) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar empleado?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('exportar') }}" class="btn btn-success mt-3">Exportar Empleados</a>
</div>
@endsection
