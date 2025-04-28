<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationWarningMail extends Mailable
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
        if(is_object($this->person)){
            $name = $this->person->apellido . ' ' . $this->person->nombre_p;
        }else{
            $name = $this->person;
        }
        
        $message = "
        <p>Hola,</p>
        <p><b>Hay una nueva solicitud de medicamentos:</b></p>

        <p>Solicitante: <b>{$name}</b></p>
        <ul>";

           
            $message .= "<li>" . $this->data['medicamento1'] . " - Cantidad: " . $this->data['cantidad1'] . "</li>";

            if (isset($this->data['medicamento2']) && isset($this->data['cantidad2'])) {
                $message .= "<li>" . $this->data['medicamento2'] . " - Cantidad: " . $this->data['cantidad2'] . "</li>";
            }

            if (isset($this->data['medicamento3']) && isset($this->data['cantidad3'])) {
                $message .= "<li>" . $this->data['medicamento3'] . " - Cantidad: " . $this->data['cantidad3'] . "</li>";
            }

            $message .= "</ul>
        <br>

        <p>Saludos</p>";

        return $this->subject('Nueva solicitud de Medicamentos.')
            ->html($message);
    }



}
