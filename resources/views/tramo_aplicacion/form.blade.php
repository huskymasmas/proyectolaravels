@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">{{ isset($tramo) ? 'Editar Tramo' : 'Registrar Tramo' }}</h2>

    <form method="POST" action="{{ isset($tramo) ? route('tramo_aplicacion.update', $tramo->id_tramo) : route('tramo_aplicacion.store') }}">
        @csrf
        @if(isset($tramo))
            @method('PUT')
        @endif

        {{-- DATOS GENERALES --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Datos Generales</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_aldea">Aldea</label>
                        <select name="id_aldea" id="id_aldea" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($aldeas as $a)
                                <option value="{{ $a->id_aldea }}" {{ (isset($tramo) && $tramo->id_aldea == $a->id_aldea) ? 'selected' : '' }}>
                                    {{ $a->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $tramo->fecha ?? old('fecha') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="aplicador">Aplicador</label>
                        <input type="text" name="aplicador" id="aplicador" class="form-control" value="{{ $tramo->aplicador ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label for="supervisor">Supervisor</label>
                        <input type="text" name="supervisor" id="supervisor" class="form-control" value="{{ $tramo->supervisor ?? '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control">{{ $tramo->observaciones ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- RODADURAS --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between">
                <span>Rodaduras</span>
                <button type="button" class="btn btn-sm btn-light" id="addRodadura">+ Agregar Rodadura</button>
            </div>
            <div class="card-body" id="rodadurasContainer">
                @if(isset($tramo) && $tramo->rodaduras)
                    @foreach($tramo->rodaduras as $i => $rod)
                        <div class="border p-3 mb-3 rounded rodadura-item">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Rodadura</strong>
                                <button type="button" class="btn btn-danger btn-sm remove">X</button>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Eje</label>
                                    <select name="rodaduras[{{ $i }}][id_Ejes]" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($ejes as $eje)
                                            <option value="{{ $eje->id_Ejes }}" {{ $rod->id_Ejes == $eje->id_Ejes ? 'selected' : '' }}>
                                                {{ $eje->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Estación Inicial</label>
                                    <input type="text" name="rodaduras[{{ $i }}][estacion_inicial]" value="{{ $rod->estacion_inicial }}" class="form-control">
                                </div>
                                <div class="col">
                                    <label>Estación Final</label>
                                    <input type="text" name="rodaduras[{{ $i }}][estacion_final]" value="{{ $rod->estacion_final }}" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label>Ancho (m)</label>
                                    <input type="number" step="0.01" name="rodaduras[{{ $i }}][ancho]" value="{{ $rod->ancho }}" class="form-control">
                                </div>
                                <div class="col">
                                    <label>Rendimiento (m²)</label>
                                    <input type="number" step="0.01" name="rodaduras[{{ $i }}][rendimiento_m2]" value="{{ $rod->rendimiento_m2 }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- CUNETAS --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between">
                <span>Cunetas</span>
                <button type="button" class="btn btn-sm btn-light" id="addCuneta">+ Agregar Cuneta</button>
            </div>
            <div class="card-body" id="cunetasContainer">
                @if(isset($tramo) && $tramo->cunetas)
                    @foreach($tramo->cunetas as $i => $cun)
                        <div class="border p-3 mb-3 rounded cuneta-item">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Cuneta</strong>
                                <button type="button" class="btn btn-danger btn-sm remove">X</button>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Eje</label>
                                    <select name="cunetas[{{ $i }}][id_Ejes]" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($ejes as $eje)
                                            <option value="{{ $eje->id_Ejes }}" {{ $cun->id_Ejes == $eje->id_Ejes ? 'selected' : '' }}>
                                                {{ $eje->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Estación Inicial</label>
                                    <input type="text" name="cunetas[{{ $i }}][estacion_inicial]" value="{{ $cun->estacion_inicial }}" class="form-control">
                                </div>
                                <div class="col">
                                    <label>Estación Final</label>
                                    <input type="text" name="cunetas[{{ $i }}][estacion_final]" value="{{ $cun->estacion_final }}" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label>Ancho (m)</label>
                                    <input type="number" step="0.01" name="cunetas[{{ $i }}][ancho]" value="{{ $cun->ancho }}" class="form-control">
                                </div>
                                <div class="col">
                                    <label>Rendimiento (m²)</label>
                                    <input type="number" step="0.01" name="cunetas[{{ $i }}][rendimiento_m2]" value="{{ $cun->rendimiento_m2 }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- BOTONES --}}
        <div class="text-end">
            <button type="submit" class="btn btn-success">{{ isset($tramo) ? 'Actualizar' : 'Guardar' }}</button>
            <a href="{{ route('tramo_aplicacion.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

{{-- SCRIPT para agregar dinámicamente --}}
<script>
const ejes = @json($ejes);

function ejeOptions() {
    return ejes.map(e => `<option value="${e.id_Ejes}">${e.Nombre ?? 'Eje ' + e.id_Ejes}</option>`).join('');
}

// Rodaduras
const rodadurasContainer = document.getElementById('rodadurasContainer');
document.getElementById('addRodadura').addEventListener('click', () => {
    const index = rodadurasContainer.children.length;
    const div = document.createElement('div');
    div.classList.add('border','p-3','mb-3','rounded','rodadura-item');
    div.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <strong>Rodadura</strong>
            <button type="button" class="btn btn-danger btn-sm remove">X</button>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label>Eje</label>
                <select name="rodaduras[${index}][id_Ejes]" class="form-control" required>
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
                <label>Ancho (m)</label>
                <input type="number" step="0.01" name="rodaduras[${index}][ancho]" class="form-control">
            </div>
            <div class="col">
                <label>Rendimiento (m²)</label>
                <input type="number" step="0.01" name="rodaduras[${index}][rendimiento_m2]" class="form-control">
            </div>
        </div>
    `;
    div.querySelector('.remove').addEventListener('click', () => div.remove());
    rodadurasContainer.appendChild(div);
});

// Cunetas
const cunetasContainer = document.getElementById('cunetasContainer');
document.getElementById('addCuneta').addEventListener('click', () => {
    const index = cunetasContainer.children.length;
    const div = document.createElement('div');
    div.classList.add('border','p-3','mb-3','rounded','cuneta-item');
    div.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <strong>Cuneta</strong>
            <button type="button" class="btn btn-danger btn-sm remove">X</button>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label>Eje</label>
                <select name="cunetas[${index}][id_Ejes]" class="form-control" required>
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
                <label>Ancho (m)</label>
                <input type="number" step="0.01" name="cunetas[${index}][ancho]" class="form-control">
            </div>
            <div class="col">
                <label>Rendimiento (m²)</label>
                <input type="number" step="0.01" name="cunetas[${index}][rendimiento_m2]" class="form-control">
            </div>
        </div>
    `;
    div.querySelector('.remove').addEventListener('click', () => div.remove());
    cunetasContainer.appendChild(div);
});
</script>
@endsection
