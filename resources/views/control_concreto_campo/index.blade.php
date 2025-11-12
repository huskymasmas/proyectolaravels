@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Control de Concreto en Campo</h2>

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="mb-3 mt-3">
        <a href="{{ route('control_concreto_campo.create') }}" class="btn btn-primary">Nuevo Registro</a>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Aldea</th>
                <th>Fecha</th>
                <th>Código Camión</th>
                <th>Hora Llegada</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Volumen (m³)</th>
                <th>Asentamiento</th>
                <th>Temperatura</th>
                <th>Aire</th>
                <th>Código Muestra</th>
                <th>Cantidad Cilindros</th>
                <th>Enviados A</th>
                <th>Fecha Envío</th>
                <th>Resultado 7d (psi)</th>
                <th>Resultado 14d (psi)</th>
                <th>Resultado 28d (psi)</th>
                <th>Responsable</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registros as $r)
            <tr>
                <td>{{ $r->id_control_concreto_campo }}</td>
                <td>{{ $r->aldea->Nombre ?? 'Sin nombre' }}</td>
                <td>{{ $r->fecha }}</td>
                <td>{{ $r->codigo_envio_camion }}</td>
                <td>{{ $r->hora_llegada ?? '-' }}</td>
                <td>{{ $r->hora_inicio_vaciado ?? '-' }}</td>
                <td>{{ $r->hora_fin_vaciado ?? '-' }}</td>
                <td>{{ $r->volumen_m3 ?? '-' }}</td>
                <td>{{ $r->asentamiento ?? '-' }}</td>
                <td>{{ $r->temperatura ?? '-' }}</td>
                <td>{{ $r->aire ?? '-' }}</td>
                <td>{{ $r->codigo_muestra ?? '-' }}</td>
                <td>{{ $r->cantidad_cilindros ?? '-' }}</td>
                <td>{{ $r->enviados_a ?? '-' }}</td>
                <td>{{ $r->fecha_envio ?? '-' }}</td>
                <td>{{ $r->resultado_psi_7d ?? '-' }}</td>
                <td>{{ $r->resultado_psi_14d ?? '-' }}</td>
                <td>{{ $r->resultado_psi_28d ?? '-' }}</td>
                <td>{{ $r->responsable ?? '-' }}</td>
                <td>
                    <a href="{{ route('control_concreto_campo.edit', $r->id_control_concreto_campo) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('control_concreto_campo.destroy', $r->id_control_concreto_campo) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Eliminar registro?')" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="20" class="text-center">No hay registros.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
