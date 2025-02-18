<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NovedadService;

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
    protected $novedadService;

    public function __construct(NovedadService $novedadService)  /*inyecto dependencias*/
    {
        $this->middleware('guest')->except('logout');
        $this->novedadService = $novedadService;

    }



    protected $redirectTo = '/';


    protected function redirectTo()
    {
        return '/';
    }



    // Método para mostrar el formulario de inicio de sesión
    public function showLoginForm()
    {
        $novedades = $this->novedadService->getUltimasNovedades();

        return view('home.inicio', compact('novedades'));
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
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Verificar si la columna 'activo' del usuario es 1
            if ($user->activo != 1) {
                // Cerrar sesión si el usuario no está activo
                Auth::logout();

                // Redirigir de vuelta con un mensaje de error
                return redirect()->route('login')->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta con el administrador.',
                ]);
            }

            // Autenticación exitosa y usuario activo, redirigir a la página de inicio
            return redirect()->intended('/')->with('success', 'Inicio de sesión exitoso.');
        }

        // Si las credenciales son incorrectas, redirigir de vuelta con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales son incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cerrar sesión
        $request->session()->invalidate(); // Invalidar la sesión
        $request->session()->regenerateToken(); // Regenerar el token CSRF

        // Redirigir al inicio de sesión o a la página de inicio
        return redirect('/login');
    }

}
