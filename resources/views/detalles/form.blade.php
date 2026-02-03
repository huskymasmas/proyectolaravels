@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Nuevo Detalle de Obra</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('detalles.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_Proyecto" class="form-label">Proyecto</label>
                        <select name="id_Proyecto" id="id_Proyecto" class="form-select" required>
                            <option value="">Seleccione un proyecto</option>
                            @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id_Proyecto }}">{{ $proyecto->Nombre ?? 'Proyecto sin nombre' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="Tipo_Obra" class="form-label">Tipo de Obra</label>
                        <input type="text" name="Tipo_Obra" id="Tipo_Obra" class="form-control" placeholder="Ej: Rodadura, cuneta..." maxlength="100">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="Valor" class="form-label">Valor</label>
                        <input type="number" step="0.01" name="Valor" id="Valor" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="id_Unidades" class="form-label">Unidad</label>
                        <select name="id_Unidades" id="id_Unidades" class="form-select" required>
                            <option value="">Seleccione unidad</option>
                            @foreach ($unidades as $unidad)
                                <option value="{{ $unidad->id_Unidades }}">{{ $unidad->Nombre ?? 'Unidad' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="Calculo" class="form-label">Cálculo (%)</label>
                        <input type="number" step="0.01" name="Calculo" id="Calculo" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Detalle" class="form-label">Detalle</label>
                    <input type="text" name="Detalle" id="Detalle" class="form-control" maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="Descripcion" class="form-label">Descripción</label>
                    <textarea name="Descripcion" id="Descripcion" rows="3" class="form-control" maxlength="255"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('detalles.index') }}" class="btn btn-secondary me-2">⬅️ Cancelar</a>
                    <button type="submit" class="btn btn-primary"> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
