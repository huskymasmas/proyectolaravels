@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Listado de Utilidades</h2>

    {{-- Filtro por proyecto --}}
    <form method="GET" action="{{ route('utilidades.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label for="proyecto" class="form-label">Filtrar por Proyecto</label>
                <select name="proyecto" id="proyecto" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Todos los proyectos --</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}" 
                            {{ $proyectoSeleccionado == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- Botón crear --}}
    <div class="mb-3 text-end">
        <a href="{{ route('utilidades.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nueva Utilidad
        </a>
    </div>

    {{-- Tabla de utilidades --}}
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Valor</th>
                <th>Unidad</th>
                <th>Detalle</th>
                <th>Cálculo (%)</th>
                <th>Resultado</th>
                <th>Descripción</th>
                <th>Proyectos Asociados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($utilidades as $utilidad)
                <tr>
                    <td>{{ $utilidad->id_utilidades }}</td>
                    <td>{{ $utilidad->Valor }}</td>
                    <td>{{ $utilidad->unidad->Nombre ?? 'N/A' }}</td>
                    <td>{{ $utilidad->Detalle ?? '-' }}</td>
                    <td>{{ $utilidad->Calculo ?? '0' }}%</td>
                    <td>{{ $utilidad->Resultado }}</td>
                    <td>{{ $utilidad->Descripcion ?? '-' }}</td>
                    <td>
                        @foreach($utilidad->proyectos as $proyecto)
                            <span class="badge bg-primary">{{ $proyecto->Nombre }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('utilidades.edit', $utilidad->id_utilidades) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('utilidades.destroy', $utilidad->id_utilidades) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta utilidad?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay utilidades registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
