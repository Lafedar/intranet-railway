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

    public $imagePath2;

    public function __construct($data, $person, $imagePath2)
    {
        $this->data = $data;
        $this->person = $person;
        $this->imagePath2 = $imagePath2;
    }



    public function build()
    {
        if (is_object($this->person)) {
            $name = $this->person->apellido . ' ' . $this->person->nombre_p;
        } else {
            $name = $this->person;
        }

        return $this->subject('Nueva solicitud de Medicamentos.')
            ->view('mails.newMedicationRequest')
            ->with([
                'name' => $name,
                'data' => $this->data,
                'imagePath2' => $this->imagePath2
            ]);
    }



}
