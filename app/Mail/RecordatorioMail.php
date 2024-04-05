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
    public $titulo;

    public function __construct($nombre, $titulo)
    {
        $this->nombre = $nombre;
        $this->titulo = $titulo;
    }

    public function build()
    {
        return $this->view('mails.recordatorio');
    }
}


