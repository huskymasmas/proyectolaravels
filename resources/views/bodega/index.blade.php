@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Bodega General</h2>

    <a href="{{ route('bodega.create') }}" class="btn btn-success mb-3">+ Nuevo Registro</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Stock Mínimo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bodegas as $b)
                <tr>
                    <td>{{ $b->id_Bodega_general }}</td>
                    <td>{{ $b->Nombre }}</td>
                    <td>{{ $b->Descripcion }}</td>
                    <td>{{ $b->Cantidad }}</td>
                    <td>{{ $b->unidad->Nombre ?? '-' }}</td>
                    <td>{{ $b->stock_minimo }}</td>
                    <td>{{ $b->Estado ? 'Activo' : 'Inactivo' }}</td>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
