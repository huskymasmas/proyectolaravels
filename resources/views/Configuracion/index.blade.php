@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Listado de Configuraciones</h2>

    {{-- Filtro por proyecto --}}
    <form method="GET" action="{{ route('Configuracion.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="id_Proyecto" class="form-label">Filtrar por Proyecto:</label>
                <select name="id_Proyecto" id="id_Proyecto" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Todos los Proyectos --</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}"
                            {{ $idProyecto == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- Botón para crear nueva configuración --}}
    <a href="{{ route('Configuracion.create') }}" class="btn btn-primary mb-3">Nueva Configuración</a>

    {{-- Tabla de configuraciones --}}
    <table class="table table-bordered">
        <thead class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Parámetros</th>
                <th>Valor</th>
                <th>Notas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($configuraciones as $configuracion)
                <tr>
                    <td>{{ $configuracion->id_Configuracion }}</td>
                    <td>{{ $configuracion->proyecto->Nombre ?? 'Sin Proyecto' }}</td>
                    <td>{{ $configuracion->Parametros }}</td>
                    <td>{{ $configuracion->Valor }}</td>
                    <td>{{ $configuracion->NOTAS }}</td>
                    <td>
                        <a href="{{ route('Configuracion.edit', $configuracion->id_Configuracion) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('Configuracion.destroy', $configuracion->id_Configuracion) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta configuración?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">No hay configuraciones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
