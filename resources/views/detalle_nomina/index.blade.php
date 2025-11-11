@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Listado de Detalles de Nómina</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('detalle_nomina.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="empleado_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filtrar por empleado --</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id_Empleados }}" {{ $filtro == $empleado->id_Empleados ? 'selected' : '' }}>
                            {{ $empleado->Nombres }} {{ $empleado->Apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('detalle_nomina.create') }}" class="btn btn-success">Agregar Detalle</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Empleado</th>
                <th>Horas Extras</th>
                <th>Días Trabajados</th>
                <th>Total a Pagar (Q)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->empleado->Nombres ?? '' }} {{ $detalle->empleado->Apellido ?? '' }}</td>
                    <td>{{ $detalle->Horas_extras }}</td>
                    <td>{{ $detalle->cantidad_dias }}</td>
                    <td>{{ number_format($detalle->totla_A_pagar, 2) }}</td>
                    <td>
                        <a href="{{ route('detalle_nomina.edit', $detalle->id_detalle_nomina) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('detalle_nomina.destroy', $detalle->id_detalle_nomina) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay registros</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
