@extends('layouts.app')

@section('content')
<div class="container p-4">

    <h3>Registrar Avance de Trabajo</h3>
    <a href="{{ route('avances.index') }}" class="btn btn-secondary mb-3">← Regresar</a>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('avances.store') }}">
        @csrf

        <!-- Aldea -->
        <div class="mb-3">
            <label class="form-label">Aldea</label>
            <select name="id_aldea" id="id_aldea" class="form-control" required>
                <option value="">Seleccione una aldea</option>
                @foreach($aldeas as $aldea)
                    <option value="{{ $aldea->id_aldea }}">{{ $aldea->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Trabajo -->
        <div class="mb-3">
            <label class="form-label">Trabajo</label>
            <select name="id_trabajos" id="id_trabajos" class="form-control" required>
                <option value="">Seleccione una aldea primero</option>
            </select>
        </div>

        <!-- Cantidad -->
        <div class="mb-3">
            <label class="form-label">Cantidad Avance</label>
            <input type="number" name="Cantidad" step="0.01" class="form-control" required>
        </div>

        <button class="btn btn-success">Guardar Avance</button>

    </form>
</div>
<script>
document.getElementById('id_aldea').addEventListener('change', function () {
    let aldea = this.value;
    let trabajosSelect = document.getElementById('id_trabajos');

    trabajosSelect.innerHTML = '<option value="">Cargando...</option>';

    if (aldea === "") {
        trabajosSelect.innerHTML = '<option value="">Seleccione una aldea primero</option>';
        return;
    }

    fetch(`/avances/trabajos/${aldea}`)
        .then(response => response.json())
        .then(data => {

            trabajosSelect.innerHTML = '<option value="">Seleccione un trabajo</option>';

            if (data.length === 0) {
                trabajosSelect.innerHTML = '<option value="">No hay trabajos para esta aldea</option>';
                return;
            }

            data.forEach(t => {
                trabajosSelect.innerHTML += `
                    <option value="${t.id_trabajos}" 
                            data-cantidad="${t.cantidad}" 
                            data-costo="${t.CostoQ}">
                        ${t.nombre_face} — Cantidad: ${t.cantidad} — Costo: Q${t.CostoQ}
                    </option>
                `;
            });
        })
        .catch(err => {
            trabajosSelect.innerHTML = '<option>Error al cargar trabajos</option>';
            console.error(err);
        });
});
</script>


