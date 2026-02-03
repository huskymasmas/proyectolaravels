<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockBajoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $productos;

    public function __construct($productos)
    {
        $this->productos = $productos;
    }

    public function build()
    {
        return $this->view('emails.stock_bajo')
                    ->subject('Alerta: Productos con Stock Bajo')
                    ->with(['productos' => $this->productos]);
    }
}
