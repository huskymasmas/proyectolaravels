@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Trabajos</h2>
    <a href="{{ route('trabajo.create') }}" class="btn btn-primary mb-3">Nuevo Trabajo</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('trabajo.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="id_Proyecto" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar Proyecto --</option>
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}" 
                            {{ $idProyecto == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre ?? 'Proyecto '.$proyecto->id_Proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Proyecto</th>
            <th>Número Face</th>
            <th>Nombre Face</th>
            <th>Estado Trabajo</th> <!-- Nueva columna -->
            <th>Cantidad</th>
            <th>Unidad</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trabajos as $trabajo)
        <tr>
            <td>{{ $trabajo->proyecto->Nombre ?? '-' }}</td>
            <td>{{ $trabajo->Numero_face }}</td>
            <td>{{ $trabajo->Nombre_face }}</td>
            <td>{{ $trabajo->estadoTrabajo->Nombre ?? '-' }}</td> <!-- Mostrar EstadoTrabajo -->
            <td>{{ $trabajo->Cantidad }}</td>
            <td>{{ $trabajo->unidad->Nombre ?? '-' }}</td>
            <td>{{ $trabajo->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
            <td>
                <a href="{{ route('trabajo.edit', $trabajo->id_trabajos) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('trabajo.destroy', $trabajo->id_trabajos) }}" method="POST" style="display:inline;">
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
