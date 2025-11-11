@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Control de Concreto en Campo</h2>

    <a href="{{ route('control_concreto_campo.create') }}" class="btn btn-success mb-3">+ Nuevo Registro</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Fecha</th>
                <th>Codigo Envío</th>
                <th>Volumen (m³)</th>
                <th>Temperatura</th>
                <th>Responsable</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($controles as $c)
            <tr>
                <td>{{ $c->id_control_concreto_campo }}</td>
                <td>{{ $c->proyecto->Nombre ?? '' }}</td>
                <td>{{ $c->fecha }}</td>
                <td>{{ $c->codigo_envio_camion }}</td>
                <td>{{ $c->volumen_m3 }}</td>
                <td>{{ $c->temperatura }}</td>
                <td>{{ $c->responsable }}</td>
                <td>
                    <a href="{{ route('control_concreto_campo.edit', $c->id_control_concreto_campo) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('control_concreto_campo.destroy', $c->id_control_concreto_campo) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar registro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
