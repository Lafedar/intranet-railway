<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $token;
    public $imagePath2;
    public function __construct($nombre, $token, $imagePath2)
    {
        $this->nombre = $nombre;
        $this->token = $token;
        $this->imagePath2 = $imagePath2;
    }

    public function build()
    {
        return $this->subject('Verificá tu cuenta')
            ->view('mails.verificacionEmail');
    }
}
