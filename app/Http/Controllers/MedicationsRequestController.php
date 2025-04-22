<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;
use App\Services\PersonaService;

use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicationApprovedMail;
use Illuminate\Http\Request;


class MedicationsRequestController extends Controller
{
    protected $medicationsRequestService;
    protected $personaService;


    public function __construct(MedicationsRequestService $medicationsRequestService, PersonaService $personaService)
    {
        $this->medicationsRequestService = $medicationsRequestService;
        $this->personaService = $personaService;
    }

    public function listsMedicationRequests()
    {
        try {
            if (auth()->user()->hasRole(['administrador'])) {
                $medicationsRequests = $this->medicationsRequestService->getAll();
                $dnis = $medicationsRequests->pluck('dni_persona')->unique()->toArray();

                // Obtener personas por esos DNI
                $people = $this->personaService->getByDnis($dnis);
                return view('medications.index', ['medicationsRequests' => $medicationsRequests, 'person' => $people]);
            } else {
                $medicationsRequests = $this->medicationsRequestService->getRequestsByDni(auth()->user()->dni);
                $person = $this->personaService->getByDni(auth()->user()->dni);
                return view('medications.index', ['medicationsRequests' => $medicationsRequests, 'person' => $person]);
            }


        } catch (Exception $e) {
            Session::flash('message', 'Error al obtener las solicitudes de medicamentos');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function deleteMedicationRequest($id)
    {
        try {
            $this->medicationsRequestService->deleteRequestById($id);
            return redirect()->back()
                ->with('success', 'Solicitud desaprobada correctamente.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al desaprobar la solicitud de medicamentos.');
        }
    }
    /*public function approveMedicationRequest($id){
        try {
           $this->medicationsRequestService->approveRequestById($id);

           return redirect()->back(); 
             
        } catch (Exception $e) {
            Session::flash('message', 'Error al aprobar la solicitud de medicamento');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back(); 
        }
    }*/
    public function approveMedicationRequest($id)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->approveRequestById($id);

            // Enviar correo
            $destinatarios = ['geronimo.alles@lafedar.com'];
            foreach ($destinatarios as $email) {
                Mail::to($email)->send(new MedicationApprovedMail($medicationRequest));
            }

            return redirect()->back()
                ->with('success', 'Solicitud aprobada correctamente.');

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Error al aprobar la solicitud de medicamento');
        }
    }


    public function generateCertificateMedicationRequest($id)
    {
        try {

            return view('medications.certificate', ['id' => $id]);

        } catch (Exception $e) {
            Session::flash('message', 'Error al mostrar el remito de la solicitud de medicamento');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function updateMedicationRequest(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'medicamento' => 'required|string|max:255',
                'cantidad' => 'required|integer',

            ]);
            
            $medicamento = $request->input('medicamento');
            $cantidad = $request->input('cantidad');
         
            $this->medicationsRequestService->updateMedicationRequestById($id, $medicamento, $cantidad);

            return redirect()->route('medications.index')
                ->with('success', 'Solicitud actualizada correctamente.');

        } catch (Exception $e) {
            Session::flash('message', 'Error al mostrar el remito de la solicitud de medicamento');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }
    public function showMedicationRequest($id)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->getRequestById($id);
            

            return view('medications.edit', ['medication' => $medicationRequest]);

        } catch (Exception $e) {
            Session::flash('message', 'Error al mostrar el remito de la solicitud de medicamento');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }



}
