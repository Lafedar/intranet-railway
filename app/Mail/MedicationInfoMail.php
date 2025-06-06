<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationInfoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $medicationRequest;
    public $items;
    public $person;
    public $date;
    public function __construct($medicationRequest, $items, $person, $date)
    {
        $this->medicationRequest = $medicationRequest;
        $this->items = $items;
        $this->person = $person;
        $this->date = $date;
    }



    public function build()
    {
        $name = $this->person->apellido . ' ' . $this->person->nombre_p;
        $cantidad = 0;

        $message = "
    <p>Hola {$name},</p>
    <p>Tu solicitud de medicamentos:</p>
    <ul>";

        foreach ($this->items as $item) {
            if ($item->aprobado == 1) {
                $message .= "<li>{$item->medicamento} – Cantidad: {$item->cantidad}</li>";
                $cantidad += $item->cantidad;
            }
        }

        $message .= "</ul>
    <p><strong>Total de bultos:</strong> {$cantidad}</p>
    <p>Ha sido <strong>aprobada</strong> con fecha {$this->date}.</p>
    <p>Saludos</p>
    ";

        return $this->subject('Aprobación de solicitud de medicamento.')
            ->html($message);
    }


}
