@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ isset($nomina) ? 'Editar Nómina' : 'Registrar Nueva Nómina' }}
            </h5>
            <a href="{{ route('nomina.index') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            <form action="{{ isset($nomina) ? route('nomina.update', $nomina->id_Nomina) : route('nomina.store') }}" 
                  method="POST">
                @csrf
                @if(isset($nomina))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="salario_base" class="form-label">Salario Base (Q)</label>
                        <input type="number" step="0.01" name="salario_base" id="salario_base" class="form-control" required
                               value="{{ old('salario_base', $nomina->salario_base ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="bono" class="form-label">Bono (Q)</label>
                        <input type="number" step="0.01" name="bono" id="bono" class="form-control"
                               value="{{ old('bono', $nomina->bono ?? 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="bono_adicional" class="form-label">Bono Adicional (Q)</label>
                        <input type="number" step="0.01" name="bono_adicional" id="bono_adicional" class="form-control"
                               value="{{ old('bono_adicional', $nomina->bono_adicional ?? 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="Estado" class="form-label">Estado</label>
                        <select name="Estado" id="Estado" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="1" {{ old('Estado', $nomina->Estado ?? '') == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('Estado', $nomina->Estado ?? '') == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> {{ isset($nomina) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
