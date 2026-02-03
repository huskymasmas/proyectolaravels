@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Listado de Egresos de Materiales</h2>

    <form method="GET" action="{{ route('vale_egreso_material.index') }}" class="mb-3 d-flex gap-2">
        <select name="id_Proyecto" class="form-control">
            <option value="">-- Todos los Proyectos --</option>
            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id_Proyecto }}" @if(request('id_Proyecto')==$proyecto->id_Proyecto) selected @endif>
                    {{ $proyecto->Nombre }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    <a href="{{ route('vale_egreso_material.create') }}" class="btn btn-danger mb-3">Nuevo Egreso</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Material</th>
                <th>Proyecto</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Fecha</th>
                <th>Conductor</th>
                <th>Placa</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vales as $vale)
            <tr>
                <td>{{ $vale->id_vale_egreso_Materiales_varios }}</td>
                <td>{{ $vale->Nombre }}</td>
                <td>{{ $vale->proyecto->Nombre ?? '' }}</td>
                <td>{{ $vale->cantidad }}</td>
                <td>{{ $vale->unidad->Nombre ?? '' }}</td>
                <td>{{ $vale->Fecha }}</td>
                <td>{{ $vale->Nombre_conductor }}</td>
                <td>{{ $vale->placa }}</td>
                <td>{{ $vale->observaciones }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
