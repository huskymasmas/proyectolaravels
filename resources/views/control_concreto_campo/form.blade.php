@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ $registro->exists ? 'Editar Registro' : 'Nuevo Registro' }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $registro->exists ? route('control_concreto_campo.update', $registro->id_control_concreto_campo) : route('control_concreto_campo.store') }}">
        @csrf
        @if ($registro->exists)
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Aldea</label>
                <select name="id_Aldea" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($aldeas as $a)
                        <option value="{{ $a->id_aldea }}" {{ old('id_Aldea', $registro->id_Aldea) == $a->id_aldea ? 'selected' : '' }}>
                            {{ $a->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $registro->fecha) }}" required>
            </div>
            <div class="col-md-3">
                <label>Código Envío Camión</label>
                <input type="text" name="codigo_envio_camion" class="form-control" value="{{ old('codigo_envio_camion', $registro->codigo_envio_camion) }}" required>
            </div>
            <div class="col-md-3">
                <label>Responsable</label>
                <input type="text" name="responsable" class="form-control" value="{{ old('responsable', $registro->responsable) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3"><label>Hora Llegada</label><input type="time" name="hora_llegada" class="form-control" value="{{ old('hora_llegada', $registro->hora_llegada) }}"></div>
            <div class="col-md-3"><label>Inicio Vaciado</label><input type="time" name="hora_inicio_vaciado" class="form-control" value="{{ old('hora_inicio_vaciado', $registro->hora_inicio_vaciado) }}"></div>
            <div class="col-md-3"><label>Fin Vaciado</label><input type="time" name="hora_fin_vaciado" class="form-control" value="{{ old('hora_fin_vaciado', $registro->hora_fin_vaciado) }}"></div>
            <div class="col-md-3"><label>Volumen (m³)</label><input type="number" step="0.01" name="volumen_m3" class="form-control" value="{{ old('volumen_m3', $registro->volumen_m3) }}"></div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3"><label>Asentamiento</label><input type="number" step="0.01" name="asentamiento" class="form-control" value="{{ old('asentamiento', $registro->asentamiento) }}"></div>
            <div class="col-md-3"><label>Temperatura</label><input type="number" step="0.01" name="temperatura" class="form-control" value="{{ old('temperatura', $registro->temperatura) }}"></div>
            <div class="col-md-3"><label>Aire</label><input type="number" step="0.01" name="aire" class="form-control" value="{{ old('aire', $registro->aire) }}"></div>
            <div class="col-md-3"><label>Código Muestra</label><input type="text" name="codigo_muestra" class="form-control" value="{{ old('codigo_muestra', $registro->codigo_muestra) }}"></div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3"><label>Cantidad Cilindros</label><input type="number" name="cantidad_cilindros" class="form-control" value="{{ old('cantidad_cilindros', $registro->cantidad_cilindros) }}"></div>
            <div class="col-md-3"><label>Enviados A</label><input type="text" name="enviados_a" class="form-control" value="{{ old('enviados_a', $registro->enviados_a) }}"></div>
            <div class="col-md-3"><label>Fecha Envío</label><input type="date" name="fecha_envio" class="form-control" value="{{ old('fecha_envio', $registro->fecha_envio) }}"></div>
        </div>

        <h5>Resultados (PSI)</h5>
        <div class="row mb-3">
            <div class="col-md-4"><label>7 días</label><input type="number" name="resultado_psi_7d" class="form-control" value="{{ old('resultado_psi_7d', $registro->resultado_psi_7d) }}"></div>
            <div class="col-md-4"><label>14 días</label><input type="number" name="resultado_psi_14d" class="form-control" value="{{ old('resultado_psi_14d', $registro->resultado_psi_14d) }}"></div>
            <div class="col-md-4"><label>28 días</label><input type="number" name="resultado_psi_28d" class="form-control" value="{{ old('resultado_psi_28d', $registro->resultado_psi_28d) }}"></div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('control_concreto_campo.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
