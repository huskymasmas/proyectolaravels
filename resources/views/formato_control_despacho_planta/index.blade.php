@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Formato Control Despacho Planta</h2>

    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <select name="id_Aldea" class="form-control">
                <option value="">Todas las aldeas</option>
                @foreach($aldeas as $a)
                    <option value="{{ $a->id_aldea }}" {{ request('id_Aldea') == $a->id_aldea ? 'selected' : '' }}>
                        {{ $a->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Filtrar</button>
            <a href="{{ route('formato_control_despacho_planta.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <a href="{{ route('formato_control_despacho_planta.create') }}" class="btn btn-success mb-3">➕ Nuevo Registro</a>

    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>FECHA</th>
                <th>NO. ENVIO</th>
                <th>CONDUCTOR</th>
                <th>TIPO DE CONCRETO (PSI)</th>
                <th>CANT. CONCRETO (MT³)</th>
                <th>KG GRANEL</th>
                <th>KG SACOS</th>
                <th>TOTAL (KG)</th>
                <th>KG PIEDRIN</th>
                <th>KG ARENA</th>
                <th>LTS AGUA</th>
                <th colspan="6">ADITIVOS</th>
                <th>SUPERVISOR</th>
                <th>OBSERVACIONES</th>
                <th>ACCIONES</th>
            </tr>
            <tr class="table-secondary">
                <th colspan="11"></th>
                <th>CANT. LTS.</th>
                <th>NOMBRE</th>
                <th>CANT. LTS.</th>
                <th>NOMBRE</th>
                <th>CANT. LTS.</th>
                <th>NOMBRE</th>
                <th colspan="3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($registros as $r)
            <tr>
                <td>{{ $r->Fecha }}</td>
                <td>{{ $r->No_envio }}</td>
                <td>{{ $r->Conductor }}</td>
                <td>{{ $r->Tipo_de_Concreto_ps }}</td>
                <td>{{ number_format($r->Cantidad_Concreto_mT3, 3) }}</td>
                <td>{{ number_format($r->Concreto_granel_kg, 3) }}</td>
                <td>{{ number_format($r->Concreto_sacos_kg, 3) }}</td>
                <td>{{ number_format($r->total, 3) }}</td>
                <td>{{ number_format($r->kg_Piedrin, 3) }}</td>
                <td>{{ number_format($r->kg_Arena, 3) }}</td>
                <td>{{ number_format($r->Lts_Agua, 0) }}</td>

                {{-- ADITIVOS (3 posibles) --}}
                <td>{{ number_format($r->cantidad1 ?? 0, 2) }}</td>
                <td>{{ $r->Aditivo1 ?? '' }}</td>
                <td>{{ number_format($r->cantidad2 ?? 0, 2) }}</td>
                <td>{{ $r->Aditivo2 ?? '' }}</td>
                <td>{{ number_format($r->cantidad3 ?? 0, 2) }}</td>
                <td>{{ $r->Aditivo3 ?? '' }}</td>

                <td>{{ $r->Supervisor ?? '' }}</td>
                <td>{{ $r->Observaciones ?? '' }}</td>
                <td>
                    <a href="{{ route('formato_control_despacho_planta.edit', $r->id_Formato_control_despacho_planta) }}" class="btn btn-warning btn-sm">✏️</a>
                    <form action="{{ route('formato_control_despacho_planta.destroy', $r->id_Formato_control_despacho_planta) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar registro?')" class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-secondary fw-bold">
            <tr>
                <td colspan="4">Totales</td>
                <td>{{ number_format($total_mt3, 3) }}</td>
                <td>{{ number_format($total_granel, 3) }}</td>
                <td>{{ number_format($total_sacos, 3) }}</td>
                <td>{{ number_format($total_general, 3) }}</td>
                <td colspan="10"></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
