@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Listado de Nóminas</h2>

    <a href="{{ route('nomina.create') }}" class="btn btn-primary mb-3"> Nueva Nómina</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sueldo Base</th>
                <th>Costo Horas Extras</th>
                <th>Bonos</th>
                <th>Bonos Adicionales</th>
                <th>Viáticos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nominas as $nomina)
                <tr>
                    <td>{{ $nomina->id_Nomina }}</td>
                    <td>{{ number_format($nomina->sueldo_Base, 2) }}</td>
                    <td>{{ number_format($nomina->Costo_horas_extras, 2) }}</td>
                    <td>{{ number_format($nomina->Bonos, 2) }}</td>
                    <td>{{ number_format($nomina->Bonos_adicional, 2) }}</td>
                    <td>{{ number_format($nomina->viaticosnomina, 2) }}</td>
                    <td>{{ $nomina->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <a href="{{ route('nomina.edit', $nomina->id_Nomina) }}" class="btn btn-sm btn-warning"> Editar</a>
                        <form action="{{ route('nomina.destroy', $nomina->id_Nomina) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta nómina?')"> Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
