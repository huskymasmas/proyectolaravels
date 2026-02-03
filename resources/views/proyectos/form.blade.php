@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ isset($proyecto) ? 'Editar Proyecto' : 'Registrar Nuevo Proyecto' }}</h5>
            <a href="{{ route('proyectos.index') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            <form action="{{ isset($proyecto) ? route('proyectos.update', $proyecto->id_Proyecto) : route('proyectos.store') }}" 
                  method="POST">
                @csrf
                @if(isset($proyecto))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="Nombre" class="form-label">Nombre del Proyecto</label>
                        <input type="text" name="Nombre" id="Nombre" class="form-control" 
                               value="{{ old('Nombre', $proyecto->Nombre ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="Descripcion" class="form-label">Descripción</label>
                        <input type="text" name="Descripcion" id="Descripcion" class="form-control" 
                               value="{{ old('Descripcion', $proyecto->detalle->Descripcion ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="Ubicacion" class="form-label">Ubicación</label>
                        <input type="text" name="Ubicacion" id="Ubicacion" class="form-control" 
                               value="{{ old('Ubicacion', $proyecto->detalle->Ubicacion ?? '') }}">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> {{ isset($proyecto) ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
