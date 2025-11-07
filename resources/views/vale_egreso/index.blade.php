@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📌 Lista de Vales de Egreso</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('vale_egreso.create') }}" class="btn btn-primary mb-3">➕ Nuevo Vale de Egreso</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No Vale</th>
                <th>Proyecto</th>
                <th>Empresa</th>
                <th>Volumen (m³)</th>
                <th>Tipo Concreto</th>
                <th>Cemento (kg)</th>
                <th>Sacos Cemento</th>
                <th>Piedrín (kg)</th>
                <th>Arena (kg)</th>
                <th>Agua (lts)</th>
                <th>Aditivos</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vales as $vale)
                <tr>
                    <td>{{ $vale->No_vale }}</td>
                    <td>{{ $vale->despacho?->proyecto?->Nombre ?? 'N/A' }}</td>
                    <td>{{ $vale->despacho?->empresa?->Nombre ?? 'N/A' }}</td>
                    <td>{{ $vale->despacho?->Volumen_carga_M3 ?? '-' }}</td>
                    <td>{{ $vale->despacho?->Tipo_Concreto ?? '-' }}</td>
                    <td>{{ $vale->dosificacion?->kg_cemento_granel ?? '-' }}</td>
                    <td>{{ $vale->dosificacion?->Sacos_Cemento ?? '-' }}</td>
                    <td>{{ $vale->dosificacion?->kg_piedirn ?? '-' }}</td>
                    <td>{{ $vale->dosificacion?->Kg_arena ?? '-' }}</td>
                    <td>{{ $vale->dosificacion?->lts_agua ?? '-' }}</td>
                    <td>
                        @for($i=1; $i<=4; $i++)
                            @php
                                $nombre = 'Nombre'.$i;
                                $cantidad = 'Cantidad'.$i;
                            @endphp
                            @if(!empty($vale->aditivo?->$nombre))
                                {{ $vale->aditivo->$nombre }} ({{ $vale->aditivo->$cantidad ?? '-' }})<br>
                            @endif
                        @endfor
                    </td>
                    <td>{{ $vale->despacho?->Fecha ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No hay vales de egreso registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
