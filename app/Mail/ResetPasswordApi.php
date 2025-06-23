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

    public function __construct($nombre, $token)
    {
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Restablecimiento de contraseña')
            ->view('mails.resetPasswordApi');
    }
}
