@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Detalle Nómina</h3>
    <form action="{{ route('reportes.nomina.detalle.update', $detalle->id_detalle_nomina) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-2">
            <label>Horas Extras</label>
            <input type="number" step="0.01" name="Horas_extras" value="{{ $detalle->Horas_extras }}" class="form-control" required>
        </div>

        <div class="form-group mb-2">
            <label>Cantidad de Días</label>
            <input type="number" name="cantidad_dias" value="{{ $detalle->cantidad_dias }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('reportes.nomina.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
