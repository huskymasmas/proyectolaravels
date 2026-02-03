@extends('layouts.app')

@section('content')
<div class="container p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Listado de Avances de Trabajo</h3>
        <a href="{{ route('avances.create') }}" class="btn btn-primary">+ Nuevo Avance</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Aldea</th>
                <th>Trabajo</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Fecha creación</th>
            </tr>
        </thead>

        <tbody>
            @foreach($avances as $avance)
            <tr>
                <td>{{ $avance->id_avances_trabajo }}</td>
                <td>{{ $avance->id_aldea }}</td>
                <td>{{ $avance->nombre_trabajo }}</td>
                <td>{{ $avance->Cantidad }}</td>
                <td>{{ $avance->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ $avance->Fecha_creacion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
