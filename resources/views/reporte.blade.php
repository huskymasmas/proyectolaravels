@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Reporte de Producción ({{ $desde }} — {{ $hasta }})</h2>

  <div class="mb-3">
    <a href="{{ route('produccion.create') }}" class="btn btn-secondary">Nuevo registro</a>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Fecha</th><th>Proyecto</th><th>Planta</th><th>Volumen (m³)</th>
        <th>Cemento (kg)</th><th>Arena (m³)</th><th>Piedrín (m³)</th><th>Aditivo (L)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($registros as $r)
        <tr>
          <td>{{ $r->Fecha }}</td>
          <td>{{ optional($r->proyecto)->Nombre }}</td>
          <td>{{ optional($r->planta)->Nombre }}</td>
          <td>{{ $r->Volumen_m3 }}</td>
          <td>{{ $r->Cemento_kg }}</td>
          <td>{{ $r->Arena_m3 }}</td>
          <td>{{ $r->Piedrin_m3 }}</td>
          <td>{{ $r->Aditivo_l }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h4>Totales</h4>
  <ul>
    <li>Total m³: {{ $totales['total_m3'] }}</li>
    <li>Total horas (según vales): {{ $totales['total_hours'] }}</li>
    <li>Rendimiento m³/h: {{ $totales['m3_per_hour'] ?? 'No calculable (falta inicio/fin)' }}</li>
  </ul>

  <h4>Consumo real vs esperado</h4>
  <table class="table">
    <thead><tr><th>Material</th><th>Real</th><th>Esperado</th><th>Diferencia %</th></tr></thead>
    <tbody>
      <tr><td>Cemento (kg)</td><td>{{ $totales['consumo_real']['Cemento_kg'] }}</td><td>{{ $totales['consumo_esperado']['Cemento_kg'] }}</td><td>{{ $diff['Cemento_kg'] ?? 'N/A' }}</td></tr>
      <tr><td>Arena (m³)</td><td>{{ $totales['consumo_real']['Arena_m3'] }}</td><td>{{ $totales['consumo_esperado']['Arena_m3'] }}</td><td>{{ $diff['Arena_m3'] ?? 'N/A' }}</td></tr>
      <tr><td>Piedrín (m³)</td><td>{{ $totales['consumo_real']['Piedrin_m3'] }}</td><td>{{ $totales['consumo_esperado']['Piedrin_m3'] }}</td><td>{{ $diff['Piedrin_m3'] ?? 'N/A' }}</td></tr>
      <tr><td>Aditivo (L)</td><td>{{ $totales['consumo_real']['Aditivo_l'] }}</td><td>{{ $totales['consumo_esperado']['Aditivo_l'] }}</td><td>{{ $diff['Aditivo_l'] ?? 'N/A' }}</td></tr>
    </tbody>
  </table>
</div>
@endsection
