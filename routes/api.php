<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicationsRequestController;
use App\Http\Controllers\PersonaController;
use Illuminate\Http\Response;

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



Route::options('/buscar-persona', function () {
    return response('', Response::HTTP_NO_CONTENT)    // 204 No Content
        ->header('Access-Control-Allow-Origin', 'https://lighthearted-stroopwafel-225cdc.netlify.app')
        ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type');
});

Route::post('/buscar-persona', [PersonaController::class, 'buscar']);