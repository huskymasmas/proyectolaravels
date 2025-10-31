@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Dosificaciones</h2>

    <form method="GET" action="{{ route('dosificacion.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <select name="tipo" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Filtrar por tipo --</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id_Tipo_dosificacion }}" 
                            {{ $tipoSeleccionado == $tipo->id_Tipo_dosificacion ? 'selected' : '' }}>
                            {{ $tipo->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <a href="{{ route('dosificacion.create') }}" class="btn btn-primary mb-3">Nueva Dosificación</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Cemento</th>
                <th>Arena</th>
                <th>Pedrín</th>
                <th>Aditivo</th>
                <th>Nota</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosificaciones as $d)
            <tr>
                <td>{{ $d->id_Dosificacion }}</td>
                <td>{{ $d->Tipo_dosificador->Nombre ?? 'Sin tipo' }}</td>
                <td>{{ $d->Cemento }}</td>
                <td>{{ $d->Arena }}</td>
                <td>{{ $d->Pedrin }}</td>
                <td>{{ $d->Aditivo }}</td>
                <td>{{ $d->Nota }}</td>
                <td>
                    <a href="{{ route('dosificacion.edit', $d->id_Dosificacion) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('dosificacion.destroy', $d->id_Dosificacion) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta dosificación?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
