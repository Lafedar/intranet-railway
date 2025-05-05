<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $medicationRequest;
    public $person;
    public $base64image;
    public $base64image_signature;
    public $date;
    public $isPdf;
    public function __construct($medicationRequest, $person, $base64image, $base64image_signature, $date, $isPdf)
    {
        $this->medicationRequest = $medicationRequest;
        $this->person = $person;
        $this->base64image = $base64image;
        $this->base64image_signature = $base64image_signature;
        $this->date = $date;
        $this->isPdf = $isPdf;
    }
 
   

    public function build()
    {
        
        return $this->subject('Nueva solicitud de medicamento.')
                    ->view('medications.certificate', [
                        'medication' => $this->medicationRequest,
                        'person' => $this->person,
                        'imageBase64' => $this->base64image,
                        'imageBase64_firma' => $this->base64image_signature,
                        'fecha' => $this->date,
                        'isPdf' => $this->isPdf
                    ]);
    }
}
