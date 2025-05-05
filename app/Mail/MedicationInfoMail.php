<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $medicationRequest;
    public $person;
    public $date;
    public function __construct($medicationRequest, $person, $date)
    {
        $this->medicationRequest = $medicationRequest;
        $this->person = $person;
        $this->date = $date;
    }



    public function build()
    {   
        $name = $this->person->apellido . ' ' . $this->person->nombre_p;

        $message = "
        <p>Hola {$name},</p>
        <p>Tu solicitud de medicamentos:</p>
        <ul>";

        if (!empty($this->medicationRequest->medicamento1 && $this->medicationRequest->aprobado1 == 1)) {
            $message .= "<li>{$this->medicationRequest->medicamento1} (Cantidad: {$this->medicationRequest->cantidad1})</li>";
        }
        if (!empty($this->medicationRequest->medicamento2 && $this->medicationRequest->aprobado2 == 1)) {
            $message .= "<li>{$this->medicationRequest->medicamento2} (Cantidad: {$this->medicationRequest->cantidad2})</li>";
        }
        if (!empty($this->medicationRequest->medicamento3 && $this->medicationRequest->aprobado3 == 1)) {
            $message .= "<li>{$this->medicationRequest->medicamento3} (Cantidad: {$this->medicationRequest->cantidad3})</li>";
        }

        $message .= "</ul>
        <p>Ha sido <strong>aprobada</strong> con fecha {$this->date}.</p>
        <p>Saludos</p>
    ";

        return $this->subject('Aprobación de solicitud de medicamento.')
            ->html($message);
    }

}
