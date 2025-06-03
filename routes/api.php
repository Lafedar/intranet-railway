<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicationsRequestController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CryptoController;
use Illuminate\Support\Facades\Http;
use App\Http\Middleware\ForceCors;
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






Route::middleware(['force.cors'])->group(function () {
    Route::get('/get-key', [CryptoController::class, 'getEncryptionKey']);
});

Route::middleware(['aes.key', 'force.cors'])->group(function () {
    Route::post('/login', [CryptoController::class, 'login']);
    Route::post('/buscarPersona', [PersonaController::class, 'buscar']);
    Route::post('/medications', [MedicationsRequestController::class, 'saveNewMedicationRequest']);

    // CORS preflight solo para estas rutas
    Route::options('/{any}', function () {
        return response('', 204);
    })->where('any', '.*');
});


