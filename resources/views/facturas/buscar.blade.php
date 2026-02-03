@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-3">Buscar Factura</h2>

    {{-- FORMULARIO DE BÚSQUEDA --}}
    <form action="{{ route('facturas.buscar') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="Num_factura" class="form-control" placeholder="Ingrese número de factura"
                   value="{{ isset($numFactura) ? $numFactura : '' }}">

            <button class="btn btn-primary">Buscar</button>
        </div>
    </form>

    {{-- MENSAJE SI NO INGRESA NÚMERO --}}
    @if(isset($mensaje))
        <div class="alert alert-warning">{{ $mensaje }}</div>
    @endif


    {{-- RESULTADOS --}}
    @if(isset($registros) || isset($registros2))

        <h4 class="mt-4">Resultados para factura: <strong>{{ $numFactura }}</strong></h4>

        {{-- BOTÓN EXPORTAR --}}
        <a href="{{ route('facturas.exportar', $numFactura) }}" class="btn btn-success mb-3">
            Exportar a Excel
        </a>

        {{-- TABLA 1: tbl_vale_ingreso --}}
        @if(isset($registros) && count($registros) > 0)
            <h5>Compras de Materiales</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nit</th>
                        <th>Cantidad</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $item)
                        <tr>
                            <td>{{ $item->nit }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>{{ number_format($item->precio_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- TABLA 2: tbl_vale_ingreso_equipo_maquinaria_vehiculo --}}
        @if(isset($registros2) && count($registros2) > 0)
            <h5>Ingresos de Equipo/Maquinaria/Vehículo</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nit</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros2 as $item2)
                        <tr>
                            <td>{{ $item2->nit }}</td>
                            <td>{{ $item2->cantidad }}</td>
                            <td>{{ number_format($item2->costo, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- TOTAL GENERAL --}}
        @if(isset($totaltotal))
            <div class="alert alert-info">
                <h5>Total Factura: Q {{ number_format($totaltotal, 2) }}</h5>
            </div>
        @endif

    @endif

</div>
@endsection
