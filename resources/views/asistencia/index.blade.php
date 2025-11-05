@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Listado de Asistencia</h2>

    <form method="GET" action="{{ route('asistencia.index') }}" class="row mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="id_Empleados" class="form-select" onchange="this.form.submit()">
                    <option value="">Filtrar por empleado</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id_Empleados }}" 
                            {{ $idEmpleado == $empleado->id_Empleados ? 'selected' : '' }}>
                            {{ $empleado->Nombres }} {{ $empleado->Apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>


    <a href="{{ route('asistencia.create') }}" class="btn btn-success mb-3">Registrar Asistencia</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Fecha</th>
                <th>Hora Ingreso</th>
                <th>Hora Salida</th>
                <th>Horas Totales</th>
                <th>Ganancia Día</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultado as $r)
                <tr>
                    <td>{{ $r['empleado'] }}</td>
                    <td>{{ $r['fecha'] }}</td>
                    <td>{{ $asistencias->firstWhere('Fecha', $r['fecha'])->Hora_ingreso ?? '-' }}</td>
                    <td>{{ $asistencias->firstWhere('Fecha', $r['fecha'])->Hora_finalizacion ?? '-' }}</td>
                    <td>{{ $r['horas_totales'] }}</td>
                    <td>{{ $r['Ganacia_dia'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
