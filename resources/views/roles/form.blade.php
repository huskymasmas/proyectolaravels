@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ isset($rol) ? 'Editar Rol' : 'Registrar Nuevo Rol' }}</h5>
            <a href="{{ route('roles.index') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            <form action="{{ isset($rol) ? route('roles.update', $rol->id_Rol) : route('roles.store') }}" method="POST">
                @csrf
                @if(isset($rol))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="Nombre" class="form-label">Nombre del Rol</label>
                        <input type="text" name="Nombre" id="Nombre" class="form-control" 
                               value="{{ old('Nombre', $rol->Nombre ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="Estado" class="form-label">Estado</label>
                        <select name="Estado" id="Estado" class="form-select" required>
                            <option value="1" {{ old('Estado', $rol->Estado ?? '') == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('Estado', $rol->Estado ?? '') == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> {{ isset($rol) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
