<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class cambioDeEstadoSolicitud extends Mailable
{
    use Queueable, SerializesModels;
    public $nombre;
    public $idSolicitud;
    public $estado;
    public $titulo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $idSolicitud, $estado, $titulo)
    {
        $this->nombre = $nombre;
        $this->idSolicitud = $idSolicitud;
        $this->estado = $estado;
        $this->titulo = $titulo;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.cambioDeEstadoSolicitudes');
    }
}