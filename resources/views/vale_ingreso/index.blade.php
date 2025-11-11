@extends('layouts.app')

@section('title', 'Vales de Ingreso')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📋 Lista de Vales de Ingreso</h3>
        <a href="{{ route('vale_ingreso.create') }}" class="btn btn-primary">➕ Nuevo Vale</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Proyecto</th>
                        <th>Nombre del Encargado de Planta</th>
                        <th>Nombre del Bodegero</th>
                        <th>Nombre del Residente de Obra</th>
                        <th>Número de Factura</th>
                        <th>Precio Unitario</th>
                        <th>NIT</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vales as $vale)
                        <tr>
                            <td>{{ $vale->id_Vale_ingreso }}</td>
                            <td>{{ $vale->Fecha }}</td>
                            <td>{{ $vale->Tipo_material }}</td>
                            <td>{{ $vale->Cantidad }}</td>
                            <td>{{ $vale->unidad->Nombre ?? 'N/A' }}</td>
                            <td>{{ $vale->proyecto->Nombre ?? 'N/A' }}</td>
                            <td>{{ $vale->Nombre_encargado_palata }}</td>
                            <td>{{ $vale->Nombre_bodegero }}</td>
                            <td>{{ $vale->Nombre_residente_obra }}</td>
                            <td>{{ $vale->Num_factura }}</td>
                            <td>{{ $vale->nit }}</td>
                            <td>{{ $vale->precio_unitario }}</td>
                            <td>{{ $vale->precio_total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay vales registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
