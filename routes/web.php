<?php
use App\Http\Controllers\AuthController;

Route::get('/verificar/{token}', [AuthController::class, 'verificarEmail']);
Route::get('/redirectToResetPassword/{token}', [AuthController::class, 'redirectToResetPassword']);
