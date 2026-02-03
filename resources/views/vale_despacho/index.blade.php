@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Listado de Vales de Despacho</h2>

    <a href="{{ route('vale_despacho.create') }}" class="btn btn-success mb-3">+ Nuevo Vale</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No Vale</th>
                <th>Proyecto</th>
                <th>Volumen (m³)</th>
                <th>Tipo Concreto</th>
                <th>Fecha</th>
                <th>Aditivos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vales as $vale)
                <tr>
                    <td>{{ $vale->No_vale }}</td>
                    <td>{{ $vale->despacho->id_Proyecto ?? '-' }}</td>
                    <td>{{ $vale->despacho->Volumen_carga_M3 ?? '-' }}</td>
                    <td>{{ $vale->despacho->Tipo_Concreto ?? '-' }}</td>
                    <td>{{ $vale->despacho->Fecha ?? '-' }}</td>
                    <td>{{ $vale->aditivos->Nombre1 ?? '-' }}</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-info btn-sm">Ver</a>
                        <a href="#" class="btn btn-warning btn-sm">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay vales registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
