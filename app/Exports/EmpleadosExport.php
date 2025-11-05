<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class EmpleadosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      $data = DB::table('tbl_Empleados as e')
            ->join('tbl_Nomina as n', 'e.id_Nomina', '=', 'n.id_Nomina')
            ->select(
                'e.Nombres',
                'e.Apellido',
                'e.Apellido2',
                'e.DPI',
                'n.salario_base',
                'n.total_pago',
                'n.descuentos',
                'n.Descuento_IGSS',
                'n.Descuento_ISR',
                'n.Descuento_IRTRA',
                'n.Salario_Neto'
            )
            ->get()
            ->toArray();

        // Agregamos cabeceras manualmente
        $headings = [
            (object)[
                'Nombres' => 'Nombres',
                'Apellido' => 'Apellido',
                'Apellido2' => 'Apellido2',
                'DPI' => 'DPI',
                'salario_base' => 'Salario Base',
                'total_pago' => 'Total Pago',
                'descuentos' => 'Descuentos',
                'Descuento_IGSS' => 'Descuento IGSS',
                'Descuento_ISR' => 'Descuento ISR',
                'Descuento_IRTRA' => 'Descuento IRTRA',
                'Salario_Neto' => 'Salario Neto',
            ],
        ];

        return collect(array_merge($headings, $data));
    
    }
   
}
