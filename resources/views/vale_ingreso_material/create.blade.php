@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Ingreso de Materiales</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vale_ingreso_material.store') }}" method="POST">
        @csrf

        {{-- Proyecto --}}
        <div class="mb-3">
            <label for="id_Proyecto">Proyecto (opcional)</label>
            <select name="id_Proyecto" id="id_Proyecto" class="form-control">
                <option value="">-- Bodega General --</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}">{{ $proyecto->Nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Nombre Material --}}
        <div class="mb-3">
            <label for="Nombre">Material</label>
            <input type="text" name="Nombre" class="form-control" required>
        </div>

        {{-- Unidad --}}
        <div class="mb-3">
            <label for="id_Unidades">Unidad</label>
            <select name="id_Unidades" class="form-control" required>
                @foreach($unidades as $unidad)
                    <option value="{{ $unidad->id_Unidades }}">{{ $unidad->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Nombre_encargado">Nombre Encargado</label>
            <input type="text" name="Nombre_encargado" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Nombre_Bodeguero">Nombre Bodeguero</label>
            <input type="text" name="Nombre_Bodeguero" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Nombre_Residente_obra">Nombre Residente de Obra</label>
            <input type="text" name="Nombre_Residente_obra" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Nombre_conductor">Conductor</label>
            <input type="text" name="Nombre_conductor" class="form-control">
        </div>

        <div class="mb-3">
            <label for="marca">Marca</label>
            <input type="text" name="marca" class="form-control">
        </div>

        <div class="mb-3">
            <label for="serie">Serie</label>
            <input type="text" name="serie" class="form-control">
        </div>

        <div class="mb-3">
            <label for="placa">Placa</label>
            <input type="text" name="placa" class="form-control">
        </div>

        <div class="mb-3">
            <label for="Fecha_ingreso">Fecha de Ingreso</label>
            <input type="date" name="Fecha_ingreso" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Hora_llegada">Hora de Llegada</label>
            <input type="time" name="Hora_llegada" class="form-control">
        </div>

        <div class="mb-3">
            <label for="empresa_proveedora">Empresa Proveedora</label>
            <input type="text" name="empresa_proveedora" class="form-control">
        </div>

        <div class="mb-3">
            <label for="cantidad">Cantidad</label>
            <input type="number" step="0.01" name="cantidad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="Total_pagar">Total a Pagar</label>
            <input type="number" step="0.001" name="Total_pagar" class="form-control">
        </div>

        <div class="mb-3">
            <label for="estado_fisico">Estado Físico</label>
            <input type="text" name="estado_fisico" class="form-control">
        </div>

        <div class="mb-3">
            <label for="costo">Costo Unitario</label>
            <input type="number" step="0.001" name="costo" class="form-control">
        </div>

        <div class="mb-3">
            <label for="id_moneda">Moneda</label>
            <select name="id_moneda" class="form-control">
                @foreach(\App\Models\Moneda::all() as $moneda)
                    <option value="{{ $moneda->id_moneda }}">{{ $moneda->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Num_factura">Número de Factura</label>
            <input type="text" name="Num_factura" class="form-control">
        </div>

        <div class="mb-3">
            <label for="nit">NIT</label>
            <input type="text" name="nit" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Registrar Ingreso</button>
    </form>
</div>
@endsection
