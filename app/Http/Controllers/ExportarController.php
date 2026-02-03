<?php

namespace App\Http\Controllers;
use App\Exports\EmpleadosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportarController extends Controller
{
    public function exportar()
    {
        return Excel::download(new EmpleadosExport, 'empleados.xlsx');
    }
}
