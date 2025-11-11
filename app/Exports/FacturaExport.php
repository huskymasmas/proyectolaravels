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

    // 🔹 Recibimos el número de factura al construir el objeto
    public function __construct($numFactura)
    {
        $this->numFactura = $numFactura;
    }

    public function collection()
    {
        // 🔸 Traer los datos de la factura
        $data = DB::table('tbl_vale_ingreso')
            ->select('Num_factura', 'nit','precio_total', 'Cantidad')
            ->where('Num_factura', $this->numFactura)
            ->get();

        // 🔹 Guardamos total y número de filas
        $this->total_gen = $data->sum('precio_total');
        $this->numRows = $data->count();

        return $data;
    }

    public function headings(): array
    {
        return [
            'Número de Factura',
            'NIT',
            'Precio Total',
            'Cantidad',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 🔹 Fila donde se colocará el total (1 por encabezado + filas + 1 de espacio)
                $row = $this->numRows + 2;

                // 🔸 Escribe el total
                $event->sheet->setCellValue('A' . $row, 'TOTAL');
                $event->sheet->setCellValue('B' . $row, $this->total_gen);

                // 🔸 Aplica estilo negrita
                $event->sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}