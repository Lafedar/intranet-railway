<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $id;
    public $nombre_solicitante;
    public $apellido_solicitante;
    public $nombre_encargado;
    public $apellido_encargado;

    public function __construct($nombre,$id, $nombre_solicitante, $apellido_solicitante, $nombre_encargado, $apellido_encargado)
    {
        $this->nombre = $nombre;  //estado
        $this->id = $id;
        $this->nombre_solicitante = $nombre_solicitante;
        $this->apellido_solicitante = $apellido_solicitante;
        $this->nombre_encargado= $nombre_encargado;
        $this->apellido_encargado = $apellido_encargado;
    }

    public function build()
    {
        return $this->view('mails.recordatorio');
    }
}


