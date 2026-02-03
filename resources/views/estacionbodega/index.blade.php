@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2>Estación de Bodega</h2>

    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Proyecto</th>
                <th>Fecha Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->id_estacion_Bodega }}</td>
                    <td>{{ $item->material }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->unidad->Nombre_unidad ?? 'N/A' }}</td>
                    <td>{{ $item->proyecto }}</td>
                    <td>{{ $item->Fecha_creacion }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
