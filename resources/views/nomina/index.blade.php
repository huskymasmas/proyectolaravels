@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Listado de Nóminas</h2>

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('nomina.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Nómina
        </a>
    </div>

    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Salario Base (Q)</th>
                <th>Bono (Q)</th>
                <th>Bono Adicional (Q)</th>
                <th>IGSS</th>
                <th>ISR</th>
                <th>IRTRA</th>
                <th>Descuentos Totales</th>
                <th>Salario Neto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nominas as $nomina)
                <tr>
                    <td>{{ $nomina->id_Nomina }}</td>
                    <td>Q {{ number_format($nomina->salario_base, 2) }}</td>
                    <td>Q {{ number_format($nomina->bono, 2) }}</td>
                    <td>Q {{ number_format($nomina->bono_adicional, 2) }}</td>
                    <td>Q {{ number_format($nomina->Descuento_IGSS, 2) }}</td>
                    <td>Q {{ number_format($nomina->Descuento_ISR, 2) }}</td>
                    <td>Q {{ number_format($nomina->Descuento_IRTRA, 2) }}</td>
                    <td>Q {{ number_format($nomina->descuentos, 2) }}</td>
                    <td>Q {{ number_format($nomina->Salario_Neto, 2) }}</td>
                    <td>
                        <a href="{{ route('nomina.edit', $nomina->id_Nomina) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('nomina.destroy', $nomina->id_Nomina) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Desea eliminar esta nómina?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
