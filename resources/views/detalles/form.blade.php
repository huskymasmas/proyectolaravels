@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Agregar Detalle</h2>

    <form action="{{ route('detalles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Tipo de Detalle</label>
            <select name="tipo" class="form-control" required>
                <option value="">Seleccione...</option>
                <option value="carpeta">Carpeta</option>
                <option value="cuenta">Cuenta</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Proyecto</label>
            <select name="id_Proyecto" class="form-control" required>
                <option value="">Seleccione un proyecto</option>
                @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id_Proyecto }}">{{ $proyecto->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Unidad</label>
            <select name="id_Unidades" class="form-control" required>
                <option value="">Seleccione una unidad</option>
                @foreach($unidades as $unidad)
                <option value="{{ $unidad->id_Unidades }}">{{ $unidad->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Valor</label>
            <input type="number" name="Valor" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Cálculo (%)</label>
            <input type="number" name="Calculo" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Detalle</label>
            <input type="text" name="Detalle" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('detalles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
