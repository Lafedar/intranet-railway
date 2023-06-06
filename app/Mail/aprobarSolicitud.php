<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class aprobarSolicitud extends Mailable
{
    use Queueable, SerializesModels;
    public $nombre;
    public $idSolicitud;
    public $estado;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $idSolicitud, $estado)
    {
        $this->nombre = $nombre;
        $this->idSolicitud = $idSolicitud;
        $this->estado = $estado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.aprobarSolicitudes');
    }
}