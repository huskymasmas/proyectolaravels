@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Detalle de Nómina de Empleados</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Código</th>
                    <th>Nombre Completo</th>
                    <th>Sueldo Base (Q)</th>
                    <th>Viáticos (Q)</th>
                    <th>Estado Trabajo</th>
                    <th>Horas Extras</th>
                    <th>Costo Horas Extras (Q)</th>
                    <th>Días</th>
                    <th>Total a Pagar (Q)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_general = 0;
                @endphp

                @forelse ($detalles as $d)
                    @php
                        $nombreCompleto = trim($d->Nombres . ' ' . $d->Apellido . ' ' . $d->Apellido2);
                        $total_general += $d->total_a_pagar;
                    @endphp
                    <tr>
                        <td>{{ $d->Codigo_empleado }}</td>
                        <td>{{ $nombreCompleto }}</td>
                        <td class="text-end">{{ number_format($d->sueldo_base, 2) }}</td>
                        <td class="text-end">{{ number_format($d->viaticosnomina, 2) }}</td>
                        <td class="text-center">{{ $d->Estado_trabajo }}</td>
                        <td class="text-center">{{ $d->Horas_extras }}</td>
                        <td class="text-end">{{ number_format($d->Costo_horas_extras, 2) }}</td>
                        <td class="text-center">{{ $d->cantidad_dias }}</td>
                        <td class="text-end fw-bold">{{ number_format($d->total_a_pagar, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>

            @if (count($detalles) > 0)
            <tfoot class="table-secondary fw-bold">
                <tr>
                    <td colspan="8" class="text-end">TOTAL GENERAL:</td>
                    <td class="text-end text-success">{{ number_format($total_general, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
