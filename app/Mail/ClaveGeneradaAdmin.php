<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaveGeneradaAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $correo;
    public $clave;

    public function __construct($nombre, $correo, $clave)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->clave = $clave;
    }

    public function build()
    {
        return $this->subject('Contraseña generada Young Engineers Bolivia')->view('emails.claveGenerada');
    }
}
