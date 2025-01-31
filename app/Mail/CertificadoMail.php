<?php
namespace App\Mail;
 
use Illuminate\Mail\Mailable;
 
class CertificadoMail extends Mailable
{
    public $filePath;
    public $nombre;
    public $apellido;
    public $curso;
    public $imageBase64Firma;
 
    public function __construct($filePath, $nombre, $apellido, $curso, $imageBase64Firma)
    {
        $this->filePath = $filePath;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->curso = $curso;
        $this->imageBase64Firma = $imageBase64Firma;
    }
 
    public function build()
    {
        return $this->subject('Certificado de la Capacitación')
                    ->view('mails.certificado', ['nombre' => $this->nombre], ['apellido' => $this->apellido],  ['curso' => $this->curso], ['imageBase64Firma' => $this->imageBase64Firma]) 
                    ->attach($this->filePath, [
                        'as' => 'certificado.pdf',
                        'mime' => 'application/pdf',
                    ]);
                    
    }
}