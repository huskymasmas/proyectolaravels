@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center mb-4">Detalle del Vale #{{ $bodega->No_vale }}</h3>

    <div class="card shadow-lg p-4">
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($bodega->Fecha)->format('d/m/Y') }}</p>
        <p><strong>Material:</strong> {{ $bodega->Material }}</p>
        <p><strong>Unidad:</strong> {{ $bodega->unidad->Nombre ?? '—' }}</p>
        <p><strong>Cantidad:</strong> {{ $bodega->Cantidad }}</p>
        <p><strong>Equivalencia M³:</strong> {{ $bodega->Equivalencia_M3 }}</p>
        <p><strong>Conductor:</strong> {{ $bodega->Conductor }}</p>
        <p><strong>Placa Vehículo:</strong> {{ $bodega->Placa_vehiculo }}</p>
        <p><strong>Proyecto:</strong> {{ $bodega->proyecto->Nombre ?? '—' }}</p>
        <p><strong>Origen:</strong> {{ $bodega->Origen }}</p>

        <a href="{{ route('bodega_proyecto.index') }}" class="btn btn-secondary mt-3">← Volver</a>
    </div>
</div>
@endsection
