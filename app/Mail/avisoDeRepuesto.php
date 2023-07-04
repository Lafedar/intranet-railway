<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class avisoDeRepuesto extends Mailable
{
    use Queueable, SerializesModels;
    public $idSolicitud;
    public $estado;
    public $titulo;
    public $descripcionRepuesto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idSolicitud, $estado, $titulo, $descripcionRepuesto)
    {
        $this->idSolicitud = $idSolicitud;
        $this->estado = $estado;
        $this->titulo = $titulo;
        $this->descripcionRepuesto = $descripcionRepuesto;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.avisoDeRepuesto');
    }
}