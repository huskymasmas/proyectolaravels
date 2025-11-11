@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ isset($detalle) ? 'Editar Detalle de Nómina' : 'Nuevo Detalle de Nómina' }}</h2>

    <form action="{{ isset($detalle) ? route('detalle_nomina.update', $detalle->id_detalle_nomina) : route('detalle_nomina.store') }}" method="POST">
        @csrf
        @if(isset($detalle))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="id_Empleados" class="form-label">Empleado</label>
            <select name="id_Empleados" class="form-select" required>
                <option value="">Seleccione un empleado</option>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->id_Empleados }}"
                        {{ isset($detalle) && $detalle->id_Empleados == $empleado->id_Empleados ? 'selected' : '' }}>
                        {{ $empleado->Nombres }} {{ $empleado->Apellido }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Horas Extras</label>
            <input type="number" name="Horas_extras" class="form-control"
                   value="{{ old('Horas_extras', $detalle->Horas_extras ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cantidad de Días</label>
            <input type="number" name="cantidad_dias" class="form-control"
                   value="{{ old('cantidad_dias', $detalle->cantidad_dias ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($detalle) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('detalle_nomina.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
