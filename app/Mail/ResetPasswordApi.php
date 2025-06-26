<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordApi extends Mailable
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
        return $this->subject('Restablecimiento de contraseña')
            ->view('mails.resetPasswordApi');
    }

}
