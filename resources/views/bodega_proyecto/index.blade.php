@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Listado de Bodega Proyecto</h2>

    <form method="GET" action="{{ route('bodega_proyecto.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="id_Proyecto" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar Proyecto --</option>
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}" 
                            {{ $idProyecto == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre ?? 'Proyecto '.$proyecto->id_Proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>No. Vale</th>
                <th>Fecha</th>
                <th>Material</th>
                <th>Unidad</th>
                <th>Cantidad</th>
                <th>Equivalen</th>
                <th>Equivalencia M³</th>
                <th>Conductor</th>
                <th>Placa Vehículo</th>
                <th>Proyecto</th>
                <th>Origen</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bodegas as $bodega)
                <tr>
                    <td>{{ $bodega->id_Bodega_proyecto }}</td>
                    <td>{{ \Carbon\Carbon::parse($bodega->Fecha)->format('d/m/Y') }}</td>
                    <td>{{ $bodega->Material }}</td>
                    <td>{{ $bodega->unidad->Nombre ?? '—' }}</td>
                    <td>{{ $bodega->Cantidad }}</td>
                    <td>{{ $bodega->Equivalen }}</td>
                    <td>{{ $bodega->Equivalencia_M3 }}</td>
                    <td>{{ $bodega->Conductor }}</td>
                    <td>{{ $bodega->Placa_vehiculo }}</td>
                    <td>{{ $bodega->proyecto->Nombre ?? '—' }}</td>
                    <td>{{ $bodega->Origen }}</td>
                    <td class="text-center">
                        <a href="{{ route('bodega_proyecto.show', $bodega->id_Bodega_proyecto) }}" class="btn btn-sm btn-info">
                            Ver
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
