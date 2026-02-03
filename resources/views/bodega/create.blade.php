@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Nuevo Registro de Bodega</h2>

    <form action="{{ route('bodega.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="Nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="Descripcion" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" step="0.01" name="Cantidad" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Unidad</label>
            <select name="id_Unidades" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                @foreach($unidades as $u)
                    <option value="{{ $u->id_Unidades }}">{{ $u->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Stock Mínimo</label>
            <input type="number" name="stock_minimo" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="Estado" class="form-control">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('bodega.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
