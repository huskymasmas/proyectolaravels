@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Formato de Control de Despacho en Planta</h3>

    {{-- FILTRO POR PROYECTO --}}
    <form method="GET" action="{{ route('formato_despacho.index') }}" class="row mb-3">
        <div class="col-md-4">
            <label for="id_Proyecto" class="form-label">Filtrar por Proyecto:</label>
            <select name="id_Proyecto" id="id_Proyecto" class="form-select">
                <option value="">-- Todos --</option>
                @foreach ($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}" 
                        {{ request('id_Proyecto') == $proyecto->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    {{-- TABLA DE REGISTROS --}}
 {{-- TABLA DE REGISTROS COMPLETA --}}
<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>ID</th>
            <th>No Envío</th>
            <th>Proyecto</th>
            <th>Tipo Concreto (PS)</th>
            <th>Cant. Concreto (m³)</th>
            <th>Concreto Granel (kg)</th>
            <th>Concreto Sacos (kg)</th>
            <th>Total (kg)</th>
            <th>Kg por m³</th>
            <th>Supervisor</th>
            <th>Observaciones</th>
            <th>Fecha creación</th>
            <th>Fecha actualización</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($formatos as $formato)
            <tr>
                <td>{{ $formato->id_Formato_control_despacho_planta }}</td>
                <td>{{ $formato->No_envio }}</td>
                <td>{{ $formato->proyecto->Nombre ?? '—' }}</td>
                <td>{{ number_format($formato->Tipo_de_Concreto_ps, 2) }}</td>
                <td>{{ number_format($formato->Cantidad_Concreto_mT3, 3) }}</td>
                <td>{{ number_format($formato->Concreto_granel_kg, 3) }}</td>
                <td>{{ number_format($formato->Concreto_sacos_kg, 3) }}</td>
                <td>{{ number_format($formato->total, 3) }}</td>
                <td>{{ number_format($formato->kg_Por_m3, 3) }}</td>
                <td>{{ $formato->empleado->Nombres ?? '—' }}</td>
                <td>{{ $formato->Observaciones ?? 'Sin observaciones' }}</td>
                <td>{{ $formato->Fecha_creacion }}</td>
                <td>{{ $formato->Fecha_actualizacion }}</td>
                <td class="text-center">
                    <a href="{{ route('formato_despacho.edit', $formato->id_Formato_control_despacho_planta) }}" 
                       class="btn btn-warning btn-sm">Editar</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="16" class="text-center">No hay registros</td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>
@endsection
