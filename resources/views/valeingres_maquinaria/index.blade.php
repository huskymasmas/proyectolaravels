@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Vale de Ingreso de Maquinaria</h3>
        <a href="{{ route('valeingres_maquinaria.create') }}" class="btn btn-primary">
            + Nuevo Ingreso
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Encargado</th>
                <th>Cantidad</th>
                <th>Fecha Ingreso</th>
                <th>Proyecto</th>
                <th>Bodeguero</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $i)
            <tr>
                <td>{{ $i->id_vale_equipo_maquinaria_vehiculo }}</td>
                <td>{{ $i->Nombre }}</td>
                <td>{{ $i->Nombre_encargado }}</td>
                <td>{{ $i->cantidad }}</td>
                <td>{{ $i->Fecha_ingreso }}</td>
                <td>
                    @if ($i->proyecto)
                        {{ $i->proyecto->Nombre }}
                    @else
                    Bodega Central
                    @endif
                </td>

                
                <td>{{ $i->Nombre_Bodeguero }}</td>

                <td class="text-center">
                    <a href="{{ route('valeingres_maquinaria.edit', $i->id_vale_equipo_maquinaria_vehiculo) }}"
                        class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('valeingres_maquinaria.destroy', $i->id_vale_equipo_maquinaria_vehiculo) }}"
                        method="POST" class="d-inline"
                        onsubmit="return confirm('¿Eliminar este registro?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay registros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
