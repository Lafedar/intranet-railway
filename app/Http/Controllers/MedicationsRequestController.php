<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;
use App\Services\PersonaService;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicationApprovedMail;
use Illuminate\Http\Request;
use App\Mail\MedicationInfoMail;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Mail\MedicationNotificationMail;
use App\Mail\MedicationNotificationUser;



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

            if (auth()->user()->hasRole('administrador') || auth()->user()->hasRole('rrhh')) {
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


                return view('medications.index', [
                    'medicationsRequests' => $medicationsRequests,
                    'persons' => $people,
                    'filters' => ['persona' => $nombrePersona, 'medicamento' => $nombreMedicamento],
                ]);

            } else {
                $medicationsRequests = $this->medicationsRequestService->getRequestsByDni(auth()->user()->dni);
                $ids = collect($medicationsRequests)->pluck('id')->toArray();
                $itemsMedicationsRequests = $this->medicationsRequestService->getItemsForMultipleRequests($ids);
                $person = $this->personaService->getByDni(auth()->user()->dni);
                $groupedItems = collect($itemsMedicationsRequests)->groupBy('id_solicitud');


                return view('medications.index', [
                    'medicationsRequests' => $medicationsRequests,
                    'itemsMedicationsRequests' => $groupedItems,
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
                ->with('success', 'Solicitud actualizada a Aprobación pendiente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating request to Pending Approval: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar la solicitud a Aprobación Pendiente.');
        }
    }

    public function completeMedicationRequest($id, $person_id, Request $request)
    {
        try {

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

            $update = $this->medicationsRequestService->approveRequestById($id);
            if (!$update) {
                return back()->with('error', 'Debe aprobar al menos un medicamento.')->withInput();

            }

            $medicationRequest = $this->medicationsRequestService->getRequestById($id);
            $items = $this->medicationsRequestService->getAllItemsByMedicationRequestId($medicationRequest->id);
            if ($recipients == null) {
                return back()->with('error', 'No se encontraron correos para enviar la notificación.')->withInput();
            }
            $emails = explode(';', $recipients);
            $isPdf = true;
            if ($update == true) {
                foreach ($emails as $email) {

                    Mail::to($email)->send(new MedicationApprovedMail($medicationRequest, $items, $person, $base64image, $base64image_signature, $date, $isPdf));
                }
                if (!empty($person->correo)) {
                    Mail::to($person->correo)->send(new MedicationInfoMail($medicationRequest, $items, $person, $date));
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
            $items = $this->medicationsRequestService->getAllItemsByMedicationRequestId($medicationRequest->id);
            $imagePath = storage_path('app/public/cursos/logo-lafedar.png');
            $person = $this->personaService->getById($person_id);
            if ($person == null) {
                $person = $person_id;
            }

            $base64image = null;
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $mimeType = mime_content_type($imagePath);
                $base64image = 'data:' . $mimeType . ';base64,' . $imageData;
            }

            $signaturePath = storage_path('app/public/cursos/firma_rrhh.png');
            $base64image_signature = null;
            if (file_exists($signaturePath)) {
                $imageData2 = base64_encode(file_get_contents($signaturePath));
                $mimeType2 = mime_content_type($signaturePath);
                $base64image_signature = 'data:' . $mimeType2 . ';base64,' . $imageData2;
            }

            $isPdf = true; // importante para controlar estilos condicionales en la vista
            $date = now()->format('d/m/Y');

            $html = view('medications.certificate', [
                'medication' => $medicationRequest,
                'items' => $items,
                'base64image' => $base64image,
                'person' => $person,
                'base64image_signature' => $base64image_signature,
                'fecha' => $date,
                'isPdf' => $isPdf
            ])->render();

            $pdf = \SnappyPdf::loadHTML($html)
                ->setOption('orientation', 'portrait')
                ->setOption('enable-local-file-access', true)
                ->setOption('enable-javascript', true)
                ->setOption('javascript-delay', 200)
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 2)
                ->setOption('margin-left', 10);

            // Mostrar el PDF en el navegador
            return $pdf->inline('remito.pdf');

        } catch (Exception $e) {
            \Log::error('Error displaying the delivery note: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al mostrar el remito: ' . $e->getMessage());
        }
    }

    public function showMedicationRequestEditForm($id)
    {
        try {
            $item = $this->medicationsRequestService->getItemByMedicationRequestId($id);


            return view('medications.edit', ['item' => $item]);

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error displaying item data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al mostrar los datos para editar el item ' . $e->getMessage());
        }
    }

    public function generatePDFcertificate($id, $personId)
    {
        try {
            $medication = $this->medicationsRequestService->getRequestById($id);

            $person = $this->personaService->getById($personId);
            if ($person == null) {

                $person = $personId;

            }
            $fecha = now()->format('d/m/Y');

            $imagePath = storage_path('app/public/cursos/logo-lafedar.png');

            if (file_exists($imagePath)) {

                $imageData = base64_encode(file_get_contents($imagePath));
                $mimeType = mime_content_type($imagePath); // Obtener el tipo MIME de la imagen (ej. image/png)


                $base64image = 'data:' . $mimeType . ';base64,' . $imageData;
            } else {

                $base64image = null;
            }


            $firmaPath = storage_path('app/public/cursos/firma_rrhh.png');

            if (file_exists($firmaPath)) {

                $imageData2 = base64_encode(file_get_contents($firmaPath));
                $mimeType2 = mime_content_type($firmaPath); // Obtener el tipo MIME de la imagen (ej. image/png)

                // Crear la cadena de imagen Base64
                $base64image_signature = 'data:' . $mimeType2 . ';base64,' . $imageData2;
            } else {

                $base64image_signature = null;
            }

            $isPdf = true;


            $html = view('medications.certificate', compact('medication', 'base64image', 'person', 'base64image_signature', 'fecha', 'isPdf'))->render();



            $pdf = SnappyPdf::loadHTML($html)
                ->setOption('orientation', 'portrait') // Establece la orientación a apaisado
                ->setOption('enable-local-file-access', true)
                ->setOption('enable-javascript', true)
                ->setOption('javascript-delay', 200)
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 2)
                ->setOption('margin-left', 10);


            return $pdf->download('remito.pdf');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error generating PDF certificate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF del remito de la solicitud de medicamentos');
        }

    }

    public function saveDataFromApi(Request $request)
    {
        try {
            $data = $request->all();
            $recipients = $this->genParametersService->getMailsToMedicationRequests();
            if ($recipients == null) {
                return back()->with('error', 'No se encontraron correos para enviar la notificación.')->withInput();
            }
            $emails = explode(';', $recipients);
            $person = $this->personaService->getByDni($data['dni_persona']);

            if (!is_object($person)) {
                $person = $data['dni_persona'];
            }
            foreach ($emails as $email) {

                Mail::to($email)->send(new MedicationNotificationMail($data, $person));
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error saving data from Api: ' . $e->getMessage());

        }
    }


    public function saveNewMedicationRequest(Request $request)
    {
        try {
            // Obtener datos cifrados desde el request
            $ciphertextBase64 = $request->input('ciphertext');
            $ivBase64 = $request->input('iv');

            if (!$ciphertextBase64 || !$ivBase64) {
                return response()->json(['message' => 'Faltan datos encriptados'], 400);
            }

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);

            // Obtener la clave AES desde la sesión
            $aesKeyBase64 = $request->session()->get('aes_key');

            if (!$aesKeyBase64) {
                return response()->json(['message' => 'Clave AES no encontrada en la sesión'], 400);
            }

            $aesKey = base64_decode($aesKeyBase64);

            // Separar tag de autenticación (últimos 16 bytes del ciphertext)
            $tagLength = 16;
            if (strlen($ciphertext) < $tagLength) {
                return response()->json(['message' => 'Datos encriptados inválidos'], 400);
            }

            $tag = substr($ciphertext, -$tagLength);
            $ciphertextRaw = substr($ciphertext, 0, -$tagLength);

            // Desencriptar con AES-GCM
            $decrypted = openssl_decrypt(
                $ciphertextRaw,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            if ($decrypted === false) {
                return response()->json(['message' => 'Error al desencriptar los datos'], 400);
            }

            // Decodificar JSON
            $data = json_decode($decrypted, true);

            if (!isset($data['data'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $payload = $data['data'];

            Log::info('Payload recibido en controlador:', ['data' => $payload]);

            $person = $this->personaService->getByDni($payload['dni']);
            $mails = $this->genParametersService->getMailsToMedicationRequests();

            if (!$person) {
                return response()->json(['message' => 'La persona no existe'], 401);
            } else {
                $create = $this->medicationsRequestService->create($payload);

                if ($create) {
                    foreach ($mails as $mail) {
                        Mail::to($mail)->send(new MedicationNotificationMail($payload, $person));
                    }
                    Mail::to($person->correo)->send(new MedicationNotificationUser($payload, $person));
                    return response()->json(['message' => 'Solicitud creada exitosamente'], 200);
                } else {
                    return response()->json(['message' => 'Hubo un problema al crear la solicitud'], 500);
                }
            }


        } catch (\Throwable $e) {
            \Log::error('Error en saveNewMedicationRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    public function getItems($id)
    {
        $medicationRequest = $this->medicationsRequestService->getRequestById($id);
        $itemsMedicationsRequests = $this->medicationsRequestService->getAllItemsByMedicationRequestId($id);


        return view('medications.items', [
            'itemsMedicationsRequests' => $itemsMedicationsRequests,
            'medicationRequest' => $medicationRequest,

        ]);


    }

    public function approveItem($id, $id_solicitud)
    {
        $medicationRequest = $this->medicationsRequestService->getRequestById($id_solicitud);

        if ($medicationRequest->estado != "Aprobada") {
            if ($this->medicationsRequestService->approveItem($id)) {
                return redirect()->back()->with('success', 'Item aprobado exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al aprobar el item.');
            }
        }

        return redirect()->back()->with('error', 'La solicitud ya está aprobada y no se pueden modificar los ítems.');
    }


    public function desapproveItem($id, $id_solicitud)
    {
        $medicationRequest = $this->medicationsRequestService->getRequestById($id_solicitud);

        if ($medicationRequest->estado != "Aprobada") {
            if ($this->medicationsRequestService->desapproveItem($id)) {
                return redirect()->back()->with('success', 'Item desaprobado exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al aprobar el item.');
            }
        }

        return redirect()->back()->with('error', 'La solicitud ya está aprobada y no se pueden modificar los ítems.');
    }

    public function updateItem($id, Request $request)
    {
        try {
            $data = $request->validate([
                'medicamento' => 'required|string|max:255',
                'cantidad' => 'required|integer',
                'lote_med' => 'nullable|string|max:255',
                'vencimiento_med' => 'nullable|date_format:Y-m-d',
            ]);

            if ($this->medicationsRequestService->updateItem($id, $data)) {
                return redirect($request->input('previous_url'))->with('success', 'Item actualizado correctamente.');

            } else {
                return redirect()->back()->with('error', 'Error al actualizar el item.');
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating item: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el item.');
        }
    }



}
