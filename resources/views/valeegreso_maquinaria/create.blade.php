@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nuevo Vale de Egreso - Maquinaria</h2>

    <form action="{{ route('valeegreso_maquinaria.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre de la Maquinaria</label>
                <input type="text" name="Nombre" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Cantidad</label>
                <input type="number" name="cantidad" class="form-control" min="1" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Proyecto</label>
                <select name="id_Proyecto" class="form-control">
                    <option value="">-- SIN PROYECTO --</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_Proyecto }}">{{ $p->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Encargado</label>
                <input type="text" name="Nombre_encargado" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Recibe Conforme</label>
                <input type="text" name="Nombre_Recibe_conforme" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Conductor</label>
                <input type="text" name="Nombre_Conductor" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Bodeguero</label>
                <input type="text" name="Nombre_Bodeguero" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Fecha</label>
                <input type="date" name="Fecha" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Hora de salida</label>
                <input type="time" name="Hora_salida" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Hora de llegada</label>
                <input type="time" name="Hora_llegada" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Serie</label>
                <input type="text" name="serie" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control">
            </div>
        </div>

        <button class="btn btn-primary">Guardar Egreso</button>
        <a href="{{ route('valeegreso_maquinaria.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
