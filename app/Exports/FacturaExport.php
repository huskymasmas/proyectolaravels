<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FacturaExport implements FromCollection, WithHeadings, WithEvents
{
    protected $numFactura;
    protected $total_gen;
    protected $numRows;

    public function __construct($numFactura)
    {
        $this->numFactura = $numFactura;
    }

    public function collection()
    {
        // 🔹 MATERIAL (tbl_vale_ingreso)
        $materiales = DB::table('tbl_vale_ingreso')
            ->select('Num_factura', 'nit', 'precio_total', 'cantidad')
            ->where('Num_factura', $this->numFactura)
            ->get()
            ->map(function ($item) {
                return [
                    'tipo' => 'Material',
                    'Num_factura' => $item->Num_factura,
                    'nit' => $item->nit,
                    'precio' => $item->precio_total,
                    'cantidad' => $item->cantidad,
                ];
            });

        // 🔹 MAQUINARIA (tbl_vale_ingreso_equipo_maquinaria_vehiculo)
        $maquinaria = DB::table('tbl_vale_ingreso_equipo_maquinaria_vehiculo')
            ->select('Num_factura', 'nit', 'costo', 'cantidad')
            ->where('Num_factura', $this->numFactura)
            ->get()
            ->map(function ($item) {
                return [
                    'tipo' => 'Maquinaria',
                    'Num_factura' => $item->Num_factura,
                    'nit' => $item->nit,
                    'precio' => $item->costo,
                    'cantidad' => $item->cantidad,
                ];
            });

        // 🔹 Unimos ambas colecciones
        $data = $materiales->merge($maquinaria);

        // 🔹 Total general
        $this->total_gen = $data->sum('precio');

        // 🔹 Cantidad de filas para ubicar el TOTAL
        $this->numRows = $data->count();

        return $data;
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Número de Factura',
            'NIT',
            'Precio',
            'Cantidad',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $row = $this->numRows + 2; // 1 encabezado + filas + 1 espacio

                // 🔸 Escribir total general
                $event->sheet->setCellValue('A' . $row, 'TOTAL GENERAL');
                $event->sheet->setCellValue('B' . $row, $this->total_gen);

                // 🔸 Estilo negrita
                $event->sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
