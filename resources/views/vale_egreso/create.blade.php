@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nuevo Vale de Egreso</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('vale_egreso.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 📌 Vale de despacho -->
        <h4>Datos del Vale</h4>
        <div class="mb-3">
            <label>No Vale</label>
            <input type="number" name="No_vale" class="form-control" value="{{ old('No_vale') }}" required>
        </div>

        <!-- 📌 Despacho -->
        <h4>Despacho de concreto</h4>
        <div class="mb-3">
            <label>Código Planta</label>
            <input type="text" name="Codigo_planta" class="form-control" value="{{ old('Codigo_planta') }}" required>
        </div>

        <div class="mb-3">
            <label>Proyecto</label>
            <select name="id_Proyecto" class="form-control" required>
                <option value="">Seleccione un proyecto</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_Proyecto }}" {{ old('id_Proyecto') == $proyecto->id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Empresa</label>
            <select name="id_Empresa" class="form-control" required>
                <option value="">Seleccione una empresa</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id_Empresa }}" {{ old('id_Empresa') == $empresa->id_Empresa ? 'selected' : '' }}>
                        {{ $empresa->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Volumen (m³)</label>
            <input type="number" step="0.01" name="Volumen_carga_M3" class="form-control" value="{{ old('Volumen_carga_M3') }}" required>
        </div>

        <div class="mb-3">
            <label>Tipo de Concreto</label>
            <input type="text" name="Tipo_Concreto" class="form-control" value="{{ old('Tipo_Concreto') }}" required>
        </div>

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" name="Fecha" class="form-control" value="{{ old('Fecha') }}">
        </div>

        <div class="mb-3">
            <label>Hora salida planta</label>
            <input type="time" name="Hora_salida_plata" class="form-control" value="{{ old('Hora_salida_plata') }}">
        </div>

        <div class="mb-3">
            <label>Inicio de carga</label>
            <input type="time" name="Inicio_Carga" class="form-control" value="{{ old('Inicio_Carga') }}">
        </div>

        <div class="mb-3">
            <label>Finaliza carga</label>
            <input type="time" name="Finaliza_carga" class="form-control" value="{{ old('Finaliza_carga') }}">
        </div>

        <div class="mb-3">
            <label>Hora llega a proyecto</label>
            <input type="time" name="Hora_llega_Proyecto" class="form-control" value="{{ old('Hora_llega_Proyecto') }}">
        </div>

        <div class="mb-3">
            <label>Placa vehículo</label>
            <input type="text" name="Placa_numero" class="form-control" value="{{ old('Placa_numero') }}">
        </div>

        <div class="mb-3">
            <label>Tipo de elemento</label>
            <input type="text" name="Tipo_elemento" class="form-control" value="{{ old('Tipo_elemento') }}">
        </div>

        <!-- 📌 Dosificación -->
        <h4>Dosificación</h4>
        <div class="mb-3">
            <label>Kg cemento granel</label>
            <input type="number" step="0.01" name="kg_cemento_granel" class="form-control" value="{{ old('kg_cemento_granel') }}">
        </div>

        <div class="mb-3">
            <label>Sacos cemento</label>
            <input type="number" step="0.01" name="Sacos_Cemento" class="form-control" value="{{ old('Sacos_Cemento') }}">
        </div>

        <div class="mb-3">
            <label>Kg piedrín</label>
            <input type="number" step="0.01" name="kg_piedirn" class="form-control" value="{{ old('kg_piedirn') }}">
        </div>

        <div class="mb-3">
            <label>Kg arena</label>
            <input type="number" step="0.01" name="Kg_arena" class="form-control" value="{{ old('Kg_arena') }}">
        </div>

        <div class="mb-3">
            <label>Litros agua</label>
            <input type="number" step="0.01" name="lts_agua" class="form-control" value="{{ old('lts_agua') }}">
        </div>

        <!-- 📌 Aditivos -->
        <h4>Aditivos</h4>
        @for($i = 1; $i <= 4; $i++)
        <div class="mb-3">
            <label>Nombre aditivo {{ $i }}</label>
            <input type="text" name="Nombre{{ $i }}" class="form-control" value="{{ old('Nombre'.$i) }}">
        </div>

        <div class="mb-3">
            <label>Cantidad aditivo {{ $i }}</label>
            <input type="number" step="0.01" name="Cantidad{{ $i }}" class="form-control" value="{{ old('Cantidad'.$i) }}">
        </div>

        <div class="mb-3">
            <label>Firma aditivo {{ $i }}</label>
            <input type="file" name="Firma{{ $i }}_ruta_imagen" class="form-control">
        </div>
        @endfor

        <div class="mb-3">
            <label>Nombre encargado paleta</label>
            <input type="text" name="Nombre_encargado_palata" class="form-control" value="{{ old('Nombre_encargado_palata') }}">
        </div>

        <div class="mb-3">
            <label>Nombre conductor</label>
            <input type="text" name="Nombre_coductor" class="form-control" value="{{ old('Nombre_coductor') }}">
        </div>

        <div class="mb-3">
            <label>Nombre recibe conforme</label>
            <input type="text" name="Nombre_Resibi_conforme" class="form-control" value="{{ old('Nombre_Resibi_conforme') }}">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Vale</button>
    </form>
</div>
@endsection
