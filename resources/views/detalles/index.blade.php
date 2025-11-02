@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Gestión de Detalles de Carpeta y Cuenta</h2>
<form method="GET" action="{{ route('detalles.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="id_Proyecto" class="form-label">Filtrar por Proyecto:</label>
                <select name="id_Proyecto" id="id_Proyecto" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Todos los Proyectos --</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}"
                            {{ $idProyecto == $proyecto->id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <button class="btn btn-secondary" onclick="window.location='{{ route('detalles.create') }}'">
        ingresar
        </button>
    <h4> Detalles Carpeta</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Unidad</th>
                <th>Valor</th>
                <th>Cálculo</th>
                <th>Resultado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carpetas as $carpeta)
            <tr>
                <td>{{ $carpeta->proyecto->Nombre ?? 'N/A' }}</td>
                <td>{{ $carpeta->unidad->Nombre ?? 'N/A' }}</td>
                <td>{{ $carpeta->Valor }}</td>
                <td>{{ $carpeta->Calculo }}</td>
                <td>{{ $carpeta->Resultado }}</td>
                <td>
                    <form action="{{ route('detalles.destroy', ['tipo' => 'carpeta', 'id' => $carpeta->id_detalle_Carpeta]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Carpeta:</strong> {{ number_format($totalCarpeta, 2) }}</p>

    <h4> Detalles Cuenta</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Unidad</th>
                <th>Valor</th>
                <th>Cálculo</th>
                <th>Resultado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cuentas as $cuenta)
            <tr>
                <td>{{ $cuenta->proyecto->Nombre ?? 'N/A' }}</td>
                <td>{{ $cuenta->unidad->Nombre ?? 'N/A' }}</td>
                <td>{{ $cuenta->Valor }}</td>
                <td>{{ $cuenta->Calculo }}</td>
                <td>{{ $cuenta->Resultado }}</td>
                <td>
                    <form action="{{ route('detalles.destroy', ['tipo' => 'cuenta', 'id' => $cuenta->id_Detalle_Cuenta]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Cuenta:</strong> {{ number_format($totalCuenta, 2) }}</p>
    <hr>
    <h4> Total General: {{ number_format($totalGeneral, 2) }}</h4>
</div>
@endsection
