<?php

namespace App\Http\Controllers;
use App\Services\EncryptService;
use App\Services\UserService;
use App\Services\PersonaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    protected $userService;
    protected $encryptService;
    protected $personService;
    public function __construct(UserService $userService, EncryptService $encryptService, PersonaService $personService)
    {
        $this->userService = $userService;
        $this->encryptService = $encryptService;
        $this->personService = $personService;

    }
    public function createUser(Request $request)
    {
        try {
            $imagePath2 = storage_path(config('images.public_path') . '/firma.jpg');
            $decrypted = $this->encryptService->decrypt($request);
            $data = json_decode($decrypted, true);

            if (!isset($data['data']['email'])) {
                return response()->json(['message' => 'Formato de datos inválido'], 400);
            }

            $dni = $data['data']['dni'];
            $email = $data['data']['email'];
            $password = $data['data']['password'];

            if (!is_object($this->personService->getByDni($dni))) {
                return response()->json(['message' => 'El Dni no existe'], 400);
            }

            /*DESCOMENTAR*/
            if (is_object($this->userService->getByDni($dni))) {
                return response()->json(['message' => 'El Dni ingresado ya tiene un usuario registrado'], 400);
            }

            if ($this->userService->validateMail($email)) {
                return response()->json(['message' => 'El email ya esta registrado'], 400);
            }


            $person = $this->personService->getByDni($dni);
            if ($person->activo == 0) {
                return response()->json(['message' => 'La persona no está activa en la empresa'], 400);
            }
            $nombre = $person->nombre_p . ' ' . $person->apellido;

            $user = $this->userService->createRegisterUserApi($dni, $person->nombre_p, $person->apellido, $email, $password);

            if ($user != null) {
                try {
                    Mail::to($email)->send(new VerificationEmail($nombre, $user->remember_token, $imagePath2));
                } catch (Exception $e) {
                    Log::error('Error al enviar mail: ' . $e->getMessage());
                    $user->delete(); // Eliminar el usuario si falla el envío del correo
                    return response()->json(['message' => 'Error, no se pudo enviar el mail. Por favor cree el usuario nuevamente'], 400);
                }

                return response()->json(['message' => 'Usuario creado exitosamente! Se enviará un correo de verificación.'], 200);
            } else {
                return response()->json(['message' => 'La persona ya tiene usuario registrado'], 400);
            }

        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating user: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear el usuario'], 500);
        }

    }


}






