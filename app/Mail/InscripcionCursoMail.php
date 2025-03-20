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
    private $gestor;
    private $imageBase64Firma;
    private $sala;

    private $hora;
    public function __construct($user, $curso, $fechaInicio, $imageBase64Firma, $gestor, $sala, $hora)
    {
        $this->user = $user;
        $this->curso = $curso;
        $this->fechaInicio = $fechaInicio;
        $this->gestor = $gestor;
        $this->imageBase64Firma = $imageBase64Firma;
        $this->sala = $sala;
        $this->hora = $hora;
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
                'gestor' => $this->gestor,
                'sala' => $this->sala,
                'hora' => $this->hora,
            ]);
    }
}
