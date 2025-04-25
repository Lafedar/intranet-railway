<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;
use App\Services\PersonaService;

use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicationApprovedMail;
use Illuminate\Http\Request;
use App\Mail\MedicationInfoMail;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;


class MedicationsRequestController extends Controller
{
    protected $medicationsRequestService;
    protected $personaService;

    protected $genParametersService;


    public function __construct(MedicationsRequestService $medicationsRequestService, PersonaService $personaService, GeneralParametersService $genParametersService)
    {
        $this->medicationsRequestService = $medicationsRequestService;
        $this->personaService = $personaService;
        $this->genParametersService = $genParametersService;
    }

    public function listsMedicationRequests(Request $request)
    {
        try {
            $nombrePersona = $request->input('persona');
            $nombreMedicamento = $request->input('medicamento');

            if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh')) {
                $medicationsRequests = $this->medicationsRequestService->getAll();
                $dnis = $medicationsRequests->pluck('dni_persona')->unique()->toArray();

                $people = $this->personaService->getByDnis($dnis);

                // Filtro por persona
                if ($nombrePersona) {
                    $peopleFiltradas = $people->filter(function ($person) use ($nombrePersona) {
                        return stripos($person->apellido . ' ' . $person->nombre_p, $nombrePersona) !== false;
                    });

                    $dnisFiltrados = $peopleFiltradas->pluck('dni')->toArray();

                    $medicationsRequests = $medicationsRequests->filter(function ($req) use ($dnisFiltrados) {
                        return in_array($req->dni_persona, $dnisFiltrados);
                    });
                }

                // Filtro por medicamento
                if ($nombreMedicamento) {
                    $medicationsRequests = $medicationsRequests->filter(function ($req) use ($nombreMedicamento) {
                        return stripos($req->medicamento1, $nombreMedicamento) !== false ||
                            stripos($req->medicamento2, $nombreMedicamento) !== false ||
                            stripos($req->medicamento3, $nombreMedicamento) !== false;
                    });
                }

                return view('medications.index', [
                    'medicationsRequests' => $medicationsRequests,
                    'persons' => $people,
                    'filters' => ['persona' => $nombrePersona, 'medicamento' => $nombreMedicamento],
                ]);

            } else {
                $medicationsRequests = $this->medicationsRequestService->getRequestsByDni(auth()->user()->dni);
                $person = $this->personaService->getByDni(auth()->user()->dni);

                return view('medications.index', [
                    'medicationsRequests' => $medicationsRequests,
                    'persons' => $person
                ]);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error displaying medications requests: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al obtener las solicitudes de medicamentos.');
        }
    }

    public function changeMedicationRequestToPendingApproval($id)
    {
        try {
            $this->medicationsRequestService->deleteRequestById($id);
            return redirect()->back()
                ->with('success', 'Solicitud actualizada a Aprobacion pendiente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error deleting medication request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al desaprobar la solicitud de medicamentos.');
        }
    }

    public function completeMedicationRequest($id, $person_id, Request $request)
    {
        try {

            $approved1 = $request->input('approved_checkbox');
            $approved2 = $request->input('approved2_checkbox');
            $approved3 = $request->input('approved3_checkbox');

            if ($approved1 == null) {
                $approved1 = "0";
            }
            if ($approved2 == null) {
                $approved2 = "0";
            }
            if ($approved3 == null) {
                $approved3 = "0";
            }


            $person = $this->personaService->getById($person_id);
            if ($person == null) {

                $person = $person_id;

            }

            $imagePath = storage_path('app/public/cursos/logo-lafedar.png');
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $mimeType = mime_content_type($imagePath);
                $base64image = 'data:' . $mimeType . ';base64,' . $imageData;
            } else {
                $base64image = null;
            }

            $signaturePath = storage_path('app/public/cursos/firma_rrhh.png');

            if (file_exists($signaturePath)) {

                $imageData2 = base64_encode(file_get_contents($signaturePath));
                $mimeType2 = mime_content_type($signaturePath); // Obtener el tipo MIME de la imagen (ej. image/png)

                // Crear la cadena de imagen Base64
                $base64image_signature = 'data:' . $mimeType2 . ';base64,' . $imageData2;
            } else {

                $base64image_signature = null;
            }

            $recipients = $this->genParametersService->getMailsToMedicationRequests();
            $date = date('d/m/Y');
            if ($approved1 !== "1" && $approved2 !== "1" && $approved3 !== "1") {
                return back()->with('error', 'Debe aprobar al menos un medicamento.')->withInput();

            } else {
                $update = $this->medicationsRequestService->approveRequestById($id, $approved1, $approved2, $approved3);
            }
            $medicationRequest = $this->medicationsRequestService->getRequestById($id);
            $emails = explode(';', $recipients);
            if ($update == true) {
                foreach ($emails as $email) {

                    Mail::to($email)->send(new MedicationApprovedMail($medicationRequest, $person, $base64image, $base64image_signature, $date));
                }
                if (!empty($person->correo)) {
                    Mail::to($person->correo)->send(new MedicationInfoMail($medicationRequest, $person, $date));
                }
            } else {
                return redirect()->back()
                    ->with('error', 'Error al aprobar la solicitud de medicamento.');
            }


            return redirect()->back()
                ->with('success', 'Solicitud aprobada correctamente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error approving medication request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al aprobar la solicitud de medicamento ' . $e->getMessage());
        }
    }


    public function generateMedicationRequestDeliveryNote($id, $person_id)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->getRequestById($id);
            $imagePath = storage_path('app/public/cursos/logo-lafedar.png');
            $person = $this->personaService->getById($person_id);
            if ($person == null) {

                $person = $person_id;

            }
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $mimeType = mime_content_type($imagePath);
                $base64image = 'data:' . $mimeType . ';base64,' . $imageData;
            } else {
                $base64image = null;
            }

            $signaturePath = storage_path('app/public/cursos/firma_rrhh.png');

            if (file_exists($signaturePath)) {

                $imageData2 = base64_encode(file_get_contents($signaturePath));
                $mimeType2 = mime_content_type($signaturePath); // Obtener el tipo MIME de la imagen (ej. image/png)

                // Crear la cadena de imagen Base64
                $base64image_signature = 'data:' . $mimeType2 . ';base64,' . $imageData2;
            } else {

                $base64image_signature = null;
            }

            $date = date('d/m/Y');
            return view('medications.certificate', ['medication' => $medicationRequest, 'base64image' => $base64image, 'person' => $person, 'base64image_signature' => $base64image_signature, 'fecha' => $date]);

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error displaying the delivery note: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al mostrar el remito de la solicitud de medicamento ' . $e->getMessage());
        }
    }

    public function reviewAndUpdateMedicationRequest(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'medication1' => 'required|string|max:255',
                'amount1' => 'required|integer',
                'approved1' => 'nullable|integer',
                'medication2' => 'nullable|string|max:255',
                'amount2' => 'nullable|integer',
                'approved2' => 'nullable|integer',
                'medication3' => 'nullable|string|max:255',
                'amount3' => 'nullable|integer',
                'approved3' => 'nullable|integer',

            ]);

            $this->medicationsRequestService->updateMedicationRequestById($id, $validateData);

            return redirect()->route('medications.index')
                ->with('success', 'Solicitud actualizada correctamente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating medication request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar la solicitud de medicamentos ' . $e->getMessage());
        }
    }
    public function showMedicationRequestEditForm($id)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->getRequestById($id);


            return view('medications.edit', ['medication' => $medicationRequest]);

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error displaying medication request data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al mostrar los datos para editar de la solicitud de medicamentos ' . $e->getMessage());
        }
    }



}
