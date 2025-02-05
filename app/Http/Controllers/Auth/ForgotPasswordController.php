<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails; // Incluir el trait para manejar el envío de correos

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Sobrescribe el método para incluir validación personalizada del dominio del correo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validar el correo electrónico
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtener el correo
        $email = $request->input('email');

        // Validar que el correo termine exactamente con '@lafedar.com'
        if (!preg_match('/@lafedar\.com$/', $email)) {
            // Si el correo no termina con '@lafedar.com', mostrar un mensaje de error
            return back()->withErrors(['email' => 'Contactarse con Sistemas para restablecer la contraseña.']);
        }

        // Si la validación es exitosa, proceder a enviar el enlace de restablecimiento de contraseña
        // Llamamos directamente al método del trait para enviar el correo
        $response = Password::sendResetLink($request->only('email'));

        // Revisamos la respuesta para determinar si el envío fue exitoso o no
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Se ha enviado el enlace de restablecimiento de contraseña a su correo electrónico.');
        } else {
            return back()->withErrors(['email' => 'Hubo un problema al intentar enviar el correo de restablecimiento.']);
        }
    }
}

