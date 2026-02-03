<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockBajoMail;
use App\Models\BodegaGeneral;

class CheckStockBajo extends Command
{
    protected $signature = 'check:stockbajo';
    protected $description = 'Envía un correo si hay productos con stock bajo';

    public function handle()
    {
        $productos = BodegaGeneral::where(function ($query) {
                $query->where(function ($q) {
                    $q->where('id_Unidades', 1)
                      ->where('Cantidad', '<', 9);
                })
                ->orWhere(function ($q) {
                    $q->where('id_Unidades', 2)
                      ->where('Cantidad', '<', 450);
                })
                ->orWhere(function ($q) {
                    $q->where('id_Unidades', 3)
                      ->where('Cantidad', '<', 0.28);
                });
            })
            ->where('Nombre', 'not like', '%maquina%')
            ->get();

        if ($productos->isEmpty()) {
            $this->info('No hay productos con stock bajo.');
            return;
        }

        Mail::to('rcuettolusky@gmail.com')->send(new StockBajoMail($productos));

        $this->info('Correo enviado con ' . $productos->count() . ' productos de stock bajo.');
    }
}
