<?php
namespace App\Mail;
 
use Illuminate\Mail\Mailable;
 
class CertificadoMail extends Mailable
{
    public $filePath;
    public $nombre;
    public $apellido;
    public $curso;
 
    public function __construct($filePath, $nombre, $apellido, $curso)
    {
        $this->filePath = $filePath;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->curso = $curso;
    }
 
    public function build()
    {
        return $this->subject('Certificado del Curso')
                    ->view('mails.certificado', ['nombre' => $this->nombre],  ['apellido' => $this->apellido],  ['curso' => $this->curso]) 
                    ->attach($this->filePath, [
                        'as' => 'certificado.pdf',
                        'mime' => 'application/pdf',
                    ]);
                    
    }
}