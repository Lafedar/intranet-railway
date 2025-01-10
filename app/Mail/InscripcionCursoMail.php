<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscripcionCursoMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $curso;
    private $fechaInicio;

    private $imageBase64Firma;

    public function __construct($user, $curso, $fechaInicio, $imageBase64Firma)
    {
        $this->user = $user;
        $this->curso = $curso;
        $this->fechaInicio = $fechaInicio;

        $this->imageBase64Firma = $imageBase64Firma;
    }

    public function build()
    {
        return $this->view('mails.inscripcion_curso')
            ->subject('Inscripción a la Capacitación')
            ->with([
                'user' => $this->user,
                'curso' => $this->curso,
                'fechaInicio' => $this->fechaInicio,
                'imageBase64Firma' => $this->imageBase64Firma,
            ]);
    }
}
