<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function redirectTo()
    {
        return '/';
    }

    use AuthenticatesUsers;

    // Método para mostrar el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Método para manejar la autenticación
    public function login(Request $request)
{
    // Validar las credenciales
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Intentar autenticar al usuario
    if (Auth::attempt($request->only('email', 'password'))) {
        // Autenticación exitosa, redirigir a la página de inicio
        return redirect('/')->with('success', 'Inicio de sesión exitoso.');
    }

    // Si las credenciales son incorrectas, redirigir de vuelta con un mensaje de error
    return back()->withErrors([
        'email' => 'Las credenciales son incorrectas.',
    ]);
}
    // Método para manejar el cierre de sesión
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
