<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicalCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $certificado;
    public $contenido;
    public $mime;
    public $nombreArchivo;
    public $imagePath2;

    public function __construct($user, $certificado, $contenido, $mime, $nombreArchivo, $imagePath2)
    {
        $this->user = $user;
        $this->certificado = $certificado;
        $this->contenido = $contenido;
        $this->mime = $mime;
        $this->nombreArchivo = $nombreArchivo;
        $this->imagePath2 = $imagePath2;
    }

    public function build()
    {
        return $this->subject('Nuevo certificado médico')
            ->view('mails.medicalCertificateMail')
            ->with([
                'nombre' => $this->user->name,
                'titulo' => $this->certificado->titulo,
                'descripcion' => $this->certificado->descripcion,
                'imagePath2' => $this->imagePath2,
            ])
            ->attachData($this->contenido, $this->nombreArchivo, [
                'mime' => $this->mime
            ]);
    }
}
