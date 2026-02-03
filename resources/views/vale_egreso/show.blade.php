@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-3">Vale de Egreso #{{ $vale->No_vale }}</h2>

    <a href="{{ route('vale_egreso.index') }}" class="btn btn-secondary mb-3">← Regresar</a>

    <!-- DESPACHO -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Despacho de Concreto</div>
        <div class="card-body">
            <p><strong>Proyecto:</strong> {{ $vale->despacho->proyecto->Nombre ?? 'N/A' }}</p>
            <p><strong>Empresa:</strong> {{ $vale->despacho->empresa->Nombre ?? 'N/A' }}</p>
            <p><strong>Volumen:</strong> {{ $vale->despacho->Volumen_carga_M3 ?? '-' }} m³</p>
            <p><strong>Tipo Concreto:</strong> {{ $vale->despacho->Tipo_Concreto ?? '-' }}</p>
            <p><strong>Fecha:</strong> {{ $vale->despacho->Fecha ?? '-' }}</p>
            <p><strong>Placa:</strong> {{ $vale->despacho->Placa_numero ?? '-' }}</p>
        </div>
    </div>

    <!-- DOSIFICACIÓN -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Dosificación</div>
        <div class="card-body">
            <p><strong>Cemento:</strong> {{ $vale->dosificacion->kg_cemento_granel }} kg / {{ $vale->dosificacion->Sacos_Cemento }} sacos</p>
            <p><strong>Piedrín:</strong> {{ $vale->dosificacion->kg_piedirn }} kg</p>
            <p><strong>Arena:</strong> {{ $vale->dosificacion->Kg_arena }} kg</p>
            <p><strong>Agua:</strong> {{ $vale->dosificacion->lts_agua }} litros</p>
        </div>
    </div>

    <!-- ADITIVOS -->
    <div class="card">
        <div class="card-header bg-warning text-dark">Aditivos Usados</div>
        <div class="card-body">
            @for($i=1; $i<=4; $i++)
                @php
                    $n = 'Nombre'.$i;
                    $c = 'Cantidad'.$i;
                @endphp

                @if(!empty($vale->aditivo->$n))
                    <p><strong>{{ $vale->aditivo->$n }}:</strong> {{ $vale->aditivo->$c }}</p>
                @endif
            @endfor
        </div>
    </div>

</div>
@endsection
