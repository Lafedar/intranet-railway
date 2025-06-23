<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;
use App\Services\PersonaService;
use App\Services\EncryptService;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MedicationApprovedMail;
use Illuminate\Http\Request;
use App\Mail\MedicationInfoMail;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralParametersService;
use App\Services\UserService;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Mail\MedicationNotificationMail;
use App\Mail\ResetPasswordApi;
use App\Mail\MedicationNotificationUser;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;






class MedicationsRequestController extends Controller
{
    protected $medicationsRequestService;
    protected $personaService;

    protected $genParametersService;
    protected $encryptService;

    protected $userService;


    public function __construct(MedicationsRequestService $medicationsRequestService, PersonaService $personaService, GeneralParametersService $genParametersService, EncryptService $encryptService, UserService $userService)
    {
        $this->medicationsRequestService = $medicationsRequestService;
        $this->personaService = $personaService;
        $this->genParametersService = $genParametersService;
        $this->encryptService = $encryptService;
        $this->userService = $userService;
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
            $items = $this->medicationsRequestService->getAllItemsByMedicationRequestId($id);
            foreach ($items as $item) {
                $this->medicationsRequestService->desapproveItem($item->id);
            }
            return redirect()->back()
                ->with('success', 'Solicitud actualizada a Aprobación pendiente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error updating request to Pending Approval: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar la solicitud a Aprobación Pendiente.');
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

    public function getItems($id)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->getRequestById($id);
            $itemsMedicationsRequests = $this->medicationsRequestService->getAllItemsByMedicationRequestId($id);


            return view('medications.items', [
                'itemsMedicationsRequests' => $itemsMedicationsRequests,
                'medicationRequest' => $medicationRequest,

            ]);
        } catch (Exception $e) {
            Log::error('Error en getItems: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }



    }

    public function approveItem($id, $id_solicitud)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->getRequestById($id_solicitud);

            if ($medicationRequest->estado != "Aprobada") {
                if ($this->medicationsRequestService->approveItem($id)) {
                    return redirect()->back()->with('success', 'Item aprobado exitosamente.');
                } else {
                    return redirect()->back()->with('error', 'Error al aprobar el item.');
                }
            }

            return redirect()->back()->with('error', 'La solicitud ya está aprobada y no se pueden modificar los ítems.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error approving item: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al aprobar el item');
        }

    }


    public function desapproveItem($id, $id_solicitud)
    {
        try {
            $medicationRequest = $this->medicationsRequestService->getRequestById($id_solicitud);

            if ($medicationRequest->estado != "Aprobada") {
                if ($this->medicationsRequestService->desapproveItem($id)) {
                    return redirect()->back()->with('success', 'Item desaprobado exitosamente.');
                } else {
                    return redirect()->back()->with('error', 'Error al aprobar el item.');
                }
            }

            return redirect()->back()->with('error', 'La solicitud ya está aprobada y no se pueden modificar los ítems.');
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error desapproving item: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al desaprobar el item');
        }

    }

    public function updateItem($id, Request $request)
    {
        try {
            $data = $request->validate([
                'medicamento' => 'required|string|max:255',
                'cantidad_solicitada' => 'required|integer',
                'cantidad_aprobada' => 'integer',
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


    public function approveMedicationRequest(Request $request, $request_id, $person_dni)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1', // Asegura que al menos un ítem sea seleccionado

            ]);


            $person = $this->personaService->getByDni($person_dni);
            if ($person == null) {

                $person = $person_dni;

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


            $items = $this->medicationsRequestService->getAllItemsByMedicationRequestId($request_id);
            $selectedItemIds = array_map('intval', $request->input('items'));
            foreach ($items as $item) {
                if (in_array($item->id, $selectedItemIds)) {
                    $this->medicationsRequestService->approveItem($item->id);


                }
            }

            /*obtengo lo items nuevamente para que se actualicen*/
            $updatedItems = $this->medicationsRequestService->getAllItemsByMedicationRequestId($request_id);

            $update = $this->medicationsRequestService->approveRequestById($request_id);
            if (!$update) {
                return back()->with('error', 'Debe aprobar al menos un medicamento.')->withInput();

            }


            $medicationRequest = $this->medicationsRequestService->getRequestById($request_id);
            if ($recipients == null) {
                return back()->with('error', 'No se encontraron correos para enviar la notificación.')->withInput();
            }
            $emails = explode(';', $recipients);
            $isPdf = true;

            if ($update == true) {
                foreach ($emails as $email) {

                    Mail::to($email)->send(new MedicationApprovedMail($medicationRequest, $updatedItems, $person, $base64image, $base64image_signature, $date, $isPdf));
                }
                if (!empty($person->correo)) {
                    Mail::to($person->correo)->send(new MedicationInfoMail($medicationRequest, $updatedItems, $person, $date));
                }
            } else {
                return redirect()->back()
                    ->with('error', 'Error al aprobar la solicitud de medicamento.');
            }

            return redirect()->route('medications.index')
                ->with('success', 'Solicitud aprobada correctamente.');

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error approving medication request and your items: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al aprobar la solicitud y sus items.');
        }

    }


    /*API*/
    public function saveNewMedicationRequest(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            if (!$decrypted) {
                return response()->json(['message' => 'Error al desencriptar los datos'], 400);
            }
            $data = json_decode($decrypted, true);

            if (!isset($data['data'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $payload = $data['data'];

            $person = $this->personaService->getByDni($payload['dni_user']);
            $mailsString = $this->genParametersService->getMailsToMedicationRequests();
            $mails = explode(',', $mailsString);

            if (!$person) {
                return response()->json(['message' => 'La persona no existe'], 401);
            } else {
                $create = $this->medicationsRequestService->create($payload);

                if ($create) {
                    foreach ($mails as $mail) {
                        Mail::to(trim($mail))->send(new MedicationNotificationMail($payload, $person));
                    }
                    Mail::to($person->correo)->send(new MedicationNotificationUser($payload, $person));
                    return response()->json(['message' => 'Solicitud creada exitosamente! Se enviará un correo de confirmación.'], 200);
                } else {
                    return response()->json(['message' => 'Hubo un problema al crear la solicitud'], 500);
                }
            }


        } catch (Exception $e) {
            Log::error('Error en saveNewMedicationRequest: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    public function getAllMedicationRequestAndItemsByUserDni(Request $request)
    {
        try {
            //Desencripto los datos
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            // Validación
            if (!isset($data['data']['dni_user'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            // Acceder al dni_user correctamente
            $dni = $data['data']['dni_user'];

            // Obtener las solicitudes con sus items
            $requestsData = $this->medicationsRequestService->getAllMedicationRequestAndItemsByUserDni($dni);



            //Encripto los datos
            $responseIv = random_bytes(12);
            $aesKeyBase64 = $request->session()->get('aes_key');
            $key = base64_decode($aesKeyBase64);

            $ciphertextWithTag = $this->encryptService->encrypt($requestsData, $key, $responseIv);
            return response()->json([
                'ciphertext' => base64_encode($ciphertextWithTag),
                'iv' => base64_encode($responseIv),
            ]);



        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting all medication requests by user dni: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el item'], 500);
        }

    }



    public function createUserApi(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            if (!isset($data['data']['email'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];
            $password = $data['data']['password'];

            if (!is_object($this->personaService->getByDni($dni))) {
                return response()->json(['message' => 'El Dni no existe'], 400);
            }

            /*DESCOMENTAR*/
            if (is_object($this->userService->getByDni($dni))) {
                return response()->json(['message' => 'El Dni ingresado ya tiene un usuario registrado'], 400);
            }

            if ($this->userService->validateMail($email)) {
                return response()->json(['message' => 'El email ya esta registrado'], 400);
            }


            $person = $this->personaService->getByDni($dni);
            if ($person->activo == 0) {
                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }
            $nombre = $person->nombre_p . ' ' . $person->apellido;

            $user = $this->userService->createRegisterUserApi($dni, $person->nombre_p, $person->apellido, $email, $password);

            if ($user != null) {
                Mail::to($email)->send(new VerificationEmail($nombre, $user->remember_token));
                return response()->json(['message' => 'Usuario creado exitosamente! Se enviará un correo de verificación.'], 200);
            } else {
                return response()->json(['message' => 'La persona ya tiene usuario registrado'], 400);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating user: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear el usuario'], 500);
        }

    }

    public function generateNewVerificationEmail(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            if (!isset($data['data']['email'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];

            $person = $this->personaService->getByDni($dni);
            if ($person->activo == 1) {
                $nombre = $person->nombre_p . ' ' . $person->apellido;
                $token = $this->userService->createNewToken($dni);

                Mail::to($email)->send(new VerificationEmail($nombre, $token));
                return response()->json(['message' => 'Mail reenviado correctamente'], 200);
            } else {

                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error generating new verification email: ' . $e->getMessage());
            return response()->json(['message' => 'Error al generar el mail de verificación'], 500);
        }


    }

    public function sendMailResetPassword(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            /*if (!isset($data['data']['email'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }*/

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];

            $person = $this->personaService->getByDni($dni);
            if(!is_object($person)){
                return response()->json(['message' => 'La persona no existe'], 400);
            }
            $user = $this->userService->getByDni($dni);
            if ($person->activo == 1) {
                if ($user->activo == 1) {
                    if ($user->email == $email) {
                        $nombre = $person->nombre_p . ' ' . $person->apellido;
                        $token = $this->userService->createNewTokenUser($dni);
                        Mail::to($email)->send(new ResetPasswordApi($nombre, $token));
                        return response()->json(['message' => 'Mail enviado correctamente!'], 200);
                    }else{
                        return response()->json(['message' => 'El usuario no está registrado'], 400);
                    }

                } else {
                    return response()->json(['message' => 'El usuario no está activo'], 400);
                }


            } else {
                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }


        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error sending reset password email: ' . $e->getMessage());
            return response()->json(['message' => 'Error al enviar el mail de restablecimiento de contraseña'], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            /*if (!isset($data['data']['dni']) || !isset($data['data']['password'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }*/

            $dni = $data['data']['dni'];
            $password = $data['data']['password'];
            Log::info('DNI: ' . $dni);
            Log::info('Password: ' . $password);

            if ($this->userService->resetPassword($dni, $password)) {
                return response()->json(['message' => 'Contraseña restablecida correctamente!'], 200);
            } else {
                return response()->json(['message' => 'Error al restablecer la contraseña'], 500);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error resetting password: ' . $e->getMessage());
            return response()->json(['message' => 'Error al restablecer la contraseña'], 500);
        }
    }

    public function cleanTokens(Request $request)
    {
        try {
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            /*if (!isset($data['data']['dni'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }*/

            $dni = $data['data']['dni'];
            Log::info('DNI para limpiar tokens: ' . $dni);
            if(!$this->userService->cleanTokens($dni)){
                return response()->json(['message' => 'Error al limpiar los tokens'], 500);
            }
            return response()->json(['message' => 'Tokens limpiados correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error cleaning tokens: ' . $e->getMessage());
            return response()->json(['message' => 'Error al limpiar los tokens'], 500);
        }
    }



}
