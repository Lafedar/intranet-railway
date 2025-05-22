<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicationsRequestController;
use App\Http\Controllers\PersonaController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/save-data', [MedicationsRequestController::class, 'saveDataFromApi']);

Route::post('/recibir-form', function (Request $request) {
    // Validar los datos
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255',
    ]);

    // Opcional: podés guardar en base de datos, mandar mails, etc.

    // Ahora, mandar los datos a Make (por ejemplo un Webhook de Make)
    $webhookUrl = 'https://hook.us2.make.com/rk3431n6gfwlba1efxp3cw5iy6tca3k0'; // la URL de Make

    $response = Http::post($webhookUrl, [
        'nombre' => $validated['nombre'],
        'email' => $validated['email'],
    ]);

    // Devolver respuesta al navegador
    return response()->json(['status' => 'Formulario enviado correctamente']);
});

/*--------------------------------------------------------------------------------------------*/
Route::post('/login', function (Request $request) {
    try {
        $encrypted = $request->input('payload');

        // Clave y IV (deben coincidir con React)
        $key = 'clave_secreta_de_32_bytes_123456';
        $key = substr($key, 0, 32);

        $iv = 'vector_init_16byt';
        $iv = substr($iv, 0, 16);

        if (strlen($key) !== 32) {
            return response()->json(['message' => 'Clave inválida'], 400);
        }

        if (strlen($iv) !== 16) {
            return response()->json(['message' => 'IV inválido'], 400);
        }


        $decrypted = openssl_decrypt(
            base64_decode($encrypted),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $data = json_decode($decrypted, true);

        if (!$data || !isset($data['email']) || !isset($data['password'])) {
            return response()->json(['message' => 'Datos inválidos'], 400);
        }

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'message' => 'Login exitoso',
                'user' => $user,
                // 'token' => $user->createToken('api')->plainTextToken // si usás tokens
            ]);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);

    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Error del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
});




Route::post('/medications', [MedicationsRequestController::class, 'saveNewMedicationRequest']);

Route::post('/buscarPersona', [PersonaController::class, 'buscar']);
