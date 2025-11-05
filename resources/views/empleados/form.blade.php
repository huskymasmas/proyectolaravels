@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ isset($empleado) ? 'Editar Empleado' : 'Registrar Nuevo Empleado' }}</h5>
            <a href="{{ route('empleados.index') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            <form action="{{ isset($empleado) ? route('empleados.update', $empleado->id_Empleados) : route('empleados.store') }}" 
                  method="POST">
                @csrf
                @if(isset($empleado))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="Nombres" class="form-control" 
                               value="{{ old('Nombres', $empleado->Nombres ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="Apellido" class="form-control" 
                               value="{{ old('Apellido', $empleado->Apellido ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="Apellido2" class="form-control" 
                               value="{{ old('Apellido2', $empleado->Apellido2 ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sexo</label>
                        <select name="Sexo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="M" {{ old('Sexo', $empleado->Sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('Sexo', $empleado->Sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">DPI</label>
                        <input type="number" name="DPI" class="form-control" 
                               value="{{ old('DPI', $empleado->DPI ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Número Telefónico</label>
                        <input type="number" name="Numero" class="form-control" 
                               value="{{ old('Numero', $empleado->Numero ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="Fecha_nacimiento" class="form-control" 
                               value="{{ old('Fecha_nacimiento', $empleado->Fecha_nacimiento ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de Inicio</label>
                        <input type="date" name="Fecha_inicio" class="form-control" 
                               value="{{ old('Fecha_inicio', $empleado->Fecha_inicio ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Rol</label>
                        <select name="id_Rol" class="form-select" required>
                            <option value="">Seleccione rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id_Rol }}" 
                                    {{ old('id_Rol', $empleado->id_Rol ?? '') == $rol->id_Rol ? 'selected' : '' }}>
                                    {{ $rol->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nómina (Pago Total)</label>
                        <select name="id_Nomina" id="id_Nomina" class="form-select" required>
                            <option value="">Seleccione una nómina</option>
                            @foreach ($nominas as $nomina)
                                <option value="{{ $nomina->id_Nomina }}"
                                    data-pago="{{ number_format($nomina->total_pago, 2, '.', '') }}"
                                    {{ old('id_Nomina', $empleado->id_Nomina ?? '') == $nomina->id_Nomina ? 'selected' : '' }}>
                                    Q{{ number_format($nomina->total_pago, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label">Departamento</label>
                        <select name="id_Departamento" id="id_Departamento" class="form-select" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id_Departamento }}"
                                    {{ old('id_Departamento', $empleado->id_Departamento ?? '') == $departamento->id_Departamento ? 'selected' : '' }}>
                                    {{ $departamento->Nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                                    Q{{ number_format($nomina->total_pago, 2) }}
                                </option>
                        
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Salario Asignado</label>
                        <input type="text" id="Salario_asignado" name="Salario_asignado" 
                               class="form-control" readonly 
                               value="Q{{ number_format($empleado->Salario_asignado ?? 0, 2) }}">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        {{ isset($empleado) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para mostrar automáticamente el salario al elegir la nómina --}}
<script>
document.getElementById('id_Nomina').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const pago = selectedOption.getAttribute('data-pago') || 0;
    document.getElementById('Salario_asignado').value = 'Q' + parseFloat(pago).toFixed(2);
});
</script>
@endsection
