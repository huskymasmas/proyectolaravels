<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomina;
use Illuminate\Support\Facades\Auth;

class NominaController extends Controller
{
    public function index()
    {
        $nominas = Nomina::all();
        return view('nomina.index', compact('nominas'));
    }

    public function create()
    {
        return view('nomina.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'salario_base' => 'required|numeric',
            'bono' => 'nullable|numeric',
            'bono_adicional' => 'nullable|numeric',
            'Estado' => 'required|in:0,1',
        ]);

        // === Cálculos ===
        $salario_bruto = $request->salario_base + ($request->bono ?? 0) + ($request->bono_adicional ?? 0);

        // IGSS = 4.83%
        $descuento_igss = $salario_bruto * 0.0483;

        // IRTRA = 1%
        $descuento_irtra = $salario_bruto * 0.01;

        // ISR según rango
        if ($salario_bruto <= 4166.67) {
            $descuento_isr = 0;
        } elseif ($salario_bruto <= 8333.33) {
            $descuento_isr = ($salario_bruto - 4166.67) * 0.05;
        } else {
            $descuento_isr = ($salario_bruto - 8333.33) * 0.07 + 208.33;
        }

        // Total descuentos
        $total_descuentos = $descuento_igss + $descuento_isr + $descuento_irtra;

        Nomina::create([
            'salario_base' => $request->salario_base,
            'bono' => $request->bono ?? 0,
            'bono_adicional' => $request->bono_adicional ?? 0,
            'Descuento_IGSS' => $descuento_igss,
            'Descuento_ISR' => $descuento_isr,
            'Descuento_IRTRA' => $descuento_irtra,
            'descuentos' => $total_descuentos,
            'Estado' => $request->Estado,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('nomina.index')->with('success', 'Nómina registrada correctamente.');
    }

    public function edit($id)
    {
        $nomina = Nomina::findOrFail($id);
        return view('nomina.form', compact('nomina'));
    }

    public function update(Request $request, $id)
    {
        $nomina = Nomina::findOrFail($id);

        $request->validate([
            'salario_base' => 'required|numeric',
            'bono' => 'nullable|numeric',
            'bono_adicional' => 'nullable|numeric',
            'Estado' => 'required|in:0,1',
        ]);

        // === Cálculos actualizados ===
        $salario_bruto = $request->salario_base + ($request->bono ?? 0) + ($request->bono_adicional ?? 0);

        $descuento_igss = $salario_bruto * 0.0483;
        $descuento_irtra = $salario_bruto * 0.01;

        if ($salario_bruto <= 4166.67) {
            $descuento_isr = 0;
        } elseif ($salario_bruto <= 8333.33) {
            $descuento_isr = ($salario_bruto - 4166.67) * 0.05;
        } else {
            $descuento_isr = ($salario_bruto - 8333.33) * 0.07 + 208.33;
        }

        $total_descuentos = $descuento_igss + $descuento_isr + $descuento_irtra;

        $nomina->update([
            'salario_base' => $request->salario_base,
            'bono' => $request->bono ?? 0,
            'bono_adicional' => $request->bono_adicional ?? 0,
            'Descuento_IGSS' => $descuento_igss,
            'Descuento_ISR' => $descuento_isr,
            'Descuento_IRTRA' => $descuento_irtra,
            'descuentos' => $total_descuentos,
            'Estado' => $request->Estado,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('nomina.index')->with('success', 'Nómina actualizada correctamente.');
    }

    public function destroy($id)
    {
        Nomina::findOrFail($id)->delete();
        return redirect()->route('nomina.index')->with('success', 'Nómina eliminada correctamente.');
    }
}
