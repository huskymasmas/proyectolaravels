@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Listado de Vales de Egreso</h2>

    <a href="{{ route('vale_egreso.create') }}" class="btn btn-primary mb-3">Crear Vale</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No. Vale</th>
                <th>Proyecto</th>
                <th>Tipo Concreto</th>
                <th>Volumen (m³)</th>
                <th>Fecha</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($vales as $v)
            <tr>
                <td>{{ $v->id_Vale_despacho }}</td>
                <td>{{ $v->despacho->proyecto->Nombre ?? '---' }}</td>
                <td>{{ $v->despacho->Tipo_Concreto }}</td>
                <td>{{ $v->despacho->Volumen_carga_M3 }}</td>
                <td>{{ $v->despacho->Fecha }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>
@endsection
