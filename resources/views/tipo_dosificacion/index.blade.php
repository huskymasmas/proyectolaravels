@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ route('tipo_dosificacion.create') }}" class="btn btn-primary mb-3">
        Crear Tipo de Dosificación
    </a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPlano">
    Subir Plano
    </button>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->id_Tipo_dosificacion }}</td>
                <td>{{ $item->Nombre }}</td>
                <td>
                    <a href="{{ route('tipo_dosificacion.edit', $item->id_Tipo_dosificacion) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('tipo_dosificacion.destroy', $item->id_Tipo_dosificacion) }}" method="POST" style="display:inline-block;">
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
