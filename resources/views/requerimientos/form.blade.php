@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4"> Nuevo Requerimiento de Material</h2>

    <form method="POST" action="{{ route('requerimientos.store') }}">
        @csrf
        <div class="row g-3">
            {{-- Proyecto --}}
            <div class="col-md-6">
                <label for="id_Proyecto" class="form-label">Proyecto</label>
                <select name="id_Proyecto" id="id_Proyecto" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id_Proyecto }}">{{ $proyecto->Nombre }}</option>
                    @endforeach
                </select>
            </div>
            


            {{-- Dosificación --}}
            <div class="col-md-6">
                <label for="id_Dosificacion" class="form-label">Dosificación</label>
                <select name="id_Dosificacion" id="id_Tipo_dosificacion" class="form-select" required>
                    <option value="">Seleccione dosificación</option>
                    @foreach ($dosificaciones as $dos)
                        <option value="{{ $dos->id_Dosificacion }}">{{ $dos->id_Tipo_dosificacion }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Detalle Obra --}}
            <div class="col-md-6">
                <label for="id_Detalle_obra" class="form-label">Detalle de Obra</label>
                <select name="id_Detalle_obra" id="id_Detalle_obra" class="form-select" required>
                    <option value="">Seleccione detalle</option>
                    @foreach ($detallesObra as $detalle)
                        <option value="{{ $detalle->id_Detalle_obra }}">{{ $detalle->Tipo_Obra }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-4"> Guardar Requerimiento</button>
            <a href="{{ route('requerimientos.index') }}" class="btn btn-secondary px-4"> Volver</a>
        </div>
    </form>
</div>
@endsection
