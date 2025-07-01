<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicationsRequestController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CryptoController;
use Illuminate\Support\Facades\Http;
use App\Http\Middleware\ForceCors;
use App\Http\Controllers\MedicalCertificateController;
use Illuminate\Support\Facades\Log;
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


//Route::options('{any}', function (Request $request) {
  //  $origin = $request->headers->get('Origin');
  //  Log::info('OPTIONS global ejecutado con origen: ' . ($origin ?? 'NULO'));

  //  return response()->json([], 204);
//})->where('any', '.*')->middleware('force.cors');

// Ruta de “prueba de deploy”
Route::get('/debug-confirm', function () {
    Log::info('Ejecutando /debug-confirm desde limpiar-proyecto-api');
    return response()->json([
        'message' => 'Código correcto desde limpiar-proyecto-api',
    ]);
});

Route::get('/debug-path', function () {
    return response()->json([
        'base_path'    => base_path(),             // Carpeta raíz de Laravel
        'api_filepath' => realpath(__FILE__),      // Ruta absoluta de este mismo archivo
        'last_line'    => trim(last(file(__FILE__))),
    ]);
})

Route::options('/test-cors', function () {
    return response()->json(['ok' => true])
        ->header('Access-Control-Allow-Origin', '*')
        ->header('X-Debug-Cors', 'PRUEBA-CORS');
});

//Route::options('/get-key', function () {
//    return response('', 204);
//})->middleware('force.cors');

Route::middleware(['force.cors'])->group(function () {
    Route::get('/get-key', [CryptoController::class, 'getEncryptionKey']);
});


Route::middleware(['aes.key', 'force.cors'])->group(function () {
    Route::post('/loginApi', [CryptoController::class, 'loginApi']);
    Route::post('/buscarPersona', [PersonaController::class, 'buscar']);
    Route::post('/medications', [MedicationsRequestController::class, 'saveNewMedicationRequest']);
    Route::post('/medicationsRequests', [MedicationsRequestController::class, 'getAllMedicationRequestAndItemsByUserDni']);
    Route::post('/createUser', [MedicationsRequestController::class, 'createUserApi']);
    Route::post('/generateNewVerificationEmail', [MedicationsRequestController::class, 'generateNewVerificationEmail']);
    Route::post('/sendMailResetPassword', [MedicationsRequestController::class, 'sendMailResetPassword']);
    Route::post('/resetPassword', [MedicationsRequestController::class, 'resetPassword']);
    Route::post('/cleanTokens', [MedicationsRequestController::class, 'cleanTokens']);
    Route::post('/medicalCertificate', [MedicalCertificateController::class, 'store']);
    

    // CORS preflight solo para estas rutas
    Route::options('/{any}', function () {
        return response('', 204);
    })->where('any', '.*');
});