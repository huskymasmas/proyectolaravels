@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Registro Diario</h2>
    <a href="{{ route('registro_diario.create') }}" class="btn btn-primary mb-3">➕ Nuevo Registro</a>

    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-warning">
            <tr>
                <th>Fecha</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Viáticos (Q)</th>
                <th>Días Viáticos (adel.)</th>
                <th>Adelanto Viáticos (Q)</th>
                <th>Trabajó sí/no</th>
                <th>Horas Extras día</th>
                <th>Adelantos (Q)</th>
                <th>Pago Parcial (Q)</th>
                <th>Total Día (Q)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $r)
            <tr>
                <td>{{ $r->fecha_dia }}</td>
                <td>{{ $r->empleado->Codigo_empleado }}</td>
                <td>{{ $r->empleado->Nombres }} {{ $r->empleado->Apellido }}</td>
                <td>{{ $r->empleado->id_Rol }}</td>
                <td>{{ number_format($r->nomina->Bonos ?? 0, 2) }}</td>
                <td>{{ $r->dias_viaticos }}</td>
                <td>{{ number_format($r->Adelanto_viatico, 2) }}</td>
                <td>{{ $r->Trabajo }}</td>
                <td>{{ $r->Horas_extras }}</td>
                <td>{{ number_format($r->Adelantos, 2) }}</td>
                <td>{{ number_format($r->Pago_Parcial, 2) }}</td>
                <td>{{ number_format($r->Total_dia, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
