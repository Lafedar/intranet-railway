<?php

namespace App\Http\Controllers;

use App\Services\MedicationsRequestService;

use Illuminate\Support\Facades\Session; 
use Exception;

class MedicationsRequestController extends Controller
{
    protected $medicationsRequestService;
    

    public function __construct(MedicationsRequestService $medicationsRequestService){
        $this->medicationsRequestService = $medicationsRequestService;
    }

    public function listsMedicationRequests(){
        try {
            if (auth()->user()->hasRole(['administrador'])) {
                $medicationsRequests = $this->medicationsRequestService->getAll();
                return view('medications.index', ['medicationsRequests' => $medicationsRequests]);
            } else {
                $medicationsRequests = $this->medicationsRequestService->getRequestsByDni(auth()->user()->dni);
                return view('medications.index', ['medicationsRequests' => $medicationsRequests]);
            }
            
            
        } catch (Exception $e) {
            Session::flash('message', 'Error al obtener las solicitudes de medicamentos');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back(); 
        }
    }

    public function deleteMedicationRequest($id){
        try {
           $this->medicationsRequestService->deleteRequestById($id);
           return redirect()->back(); 
             
        } catch (Exception $e) {
            Session::flash('message', 'Error al eliminar la solicitud de medicamento');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back(); 
        }
    }

   
}
