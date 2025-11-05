@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Listado de Roles</h3>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Nuevo Rol</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $rol)
                <tr>
                    <td>{{ $rol->id_Rol }}</td>
                    <td>{{ $rol->Nombre }}</td>
                    <td>{{ $rol->Estado ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <form action="{{ route('roles.destroy', $rol->id_Rol) }}" method="POST" onsubmit="return confirm('¿Eliminar rol?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
