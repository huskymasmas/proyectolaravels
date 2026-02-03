@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ isset($trabajo) ? 'Editar Trabajo' : 'Nuevo Trabajo' }}</h2>

    <form method="POST" action="{{ isset($trabajo) ? route('trabajo.update', $trabajo->id_trabajos) : route('trabajo.store') }}">
        @csrf
        @if(isset($trabajo))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Aldea</label>
                <select name="id_aldea" class="form-select" required>
                    <option value="">-- Seleccionar Aldea --</option>
                    @foreach($aldeas as $aldea)
                        <option value="{{ $aldea->id_aldea }}" 
                            {{ (isset($trabajo) && $trabajo->id_aldea == $aldea->id_aldea) ? 'selected' : '' }}>
                            {{ $aldea->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
    <label>Estado de Trabajo</label>
    <select name="id_Estado_trabajo" class="form-select" required>
        <option value="">-- Seleccionar Estado --</option>
        @foreach($estados as $estado)
            <option value="{{ $estado->id_Estado_trabajo }}"
                {{ isset($trabajo) && $trabajo->id_Estado_trabajo == $estado->id_Estado_trabajo ? 'selected' : '' }}>
                {{ $estado->Nombre }}
            </option>
        @endforeach
    </select>
</div>


            <div class="col-md-4 mb-3">
                <label>Unidad</label>
                <select name="id_Unidades" class="form-select" required>
                    <option value="">-- Seleccionar Unidad --</option>
                    @foreach($unidades as $unidad)
                        <option value="{{ $unidad->id_Unidades }}" 
                            {{ (isset($trabajo) && $trabajo->id_Unidades == $unidad->id_Unidades) ? 'selected' : '' }}>
                            {{ $unidad->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Número Fase</label>
                <input type="text" name="numero_face" value="{{ $trabajo->numero_face ?? '' }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Nombre Fase</label>
                <input type="text" name="nombre_face" value="{{ $trabajo->nombre_face ?? '' }}" class="form-control" required>
            </div>

            <div class="col-md-2 mb-3">
                <label>Cantidad</label>
                <input type="number" step="0.01" name="cantidad" value="{{ $trabajo->cantidad ?? '' }}" class="form-control" required>
            </div>

            <div class="col-md-2 mb-3">
                <label>Costo Q/Unidad</label>
                <input type="number" step="0.01" name="CostoQ" value="{{ $trabajo->CostoQ ?? '' }}" class="form-control" required>
            </div>
        </div>

         @if(isset($trabajo) && $trabajo->planos->count() > 0)
                <div class="col-md-12 mb-4">
                    <h5>Planos existentes:</h5>

                    @foreach ($trabajo->planos as $plano)
                        <div class="border p-2 mb-3">
                            <strong>{{ $plano->nombre }}</strong>
                            <embed src="data:application/pdf;base64,{{ base64_encode($plano->datos) }}"
                                type="application/pdf" width="100%" height="300px">
                        </embed>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <button type="submit" class="btn btn-primary">
            {{ isset($trabajo) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('trabajo.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
