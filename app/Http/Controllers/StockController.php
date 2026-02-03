<?php
namespace App\Http\Controllers;
use App\Mail\NotificacionStock;
use Illuminate\Support\Facades\Mail;

class StockController extends Controller
{
    public function enviarCorreo()
    {
        $mensaje = "El producto XYZ tiene stock bajo.";
        Mail::to('rcuettolusky@gmail.com')->send(new NotificacionStock($mensaje));

        return "Correo enviado correctamente.";
    }
}
