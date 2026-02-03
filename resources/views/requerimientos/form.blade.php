@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <h2 class="text-center mb-4 fw-bold text-primary">Nuevo Requerimiento de Material</h2>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('requerimientos.store') }}">
                @csrf
                <div class="row g-3">

                    {{-- Proyecto --}}
                    <div class="col-md-6">
                        <label for="id_Proyecto" class="form-label fw-semibold">Proyecto</label>
                        <select name="id_Proyecto" id="id_Proyecto" class="form-select shadow-sm" required>
                            <option value="">Seleccione un proyecto</option>
                            @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id_Proyecto }}">
                                    {{ $proyecto->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dosificación --}}
                    <div class="col-md-6">
                        <label for="id_Dosificacion" class="form-label fw-semibold">Dosificación</label>
                        <select name="id_Dosificacion" id="id_Dosificacion" class="form-select shadow-sm" required>
                            <option value="">Seleccione dosificación</option>
                            @foreach ($dosificaciones as $dos)
                                <option value="{{ $dos->id_Dosificacion }}">
                                    {{ $dos->id_Tipo_dosificacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Detalle Obra --}}
                    <div class="col-md-6">
                        <label for="id_Detalle_obra" class="form-label fw-semibold">Detalle de Obra</label>
                        <select name="id_Detalle_obra" id="id_Detalle_obra" class="form-select shadow-sm" required>
                            <option value="">Seleccione detalle</option>
                            @foreach ($detallesObra as $detalle)
                                <option value="{{ $detalle->id_Detalle_obra }}">
                                    {{ $detalle->Tipo_Obra }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> Guardar Requerimiento
                    </button>
                    <a href="{{ route('requerimientos.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
