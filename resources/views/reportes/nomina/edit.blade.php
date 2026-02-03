@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Nómina</h3>
    <form action="{{ route('reportes.nomina.update', $nomina->id_Nomina) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-2">
            <label>Sueldo Base</label>
            <input type="number" step="0.01" name="sueldo_Base" value="{{ $nomina->sueldo_Base }}" class="form-control" required>
        </div>

        <div class="form-group mb-2">
            <label>Costo Horas Extras</label>
            <input type="number" step="0.01" name="Costo_horas_extras" value="{{ $nomina->Costo_horas_extras }}" class="form-control" required>
        </div>

        <div class="form-group mb-2">
            <label>Viáticos</label>
            <input type="number" step="0.01" name="viaticosnomina" value="{{ $nomina->viaticosnomina }}" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label>Estado</label>
            <select name="Estado" class="form-control" required>
                <option value="1" {{ $nomina->Estado == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ $nomina->Estado == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('reportes.nomina.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
