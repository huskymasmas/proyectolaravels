@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Detalle del Vale #{{ $vale->No_vale }}</h2>

    <a href="{{ route('vale_egreso.index') }}" class="btn btn-secondary mb-3">↩ Volver</a>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">📦 Despacho de Concreto</div>
        <div class="card-body">
            <p><strong>Proyecto:</strong> {{ $vale->despacho->id_Proyecto ?? 'N/A' }}</p>
            <p><strong>Volumen:</strong> {{ $vale->despacho->Volumen_carga_M3 ?? 'N/A' }} m³</p>
            <p><strong>Tipo Concreto:</strong> {{ $vale->despacho->Tipo_Concreto ?? 'N/A' }}</p>
            <p><strong>Fecha:</strong> {{ $vale->despacho->Fecha ?? 'N/A' }}</p>
            <p><strong>Placa:</strong> {{ $vale->despacho->Placa_numero ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">🧱 Dosificación</div>
        <div class="card-body">
            <p><strong>Cemento:</strong> {{ $vale->dosificacion->kg_cemento_granel ?? 0 }} kg granel / {{ $vale->dosificacion->Sacos_Cemento ?? 0 }} sacos</p>
            <p><strong>Piedrín:</strong> {{ $vale->dosificacion->kg_piedirn ?? 0 }} kg</p>
            <p><strong>Arena:</strong> {{ $vale->dosificacion->Kg_arena ?? 0 }} kg</p>
            <p><strong>Agua:</strong> {{ $vale->dosificacion->lts_agua ?? 0 }} litros</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">🧪 Aditivos Aplicados</div>
        <div class="card-body">
            @for($i = 1; $i <= 4; $i++)
                @php
                    $nombre = 'Nombre' . $i;
                    $cantidad = 'Cantidad' . $i;
                @endphp
                @if(!empty($vale->aditivos->$nombre))
                    <p><strong>{{ $vale->aditivos->$nombre }}:</strong> {{ $vale->aditivos->$cantidad }} unidades</p>
                @endif
            @endfor
        </div>
    </div>
</div>
@endsection
