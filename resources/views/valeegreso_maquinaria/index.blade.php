@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Vale de Egreso de Maquinaria</h2>

   <a href="{{ route('valeegreso_maquinaria.create') }}" class="btn btn-primary mb-3">+ Nuevo Vale de Egreso</a>

    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="id_Proyecto" class="form-control">
                    <option value="">-- Filtrar por Proyecto --</option>
                    @foreach($proyectos as $pro)
                        <option value="{{ $pro->id_Proyecto }}" {{ request('id_Proyecto') == $pro->id_Proyecto ? 'selected' : '' }}>
                            {{ $pro->Nombre_Proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Maquinaria</th>
                <th>Cantidad</th>
                <th>Proyecto</th>
                <th>Encargado</th>
                <th>Fecha</th>
                <th>Hora Salida</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->id_vale_egreso_equipo_maquinaria_vehiculo }}</td>
                <td>{{ $item->Nombre }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ $item->proyecto->Nombre_Proyecto ?? 'SIN PROYECTO' }}</td>
                <td>{{ $item->Nombre_encargado }}</td>
                <td>{{ $item->Fecha }}</td>
                <td>{{ $item->Hora_salida }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
