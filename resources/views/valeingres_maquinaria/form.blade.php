@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3>{{ isset($item) ? 'Editar Ingreso' : 'Nuevo Ingreso' }}</h3>

    <form action="{{ isset($item) 
            ? route('valeingres_maquinaria.update', $item->id_vale_equipo_maquinaria_vehiculo)
            : route('valeingres_maquinaria.store') }}" 
        method="POST">

        @csrf
        @if(isset($item))
            @method('PUT')
        @endif


        <div class="row g-3 mt-2">

            <div class="col-md-4">
                <label>Nombre maquinaria *</label>
                <input type="text" name="Nombre" class="form-control"
                       value="{{ old('Nombre', $item->Nombre ?? '') }}" required>
            </div>

            <div class="col-md-4">
                <label>Encargado *</label>
                <input type="text" name="Nombre_encargado" class="form-control"
                       value="{{ old('Nombre_encargado', $item->Nombre_encargado ?? '') }}" required>
            </div>

            <div class="col-md-4">
                <label>Bodeguero *</label>
                <input type="text" name="Nombre_Bodeguero" class="form-control"
                       value="{{ old('Nombre_Bodeguero', $item->Nombre_Bodeguero ?? '') }}" required>
            </div>

            <div class="col-md-3">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control"
                       value="{{ old('marca', $item->marca ?? '') }}">
            </div>

            <div class="col-md-3">
                <label>Serie</label>
                <input type="text" name="serie" class="form-control"
                       value="{{ old('serie', $item->serie ?? '') }}">
            </div>

            <div class="col-md-3">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control"
                       value="{{ old('placa', $item->placa ?? '') }}">
            </div>

            <div class="col-md-3">
                <label>Cantidad *</label>
                <input type="number" step="0.001" name="cantidad" class="form-control"
                       value="{{ old('cantidad', $item->cantidad ?? '') }}" required>
            </div>

            <div class="col-md-4">
                <label>Proyecto (opcional)</label>
                <select name="id_Proyecto" class="form-control">
                    <option value="">-- Bodega Central --</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_Proyecto }}"
                            {{ old('id_Proyecto', $item->id_Proyecto ?? '') == $p->id_Proyecto ? 'selected' : '' }}>
                            {{ $p->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-4">
                <label>Estado físico</label>
                <input type="text" name="estado_fisico" class="form-control"
                       value="{{ old('estado_fisico', $item->estado_fisico ?? '') }}">
            </div>

            <div class="col-md-3">
                <label>Empresa proveedora</label>
                <input type="text" name="empresa_proveedora" class="form-control"
                       value="{{ old('empresa_proveedora', $item->empresa_proveedora ?? '') }}">
            </div>

            <div class="col-md-3">
            <label>Moneda </label>
                <select name="id_moneda" class="form-control" required>
                    <option value="">Seleccione...</option>
                            @foreach($monedas as $m)
                                <option value="{{ $m->id_moneda }}"
                                    {{ old('id_moneda', $item->id_moneda ?? '') == $m->id_moneda ? 'selected' : '' }}>
                                    {{ $m->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>


            <div class="col-md-3">
                <label>Costo</label>
                <input type="number" step="0.01" name="costo" class="form-control"
                       value="{{ old('costo', $item->costo ?? '') }}">
            </div>

            <div class="col-md-3">
                <label>Número Factura</label>
                <input type="text" name="Num_factura" class="form-control"
                       value="{{ old('Num_factura', $item->Num_factura ?? '') }}">
            </div>

            <div class="col-md-3">
                <label>NIT</label>
                <input type="text" name="nit" class="form-control"
                       value="{{ old('nit', $item->nit ?? '') }}">
            </div>

        </div>

        <div class="mt-3">
            <button class="btn btn-success">
                {{ isset($item) ? 'Actualizar' : 'Guardar' }}
            </button>

            <a href="{{ route('valeingres_maquinaria.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
