<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Permiso;
use App\Empleado;
use App\User;
use Auth;
use DB;
use Session;
use App\Models\MiAgenda;
use App\Empresa;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        $personas = MiAgenda::orderBy('nombre', 'asc')
            ->Empresa($request->get('empresa'))
            ->Nombre($request->get('nombre'))
            ->paginate(50);

        return view('persona.index', array('personas' => $personas, 'nombre' => $request->get('nombre'), 'empresa' => $request->get('empresa')));
    }

    public function create()
    {

        return view('persona.create');
    }

    public function store(Request $request)
    {
        $creador = auth()->user()->id;

        $personas = new Persona;
        $personas->nombre = $request['nombre'];
        $personas->apellido = $request['apellido'];
        $personas->direccion = $request['direccion'];
        $personas->empresa = $request['empresa'];
        $personas->interno = $request['interno'];
        $personas->telefono = $request['telefono'];
        $personas->celular = $request['celular'];
        $personas->correo = $request['correo'];
        $personas->creador = $creador;
        $personas->save();
        Session::flash('message', 'Contacto agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('persona');
    }

    public function show($id)
    {
        //        
    }

    public function edit($id)
    {
        $personas = Persona::find($id);
        return view('persona.edit', ['persona' => $personas]);
    }

    public function update(Request $request, $id)
    {
        $modificador = auth()->user()->id;

        $personas = Persona::find($request['id']);

        $personas->nombre = $request['nombre'];
        $personas->apellido = $request['apellido'];
        $personas->direccion = $request['direccion'];
        $personas->empresa = $request['empresa'];
        $personas->interno = $request['interno'];
        $personas->telefono = $request['telefono'];
        $personas->celular = $request['celular'];
        $personas->correo = $request['correo'];
        $personas->modificador = $modificador;
        $personas->save();

        Session::flash('message', 'Contacto modificado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('persona');
    }

    public function destroy($request, $id)
    {
    }

    public function destroy_contacto(Request $request, $id)
    {
        $personas = Persona::find($id);
        $personas->delete();

        return response()->json([
            'message' => 'Contacto eliminado con éxito'
        ]);

    }
    public function buscar(Request $request)
    {
        try {
            Log::info('Solicitud de búsqueda recibida: ' . $request->session()->getId());

            $ciphertextBase64 = $request->input('ciphertext');
            $ivBase64 = $request->input('iv');

            if (!$ciphertextBase64 || !$ivBase64) {
                return response()->json(['message' => 'Faltan datos encriptados'], 400);
            }

            $ciphertext = base64_decode($ciphertextBase64);
            $iv = base64_decode($ivBase64);

            $aesKeyBase64 = $request->session()->get('aes_key');
            if (!$aesKeyBase64) {
                return response()->json(['message' => 'Clave AES no encontrada en la sesión'], 400);
            }
            $aesKey = base64_decode($aesKeyBase64);

            $tagLength = 16;
            if (strlen($ciphertext) < $tagLength) {
                return response()->json(['message' => 'Datos encriptados inválidos'], 400);
            }

            $tag = substr($ciphertext, -$tagLength);
            $ciphertextRaw = substr($ciphertext, 0, -$tagLength);

            Log::info('Datos recibidos para desencriptar: ' . $ciphertextBase64 . ' con IV: ' . $ivBase64);

            $decrypted = openssl_decrypt(
                $ciphertextRaw,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );

            Log::info('Datos desencriptados: ' . $decrypted);

            if ($decrypted === false) {
                return response()->json(['message' => 'Error al desencriptar'], 400);
            }

            $data = json_decode($decrypted, true);
            \Log::info("datos recibidos: " . json_encode($data))    ;

            $dni = $data['data']['dni'];

            \Log::info("DNI recibido: " . $dni);

            $persona = Persona::where('dni', $dni)->first();

            if (!$persona) {
                return response()->json(['message' => 'Persona no encontrada'], 404);
            }

            // Crear y cifrar respuesta
            $responseData = [
                'nombre_p' => $persona->nombre_p,
                'apellido' => $persona->apellido,
            ];

            $jsonResponse = json_encode($responseData);

            $newIv = random_bytes(12);
            $tagOut = '';
            $ciphertextOut = openssl_encrypt(
                $jsonResponse,
                'aes-256-gcm',
                $aesKey,
                OPENSSL_RAW_DATA,
                $newIv,
                $tagOut
            );

            return response()->json([
                'ciphertext' => base64_encode($ciphertextOut . $tagOut),
                'iv' => base64_encode($newIv),
            ], 200);
        }catch(Exception $e) {
            Log::error('Error en la búsqueda: ' . $e->getMessage());
            return response()->json(['message' => 'Error en la búsqueda'], 500);
        }

    }


    public function checkMail(Request $request)
    {
        $mail = $request->input('mail');

        $bandera = DB::table('personas')->where('correo', $mail)->exists();

        if ($bandera) {
            return response()->json($bandera);
        } else {
            return response()->json(['error' => 'Persona no encontrada'], 404);
        }
    }

}
