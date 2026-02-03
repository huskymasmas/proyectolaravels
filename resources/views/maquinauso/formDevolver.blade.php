@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-3">Devolver Maquinaria</h2>

    {{-- Mensajes --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow p-4">

        <form method="POST" action="{{ route('maquinauso.procesarDevolucion', $item->id_maquina_uso) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Máquina:</label>
                <input type="text" class="form-control" value="{{ $item->maquina }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Proyecto:</label>
                <input type="text" class="form-control" value="{{ $item->proyecto }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Cantidad en Uso:</label>
                <input type="text" class="form-control" value="{{ $item->cantidad }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Cantidad a Devolver:</label>
                <input type="number" name="cantidad_devolver" class="form-control" min="1" max="{{ $item->cantidad }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Devolver a Bodega:</label>
                <select name="bodega_destino" class="form-control">
                    <option value="">-- Bodega General (DEFAULT) --</option>

                    @foreach($bodegas as $bod)
                        <option value="{{ $bod->id_Proyecto }}">
                            {{ $bod->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success">Procesar Devolución</button>
            <a href="{{ route('maquinauso.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>

    </div>
</div>
@endsection
