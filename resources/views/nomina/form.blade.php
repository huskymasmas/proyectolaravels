@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ $nomina->exists ? 'Editar Nómina' : 'Nueva Nómina' }}</h2>

    <form action="{{ $nomina->exists ? route('nomina.update', $nomina->id_Nomina) : route('nomina.store') }}" method="POST">
        @csrf
        @if($nomina->exists)
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Sueldo Base:</label>
                <input type="number" step="0.01" name="sueldo_Base" class="form-control" value="{{ old('sueldo_Base', $nomina->sueldo_Base) }}" required>
            </div>
            <div class="col-md-6">
                <label>Costo Horas Extras:</label>
                <input type="number" step="0.01" name="Costo_horas_extras" class="form-control" value="{{ old('Costo_horas_extras', $nomina->Costo_horas_extras) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Bonos:</label>
                <input type="number" step="0.01" name="Bonos" class="form-control" value="{{ old('Bonos', $nomina->Bonos) }}">
            </div>
            <div class="col-md-4">
                <label>Bonos Adicionales:</label>
                <input type="number" step="0.01" name="Bonos_adicional" class="form-control" value="{{ old('Bonos_adicional', $nomina->Bonos_adicional) }}">
            </div>
            <div class="col-md-4">
                <label>Viáticos:</label>
                <input type="number" step="0.01" name="viaticosnomina" class="form-control" value="{{ old('viaticosnomina', $nomina->viaticosnomina) }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Estado:</label>
            <select name="Estado" class="form-control" required>
                <option value="1" {{ old('Estado', $nomina->Estado) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('Estado', $nomina->Estado) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ $nomina->exists ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('nomina.index') }}" class="btn btn-secondary">Regresar</a>
    </form>
</div>
@endsection
