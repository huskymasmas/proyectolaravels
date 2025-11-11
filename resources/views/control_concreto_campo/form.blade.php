@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ isset($control) ? 'Editar Registro' : 'Nuevo Registro' }}</h2>

    <form action="{{ isset($control) ? route('control_concreto_campo.update', $control->id_control_concreto_campo) : route('control_concreto_campo.store') }}" method="POST">
        @csrf
        @if(isset($control))
            @method('PUT')
        @endif

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Datos Generales</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <label>Proyecto</label>
                        <select name="id_Proyecto" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($proyectos as $p)
                                <option value="{{ $p->id_Proyecto }}" {{ (isset($control) && $control->id_Proyecto==$p->id_Proyecto) ? 'selected' : '' }}>
                                    {{ $p->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ $control->fecha ?? '' }}" required>
                    </div>
                    <div class="col">
                        <label>Codigo Envío Camión</label>
                        <input type="text" name="codigo_envio_camion" class="form-control" value="{{ $control->codigo_envio_camion ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Hora Llegada</label>
                        <input type="time" name="hora_llegada" class="form-control" value="{{ $control->hora_llegada ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Hora Inicio Vaciado</label>
                        <input type="time" name="hora_inicio_vaciado" class="form-control" value="{{ $control->hora_inicio_vaciado ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Hora Fin Vaciado</label>
                        <input type="time" name="hora_fin_vaciado" class="form-control" value="{{ $control->hora_fin_vaciado ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Volumen (m³)</label>
                        <input type="number" step="0.01" name="volumen_m3" class="form-control" value="{{ $control->volumen_m3 ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Asentamiento</label>
                        <input type="number" step="0.01" name="asentamiento" class="form-control" value="{{ $control->asentamiento ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Temperatura</label>
                        <input type="number" step="0.01" name="temperatura" class="form-control" value="{{ $control->temperatura ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Aire</label>
                        <input type="number" step="0.01" name="aire" class="form-control" value="{{ $control->aire ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Codigo Muestra</label>
                        <input type="text" name="codigo_muestra" class="form-control" value="{{ $control->codigo_muestra ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Cantidad Cilindros</label>
                        <input type="number" name="cantidad_cilindros" class="form-control" value="{{ $control->cantidad_cilindros ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Enviados a</label>
                        <input type="text" name="enviados_a" class="form-control" value="{{ $control->enviados_a ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Fecha Envío</label>
                        <input type="date" name="fecha_envio" class="form-control" value="{{ $control->fecha_envio ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Resultado PSI 7d</label>
                        <input type="number" name="resultado_psi_7d" class="form-control" value="{{ $control->resultado_psi_7d ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Resultado PSI 14d</label>
                        <input type="number" name="resultado_psi_14d" class="form-control" value="{{ $control->resultado_psi_14d ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Resultado PSI 28d</label>
                        <input type="number" name="resultado_psi_28d" class="form-control" value="{{ $control->resultado_psi_28d ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Responsable</label>
                        <input type="text" name="responsable" class="form-control" value="{{ $control->responsable ?? '' }}">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($control) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('control_concreto_campo.index') }}" class="btn btn-danger">Cancelar</a>
    </form>
</div>
@endsection
