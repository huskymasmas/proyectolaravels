@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Buscar Factura</h2>

    {{-- Formulario de búsqueda --}}
    <form action="{{ route('facturas.buscar') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="Num_factura" class="form-control" placeholder="Ingrese el número de factura" value="{{ old('Num_factura', $numFactura ?? '') }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    {{-- Mostrar mensaje si no hay resultados --}}
    @isset($mensaje)
        <div class="alert alert-warning">{{ $mensaje }}</div>
    @endisset

    {{-- Mostrar tabla si hay resultados --}}
    @if(isset($registros) && $registros->count() > 0)
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Número de Factura</th>
                     <th>NIT</th>
                    <th>Precio Total</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $item)
                    <tr>
                        <td>{{ $item->Num_factura }}</td>
                        <td>{{ $item->nit }}</td>
                        <td>{{ number_format($item->precio_total, 2) }}</td>
                        <td>{{ $item->cantidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Mostrar el total abajo --}}
        <div class="alert alert-success text-end">
            <strong>Total ingresado en la factura:</strong> Q{{ number_format($total, 2) }}
        </div>
    @elseif(isset($registros))
        <div class="alert alert-danger">No se encontraron resultados para la factura {{ $numFactura }}</div>
    @endif

    @if(isset($total))

    {{-- Botón para exportar a Excel --}}
    <div class="text-end">
        <a href="{{ route('facturas.exportar', ['numFactura' => $numFactura]) }}" class="btn btn-success">
            Exportar a Excel
        </a>
    </div>
@endif


</div>
@endsection
