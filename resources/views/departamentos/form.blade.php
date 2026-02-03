@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ isset($departamento) ? 'Editar Departamento' : 'Registrar Nuevo Departamento' }}</h5>
            <a href="{{ route('departamentos.index') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            <form action="{{ isset($departamento) ? route('departamentos.update', $departamento->id_Departamento) : route('departamentos.store') }}" method="POST">
                @csrf
                @if(isset($departamento))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Departamento</label>
                        <input type="text" name="Nombres" class="form-control" required
                            value="{{ old('Nombres', $departamento->Nombres ?? '') }}">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> {{ isset($departamento) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
