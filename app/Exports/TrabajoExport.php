<?php

namespace App\Exports;

use App\Models\Trabajo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrabajoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Trabajo::with(['aldea', 'estadoTrabajo', 'unidad'])
            ->get()
            ->map(function ($trabajo) {
                return [
                    'ID' => $trabajo->id_trabajos,
                    'Aldea' => $trabajo->aldea->Nombre ?? '',
                    'Número Face' => $trabajo->Numero_face,
                    'Nombre Face' => $trabajo->Nombre_face,
                    'Estado Trabajo' => $trabajo->estadoTrabajo->Nombre ?? '',
                    'Cantidad' => $trabajo->Cantidad,
                    'Unidad' => $trabajo->unidad->Nombre ?? '',
                    'Costo Q/Unidad' => $trabajo->CostoQ,
                    'Subtotal' => $trabajo->Subtotal,
                    'Creado Por' => $trabajo->Creado_por,
                    'Fecha Creación' => $trabajo->Fecha_creacion,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Aldea',
            'Número Face',
            'Nombre Face',
            'Estado Trabajo',
            'Cantidad',
            'Unidad',
            'Costo Q/Unidad',
            'Subtotal',
            'Creado Por',
            'Fecha Creación'
        ];
    }
}
