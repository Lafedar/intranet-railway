<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\PolicyService;
use Session;
use App\Models\Policy;

class PolicyController extends Controller
{
    protected $policyService;

    public function __construct(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    public function list_all_Policy(Request $request)
    {

        $policies = $this->policyService->get_paginated_policies();

        return view('policy.index', array(
            'policies' => $policies,
        ));
    }

    public function store_policy(Request $request)
    {

        $message = $this->policyService->storePolicy($request);

        if ($message) {
            $message = 'Política creada correctamente';
            $alertClass = 'alert-success';
        } else {
            $message = 'Error al crear la política';
            $alertClass = 'alert-error';

        }
        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect('policy');
    }

    public function destroy_policy($id)
    {
        $message = $this->policyService->destroyPolicy($id);
        if ($message) {
            $message = 'Política eliminada correctamente';
            $alertClass = 'alert-success';
        } else {
            $message = 'Error al eliminar la política';
            $alertClass = 'alert-error';
        }
        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect('policy');
    }

    public function update_policy(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $date = $request->input('date');
        $pdf = $request->file('pdf');

        $message = $this->policyService->updatePolicy($id, $title, $date, $pdf);
        if ($message) {
            $message = 'Política actualizada correctamente';
            $alertClass = 'alert-success';
        } else {
            $message = 'Error al actualizar la política';
            $alertClass = 'alert-error';
        }
        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect('policy');
    }



}
