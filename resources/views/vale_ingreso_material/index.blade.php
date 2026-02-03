@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="d-flex justify-content-between align-items-center">
        Vales de Ingreso de Materiales

        <a href="{{ route('vale_ingreso_material.create') }}" class="btn btn-success">
            + Nuevo Vale de Ingreso
        </a>
    </h2>

    {{-- Filtro por Proyecto --}}
    <form action="{{ route('vale_ingreso_material.index') }}" method="GET" class="mb-3 row g-3">
        <div class="col-md-4">
            <select name="id_Proyecto" class="form-control">
                <option value="">-- Todos los Vales --</option>
                <option value="bodega_general" {{ request('id_Proyecto') == 'bodega_general' ? 'selected' : '' }}>
                    Bodega General
                </option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}" {{ request('id_Proyecto') == $proyecto->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    {{-- Tabla de Vales --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Fecha Ingreso</th>
                <th>Proveedor</th>
                <th>Conductor</th>
                <th>Placa</th>
                <th>Estado Físico</th>
                <th>Costo</th>
                <th>Total Pagar</th>
                <th>Proyecto / Bodega</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vales as $vale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vale->Nombre }}</td>
                    <td>{{ $vale->cantidad }}</td>
                    <td>{{ $vale->unidad->Nombre ?? 'Sin unidad' }}</td>
                    <td>{{ $vale->Fecha_ingreso }}</td>
                    <td>{{ $vale->empresa_proveedora }}</td>
                    <td>{{ $vale->Nombre_conductor }}</td>
                    <td>{{ $vale->placa }}</td>
                    <td>{{ $vale->estado_fisico }}</td>
                    <td>{{ $vale->costo }}</td>
                    <td>{{ $vale->Total_pagar }}</td>
                    <td>
                        @if($vale->id_Proyecto)
                            {{ $vale->proyecto->Nombre ?? 'Proyecto eliminado' }}
                        @else
                            Bodega General
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('vale_ingreso_material.edit', $vale->id_vale_equipo_maquinaria_vehiculo) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('vale_ingreso_material.destroy', $vale->id_vale_equipo_maquinaria_vehiculo) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este registro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No hay vales registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
