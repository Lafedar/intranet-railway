<?php

namespace App\Http\Controllers;

use App\Services\GeneralParametersService;
use Illuminate\Http\Request;
use DB;
use Exception;


class GeneralParametersController extends Controller
{
    private GeneralParametersService $genParameterService;

    public function __construct(GeneralParametersService $genParameterService)
    {
        $this->genParameterService = $genParameterService;

    }
    public function listAllParameters()
    {
        $parameters = $this->genParameterService->listAll();
        return view('parametros_gen.index', compact('parameters'));


    }
    public function listAllParametersToSystems()
    {
        $parameters = $this->genParameterService->listAll();
        return view('parametros_gen_sistemas.index', compact('parameters'));
    }

    public function saveNewParameter(Request $request)
    {
        $request->validate([
            'id_param' => 'required',
            'descripcion_param' => 'required',
            'valor_param' => 'required',
            'origen' => 'required',
        ]);

        $exist = DB::table('parametros_mant')->where('id_param', $request->id_param)->exists();

        if ($exist) {
            return redirect()->back()->with('error', 'El ID ya existe en la tabla. No se puede ingresar nuevamente.');
        } else {
            $store = $this->genParameterService->store($request->id_param, $request->descripcion_param, $request->valor_param, $request->origen);
            if($store){
                return redirect()->back()->with('success', 'Parámetro agregado correctamente.');
            }else{
                return redirect()->back()->with('error', 'Error al agregar el parámetro.');
            }

            
        }
    }

    public function updateParameterAndValidateEmails(Request $request, $id)
    {
        $request->validate([
            'descripcion_param' => 'required|string|max:255',
            'valor_param' => 'required|string|max:255',
        ]);
        try {

            if ($id == "PMAIL") {
                $mailExist = DB::table('personas')->where('correo', $request->valor_param)->exists();

                if (!$mailExist) {
                    return redirect()->back()->with(
                        'error',
                        'El correo no existe en la base de datos'
                    )->withInput();
                }
            }


            if ($id == "PMAILSMED") {
                $emails = array_filter(array_map('trim', explode(';', $request->valor_param)));
                $invalidMails = [];

                foreach ($emails as $email) {
                    $existe = DB::table('personas')->where('correo', $email)->exists();
                    if (!$existe) {
                        $invalidMails[] = $email;
                    }
                }

                if (!empty($invalidMails)) {
                    return redirect()->back()->with('error', 'Los siguientes correos no existen en la base de datos: ' . implode(', ', $invalidMails))->withInput();
                }

            }

            $update = $this->genParameterService->update($id, $request->descripcion_param, $request->valor_param);
            if ($update) {
                return redirect()->back()->with('success', 'Parámetro actualizado correctamente');
            } else {
                return redirect()->back()->with('error', 'Error al actualizar el parámetro');
            }



        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el parámetro: ' . $e->getMessage());
        }


    }

    public function deleteParameterById($id)
    {
        try {
            $delete = $this->genParameterService->delete($id);
            if($delete){
                return redirect()->back()->with('success', 'Parámetro eliminado correctamente');
            }else{
                return redirect()->back()->with('error', 'Error al eliminar el parámetro');
            }
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el parámetro en el controlador: ' . $e->getMessage());
        }
    }



    public function getMaximunSupportMegabytes()
    {
        try{
            $megabytesMax = $this->genParameterService->getMegabytesMax();
            if($megabytesMax ) {
                return redirect()->back()->with('success', 'Parámetro eliminado correctamente');
            }else{
                return redirect()->back()->with('error', 'Error al obtener los MB maximos');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al obtener los MB maximos en el controlador: ' . $e->getMessage());
        }
        

        
    }

}
