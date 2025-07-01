<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicalCertificateUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $certificado;
    public $imagePath2;

    public function __construct($user, $certificado, $imagePath2)
    {
        $this->user = $user;
        $this->certificado = $certificado;
        $this->imagePath2 = $imagePath2;
    }

    public function build()
    {
        return $this->subject('Nuevo certificado médico')
            ->view('mails.medicalCertificateUser')
            ->with([
                'nombre' => $this->user->name,
                'titulo' => $this->certificado->titulo,
                
            ]);
           
    }
}
