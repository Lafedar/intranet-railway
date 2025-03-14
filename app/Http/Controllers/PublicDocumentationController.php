<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PublicDocumentationService;
use Session;


class PublicDocumentationController extends Controller
{
    protected $publicDocumentationService;

    public function __construct(PublicDocumentationService $publicDocumentationService)
    {
        $this->publicDocumentationService = $publicDocumentationService;
    }
    public function list_all_documentation(Request $request)
    {
        $documentsQuery = $this->publicDocumentationService->get_all();

        $documents = $documentsQuery->paginate(10)->withQueryString();

        return view('public_documentation.index', array(
            'documentation' =>  $documents,
        ));
    }

    public function store_public_documentation(Request $request)
    {

        $message = $this->publicDocumentationService->store($request);

        if ($message) {
            $message = 'Documentacion creada correctamente';
            $alertClass = 'alert-success';
        } else {
            $message = 'Error al crear la documentacion';
            $alertClass = 'alert-error';

        }
        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();

    }

    public function destroy_public_documentation($id)
    {
        $message = $this->publicDocumentationService->destroy($id);
        if ($message) {
            $message = 'Documento eliminado correctamente';
            $alertClass = 'alert-success';
        } else {
            $message = 'Error al eliminar el documento';
            $alertClass = 'alert-error';
        }
        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();

    }

    public function update_public_documentation(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $date = $request->input('date');
        $pdf = $request->file('pdf');

        $message = $this->publicDocumentationService->update($id, $title, $date, $pdf);
        if ($message) {
            $message = 'Documento actualizado correctamente';
            $alertClass = 'alert-success';
        } else {
            $message = 'Error al actualizar el documento';
            $alertClass = 'alert-error';
        }
        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();
    }



}
