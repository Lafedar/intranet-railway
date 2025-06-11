<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationNotificationUser extends Mailable
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
<<<<<<< HEAD
        if (is_object($this->person)) {
            $name = $this->person->apellido . ' ' . $this->person->nombre_p;
        } else {
            $name = $this->person;
        }

=======
        if(is_object($this->person)){
            $name = $this->person->apellido . ' ' . $this->person->nombre_p;
        }else{
            $name = $this->person;
        }
        
>>>>>>> 26a131190a9b1f995f0fdd66f5132067dfc66b85
        $message = "
        <p>Hola, {$name}</p>
        <p><b>Tu solicitud de medicamentos fue creada:</b></p>

        <ul>";

<<<<<<< HEAD

        $message .= "<li>" . $this->data['medication'] . " - Cantidad: " . $this->data['amount'] . "</li>";

        if (!empty($this->data['medication2']) && !empty($this->data['amount2'])) {

            $message .= "<li>" . $this->data['medication2'] . " - Cantidad: " . $this->data['amount2'] . "</li>";
        }

        if (!empty($this->data['medication3']) && !empty($this->data['amount3'])) {

            $message .= "<li>" . $this->data['medication3'] . " - Cantidad: " . $this->data['amount3'] . "</li>";
        }

        $message .= "</ul>
=======
           
            $message .= "<li>" . $this->data['medication'] . " - Cantidad: " . $this->data['amount'] . "</li>";

            if (isset($this->data['medication2']) && isset($this->data['amount2'])) {
                $message .= "<li>" . $this->data['medication2'] . " - Cantidad: " . $this->data['amount2'] . "</li>";
            }

            if (isset($this->data['medication3']) && isset($this->data['amount3'])) {
                $message .= "<li>" . $this->data['medication3'] . " - Cantidad: " . $this->data['amount3'] . "</li>";
            }

            $message .= "</ul>
>>>>>>> 26a131190a9b1f995f0fdd66f5132067dfc66b85
        <br>

        <p>Saludos</p>";

        return $this->subject('Nueva solicitud de Medicamentos.')
            ->html($message);
    }



}
