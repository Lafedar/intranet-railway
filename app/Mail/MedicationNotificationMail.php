<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $person;

    public function __construct($data, $person)
    {
        $this->data = $data;
        $this->person = $person;
    }



    public function build()
    {
        if (is_object($this->person)) {
            $name = $this->person->apellido . ' ' . $this->person->nombre_p;
        } else {
            $name = $this->person;
        }

        $message = "
        <p>Hola,</p>
        <p><b>Hay una nueva solicitud de medicamentos:</b></p>

        <p>Solicitante: <b>{$name}</b></p>
        <ul>";


        $message .= "<li>" . $this->data['medication'] . " - Cantidad: " . $this->data['amount'] . "</li>";

        if (!empty($this->data['medication2']) && !empty($this->data['amount2'])) {

            $message .= "<li>" . $this->data['medication2'] . " - Cantidad: " . $this->data['amount2'] . "</li>";
        }

        if (!empty($this->data['medication3']) && !empty($this->data['amount3'])) {

            $message .= "<li>" . $this->data['medication3'] . " - Cantidad: " . $this->data['amount3'] . "</li>";
        }

        $message .= "</ul>
        <br>

        <p>Saludos</p>";

        return $this->subject('Nueva solicitud de Medicamentos.')
            ->html($message);
    }



}
