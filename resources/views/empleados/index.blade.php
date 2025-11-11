@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Listado de Empleados</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('empleados.create') }}" class="btn btn-primary">➕ Nuevo Empleado</a>
    </div>

    <table id="empleadosTable" class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Departamento</th>
                <th>Rol</th>
                <th>Sexo</th>
                <th>Contrato</th>
                <th>Estado</th>
                <th>Código</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->id_Empleados }}</td>
                    <td>{{ $empleado->Nombres }} {{ $empleado->Apellido }} {{ $empleado->Apellido2 }}</td>
                    <td>{{ $empleado->departamento->Nombres ?? '-' }}</td>
                    <td>{{ $empleado->rol->Nombre ?? '-' }}</td>
                    <td>{{ $empleado->Sexo }}</td>
                    <td>{{ $empleado->Tipo_contrato }}</td>
                    <td>{{ $empleado->Estado_trabajo }}</td>
                    <td>{{ $empleado->Codigo_empleado }}</td>
                    <td>
                        <a href="{{ route('empleados.edit', $empleado->id_Empleados) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('empleados.destroy', $empleado->id_Empleados) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este empleado?')">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#empleadosTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    });
</script>
@endsection
