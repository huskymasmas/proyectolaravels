@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ isset($tramo) ? 'Editar Tramo de Aplicación' : 'Registrar Tramo de Aplicación' }}</h2>

    <form action="{{ isset($tramo) ? route('tramo_aplicacion.update', $tramo->id_tramo) : route('tramo_aplicacion.store') }}" method="POST">
        @csrf
        @if(isset($tramo))
            @method('PUT')
        @endif

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
                                <option value="{{ $p->id_Proyecto }}" {{ (isset($tramo) && $tramo->id_Proyecto==$p->id_Proyecto) ? 'selected' : '' }}>
                                    {{ $p->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ isset($tramo) ? $tramo->fecha : '' }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Aplicador</label>
                        <input type="text" name="aplicador" class="form-control" value="{{ $tramo->aplicador ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Cubeta / Bomba</label>
                        <input type="text" name="cubeta_bomba" class="form-control" value="{{ $tramo->cubeta_bomba ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Supervisor</label>
                        <input type="text" name="supervisor" class="form-control" value="{{ $tramo->supervisor ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Ancho Aditivo</label>
                        <input type="number" step="0.001" name="Aditivo_Ancho" class="form-control" value="{{ $tramo->Aditivo_Ancho ?? '' }}">
                    </div>
                    <div class="col">
                        <label>Rendimiento (m²)</label>
                        <input type="number" step="0.001" name="Rendimiento_M2" class="form-control" value="{{ $tramo->Rendimiento_M2 ?? '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control">{{ $tramo->observaciones ?? '' }}</textarea>
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
                @if(isset($tramo))
                    @foreach($tramo->rodaduras as $i => $rod)
                        <div class="border p-3 mb-3 rounded rodadura-item">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Rodadura</strong>
                                <button type="button" class="btn btn-danger btn-sm remove">X</button>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Eje</label>
                                    <select name="rodaduras[{{ $i }}][id_Ejes]" class="form-control">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($ejes as $e)
                                            <option value="{{ $e->id_Ejes }}" {{ $e->id_Ejes==$rod->id_Ejes ? 'selected' : '' }}>
                                                {{ $e->Nombre ?? 'Eje '.$e->id_Ejes }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Estación Inicial</label>
                                    <input type="text" name="rodaduras[{{ $i }}][estacion_inicial]" class="form-control" value="{{ $rod->estacion_inicial }}">
                                </div>
                                <div class="col">
                                    <label>Estación Final</label>
                                    <input type="text" name="rodaduras[{{ $i }}][estacion_final]" class="form-control" value="{{ $rod->estacion_final }}">
                                </div>
                            </div>
                            <input type="hidden" name="rodaduras[{{ $i }}][id_rodadura]" value="{{ $rod->id_rodadura }}">
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
                @if(isset($tramo))
                    @foreach($tramo->cunetas as $i => $cun)
                        <div class="border p-3 mb-3 rounded cuneta-item">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Cuneta</strong>
                                <button type="button" class="btn btn-danger btn-sm remove">X</button>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Eje</label>
                                    <select name="cunetas[{{ $i }}][id_Ejes]" class="form-control">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($ejes as $e)
                                            <option value="{{ $e->id_Ejes }}" {{ $e->id_Ejes==$cun->id_Ejes ? 'selected' : '' }}>
                                                {{ $e->Nombre ?? 'Eje '.$e->id_Ejes }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Estación Inicial</label>
                                    <input type="text" name="cunetas[{{ $i }}][estacion_inicial]" class="form-control" value="{{ $cun->estacion_inicial }}">
                                </div>
                                <div class="col">
                                    <label>Estación Final</label>
                                    <input type="text" name="cunetas[{{ $i }}][estacion_final]" class="form-control" value="{{ $cun->estacion_final }}">
                                </div>
                            </div>
                            <input type="hidden" name="cunetas[{{ $i }}][id_cuneta]" value="{{ $cun->id_cuneta }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($tramo) ? 'Actualizar Tramo' : 'Guardar Tramo' }}</button>
        <a href="{{ route('tramo_aplicacion.index') }}" class="btn btn-danger">Cancelar</a>
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
        const index = rodadurasContainer.children.length;
        const div = document.createElement('div');
        div.classList.add('border','p-3','mb-3','rounded');
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
        `;
        div.querySelector('.remove').addEventListener('click', ()=>div.remove());
        rodadurasContainer.appendChild(div);
    }

    // --- Cunetas ---
    const cunetasContainer = document.getElementById('cunetasContainer');
    document.getElementById('addCuneta').addEventListener('click', addCuneta);
    function addCuneta() {
        const index = cunetasContainer.children.length;
        const div = document.createElement('div');
        div.classList.add('border','p-3','mb-3','rounded');
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
        `;
        div.querySelector('.remove').addEventListener('click', ()=>div.remove());
        cunetasContainer.appendChild(div);
    }
</script>
@endsection
