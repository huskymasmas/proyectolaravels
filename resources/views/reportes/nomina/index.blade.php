@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Reporte de Nómina</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Días Trabajados</th>
                    <th>Horas Extras</th>
                    <th>Adelanto Sueldo (Q)</th>
                    <th>Adelanto Viáticos (Q)</th>
                    <th>Total Pagos (Q)</th>
                    <th>Total Devengado (Q)</th>
                    <th>Saldo Pendiente (Q)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_dias = 0;
                    $total_horas = 0;
                    $total_adelantos = 0;
                    $total_viaticos = 0;
                    $total_pagos = 0;
                    $total_devengado = 0;
                    $total_saldo = 0;
                @endphp

                @forelse ($reportes as $r)
                    @php
                        $total_dias += $r->Dias_trabajados;
                        $total_horas += $r->Total_horas_extras;
                        $total_adelantos += $r->Adelanto_total;
                        $total_viaticos += $r->Adelanto_viaticos;
                        $total_pagos += $r->Total_Pagos;
                        $total_devengado += $r->total_devengado;
                        $total_saldo += $r->saldo_pendiente;
                    @endphp
                    <tr>
                        <td>{{ $r->Codigo }}</td>
                        <td>{{ $r->Nombre }}</td>
                        <td>{{ $r->rol }}</td>
                        <td class="text-center">{{ $r->Dias_trabajados }}</td>
                        <td class="text-center">{{ $r->Total_horas_extras }}</td>
                        <td class="text-end">{{ number_format($r->Adelanto_total, 2) }}</td>
                        <td class="text-end">{{ number_format($r->Adelanto_viaticos, 2) }}</td>
                        <td class="text-end">{{ number_format($r->Total_Pagos, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($r->total_devengado, 2) }}</td>
                        <td class="text-end fw-bold text-success">{{ number_format($r->saldo_pendiente, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No hay datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>

            @if (count($reportes) > 0)
            <tfoot class="table-secondary fw-bold">
                <tr>
                    <td colspan="3" class="text-end">TOTALES:</td>
                    <td class="text-center">{{ $total_dias }}</td>
                    <td class="text-center">{{ $total_horas }}</td>
                    <td class="text-end">{{ number_format($total_adelantos, 2) }}</td>
                    <td class="text-end">{{ number_format($total_viaticos, 2) }}</td>
                    <td class="text-end">{{ number_format($total_pagos, 2) }}</td>
                    <td class="text-end">{{ number_format($total_devengado, 2) }}</td>
                    <td class="text-end text-success">{{ number_format($total_saldo, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
