@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Listado de Tramos de Aplicación</h2>

    <div class="mb-3">
        <form method="GET" action="{{ route('tramo_aplicacion.index') }}" class="row g-2">
            <div class="col-md-4">
                <select name="aldea" class="form-control">
                    <option value="">-- Filtrar por aldea --</option>
                    @foreach($aldeas as $aldea)
                        <option value="{{ $aldea->id_aldea }}" {{ request('aldea') == $aldea->id_aldea ? 'selected' : '' }}>
                            {{ $aldea->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('tramo_aplicacion.create') }}" class="btn btn-success">Nuevo Tramo</a>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Aldea</th>
                <th>Fecha</th>
                <th>Aplicador</th>
                <th>Supervisor</th>
                <th>Rodaduras</th>
                <th>Cunetas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tramos as $tramo)
                <tr>
                    <td>{{ $tramo->id_tramo }}</td>
                    <td>{{ $tramo->aldea_nombre ?? 'Sin Aldea' }}</td>
                    <td>{{ $tramo->fecha }}</td>
                    <td>{{ $tramo->aplicador }}</td>
                    <td>{{ $tramo->supervisor }}</td>
                    <td>
                        @foreach($tramo->rodaduras as $rod)
                            <div>{{ $rod->id_Ejes }}: {{ $rod->estacion_inicial }} - {{ $rod->estacion_final }}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($tramo->cunetas as $cun)
                            <div>{{ $cun->id_Ejes }}: {{ $cun->estacion_inicial }} - {{ $cun->estacion_final }}</div>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('tramo_aplicacion.edit', $tramo->id_tramo) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
