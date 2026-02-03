@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-3">Maquinaria en Uso</h2>

    {{-- MENSAJES --}}
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow p-3">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Máquina</th>
                    <th>Cantidad en uso</th>
                    <th>Proyecto</th>
                    <th>Fecha en Uso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($data as $row)
                <tr>
                    <td>{{ $row->id_maquina_uso }}</td>
                    <td>{{ $row->maquina }}</td>
                    <td>{{ $row->cantidad }}</td>
                    <td>{{ $row->proyecto }}</td>
                    <td>{{ $row->Fecha }}</td>
                    <td>
                        @if($row->Estado == 1)
                            <span class="badge bg-success">EN USO</span>
                        @else
                            <span class="badge bg-secondary">DESUSO</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('maquinauso.formDevolver', $row->id_maquina_uso) }}"
                           class="btn btn-warning btn-sm">
                           Devolver
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay maquinaria en uso actualmente.</td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection
