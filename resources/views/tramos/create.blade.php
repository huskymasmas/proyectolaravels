@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Registrar Tramo</h2>

    <form action="{{ route('tramos.store') }}" method="POST">
        @csrf

        {{-- DATOS GENERALES --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Datos Generales del Tramo</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <label>Proyecto</label>
                        <select name="id_Proyecto" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach ($proyectos as $p)
                                <option value="{{ $p->id_Proyecto }}">{{ $p->Nombre ?? 'Proyecto ' . $p->id_Proyecto }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Tipo de Concreto</label>
                        <input type="text" name="tipo_concreto" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Cantidad de Concreto (m³)</label>
                        <input type="number" step="0.01" name="cantidad_concreto_m3" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Temperatura</label>
                        <input type="number" step="0.1" name="temperatura" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Supervisor</label>
                        <input type="text" name="supervisor" class="form-control">
                    </div>
                    <div class="col">
                        <label>Nombre Aditivo</label>
                        <input type="text" name="nombre_aditivo" class="form-control">
                    </div>
                    <div class="col">
                        <label>Cantidad (lts)</label>
                        <input type="number" step="0.1" name="cantidad_lts" class="form-control">
                    </div>
                    <div class="col">
                        <label>Tipo</label>
                        <input type="text" name="tipo" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control"></textarea>
                </div>
            </div>
        </div>

        {{-- RODADURAS --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between">
                <span>Rodaduras</span>
                <button type="button" class="btn btn-sm btn-light" id="addRodadura">+ Agregar Rodadura</button>
            </div>
            <div class="card-body" id="rodadurasContainer"></div>
        </div>

        {{-- CUNETAS --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between">
                <span>Cunetas</span>
                <button type="button" class="btn btn-sm btn-light" id="addCuneta">+ Agregar Cuneta</button>
            </div>
            <div class="card-body" id="cunetasContainer"></div>
        </div>

        <button type="submit" class="btn btn-success">Guardar Tramo</button>
        <a href="{{ route('tramos.index') }}" class="btn btn-danger">Cancelar</a>
    </form>
</div>

{{-- SCRIPTS --}}
<script>
    const ejes = @json($ejes);

    function ejeOptions() {
        return ejes.map(e => `<option value="${e.id_Ejes}">${e.Nombre ?? 'Eje ' + e.id_Ejes}</option>`).join('');
    }

    // --- Rodaduras ---
    const rodadurasContainer = document.getElementById('rodadurasContainer');
    document.getElementById('addRodadura').addEventListener('click', addRodadura);

    function addRodadura() {
        const index = rodadurasContainer.children.length; // índice para cada rodadura
        const div = document.createElement('div');
        div.classList.add('border', 'p-3', 'mb-3', 'rounded');

        div.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <strong>Rodadura</strong>
                <button type="button" class="btn btn-danger btn-sm remove">X</button>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <label>Eje</label>
                    <select name="rodaduras[${index}][id_Ejes]" class="form-control">
                        <option value="">-- Seleccione --</option>
                        ${ejeOptions()}
                    </select>
                </div>
                <div class="col">
                    <label>Estación Inicial</label>
                    <input type="text" name="rodaduras[${index}][estacion_inicial]" class="form-control">
                </div>
                <div class="col">
                    <label>Estación Final</label>
                    <input type="text" name="rodaduras[${index}][estacion_final]" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label>Ancho Prom (m)</label>
                    <input type="number" step="0.01" name="rodaduras[${index}][ancho_prom]" class="form-control">
                </div>
                <div class="col">
                    <label>Área (m²)</label>
                    <input type="number" step="0.01" name="rodaduras[${index}][m2]" class="form-control">
                </div>
                <div class="col">
                    <label>Rendimiento (m³)</label>
                    <input type="number" step="0.01" name="rodaduras[${index}][rendimiento_m3]" class="form-control">
                </div>
            </div>
        `;

        div.querySelector('.remove').addEventListener('click', () => div.remove());
        rodadurasContainer.appendChild(div);
    }

    // --- Cunetas ---
    const cunetasContainer = document.getElementById('cunetasContainer');
    document.getElementById('addCuneta').addEventListener('click', addCuneta);

    function addCuneta() {
        const index = cunetasContainer.children.length; // índice para cada cuneta
        const div = document.createElement('div');
        div.classList.add('border', 'p-3', 'mb-3', 'rounded');

        div.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <strong>Cuneta</strong>
                <button type="button" class="btn btn-danger btn-sm remove">X</button>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <label>Eje</label>
                    <select name="cunetas[${index}][id_Ejes]" class="form-control">
                        <option value="">-- Seleccione --</option>
                        ${ejeOptions()}
                    </select>
                </div>
                <div class="col">
                    <label>Estación Inicial</label>
                    <input type="text" name="cunetas[${index}][estacion_inicial]" class="form-control">
                </div>
                <div class="col">
                    <label>Estación Final</label>
                    <input type="text" name="cunetas[${index}][estacion_final]" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label>Ancho Prom (m)</label>
                    <input type="number" step="0.01" name="cunetas[${index}][ancho_prom]" class="form-control">
                </div>
                <div class="col">
                    <label>Área (m²)</label>
                    <input type="number" step="0.01" name="cunetas[${index}][m2]" class="form-control">
                </div>
                <div class="col">
                    <label>Rendimiento (m³)</label>
                    <input type="number" step="0.01" name="cunetas[${index}][rendimiento_m3]" class="form-control">
                </div>
            </div>
        `;

        div.querySelector('.remove').addEventListener('click', () => div.remove());
        cunetasContainer.appendChild(div);
    }
</script>
@endsection
