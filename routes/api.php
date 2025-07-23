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
use App\Http\Controllers\SynchronizationController;
use App\Http\Controllers\UserController;

Route::middleware(['force.cors'])->group(function () {
    Route::get('/get-key', [CryptoController::class, 'getEncryptionKey']);
    Route::post('/refresh-token', [CryptoController::class, 'refreshToken']);
});
Route::middleware(['aes.key', 'force.cors'])->group(function () {
    Route::post('/loginApi', [CryptoController::class, 'loginApi']);

});



Route::middleware(['aes.key', 'jwt', 'force.cors'])->group(function () {

    Route::post('/buscarPersona', [PersonaController::class, 'buscar']);
    Route::post('/medications', [MedicationsRequestController::class, 'saveNewMedicationRequest']);
    Route::post('/medicationsRequests', [MedicationsRequestController::class, 'getAllMedicationRequestAndItemsByUserDni']);
    Route::post('/createUser', [UserController::class, 'createUser']);
    Route::post('/generateNewVerificationEmail', [AuthController::class, 'generateNewVerificationEmail']);
    Route::post('/sendMailResetPassword', [AuthController::class, 'sendMailResetPassword']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/cleanTokens', [AuthController::class, 'cleanTokens']);
    Route::post('/medicalCertificate', [MedicalCertificateController::class, 'store']);


    // CORS preflight solo para estas rutas
    Route::options('/{any}', function () {
        return response('', 204);
    })->where('any', '.*');
});



//RUTAS PARA SINCRONIZAR DESDE INTRANET
Route::post('/get-key-api-extranet', [SynchronizationController::class, 'getKey']);
Route::post('/createPerson', [SynchronizationController::class, 'createPersonFromIntranet']);
Route::post('/updatePerson', [SynchronizationController::class, 'updatePersonFromIntranet']);
Route::post('/updateMedicationRequest', [SynchronizationController::class, 'updateMedicationRequestFromIntranet']);
Route::post('/destroyPerson', [SynchronizationController::class, 'destroyPerson']);
Route::post('/syncPassword', [SynchronizationController::class, 'syncPasswordFromIntranet']);
