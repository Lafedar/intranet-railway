<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Politica;
use Session;
use DB;


class PoliticaController extends Controller
{
    public function index(Request $request)
    {
        $politicas = Politica::ID($request->get('id_politica'))
            ->Titulo($request->get('titulo_politica'))
            ->Fecha($request->get('fecha_politica'))
            ->paginate(20);

        return view('politicas.index', array(
            'politicas' => $politicas,
            'id_politica' => $request->get('id_politica'),
            'titulo_politica' => $request->get('titulo_politica'),
            'fecha_politica' =>
                $request->get('fecha_politica')
        ));
    }

    public function store_politica(Request $request)
    {
        $aux = Politica::get()->max('id');
        if ($aux == null) {
            $aux = 0;
        }

        $politica = new Politica;
        $politica->titulo = $request['titulo'];
        $politica->fecha = $request['fecha'];

        if ($request->file('pdf')) {
            $file = $request->file('pdf');
            $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();
            Storage::disk('public')->put('politica/' . $name, \File::get($file));
            $politica->pdf = 'politica\\' . $name;
        }

        $politica->save();

        Session::flash('message', 'Archivo agregado con éxito');
        Session::flash('alert-class', 'alert-success');
        return redirect('politicas');
    }

    public function destroy_politica($id)
    {
        // Encontrar la política
        $politica = Politica::find($id);

        // Verificar si existe el archivo PDF asociado y eliminarlo
        if ($politica && $politica->pdf) {
            // Verificar si el archivo realmente existe antes de eliminarlo
            $pdfPath = 'public/' . $politica->pdf;
            if (Storage::exists($pdfPath)) {
                Storage::delete($pdfPath);
            }
        }

        // Eliminar la política
        if ($politica) {
            $politica->delete();
            Session::flash('message', 'Archivo eliminado con éxito');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Política no encontrada');
            Session::flash('alert-class', 'alert-danger');
        }

        // Redirigir a la lista de políticas
        return redirect('politicas');
    }


    public function update_politica(Request $request)
    {
        // Validación de entrada
        $validated = $request->validate([
            'id' => 'required|exists:politica,id',
            'titulo' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // Asegúrate de que el archivo sea un PDF
        ]);

        // Actualización de datos principales (titulo, fecha)
        $updateData = [];
        if ($request->has('titulo')) {
            $updateData['titulo'] = $request['titulo'];
        }
        if ($request->has('fecha')) {
            $updateData['fecha'] = $request['fecha'];
        }

        if (!empty($updateData)) {
            DB::table('politica')->where('id', $request['id'])->update($updateData);
        }

        // Si hay un archivo PDF
        if ($request->hasFile('pdf') && $request->file('pdf') != null) {
            $aux = Politica::find($request['id']);

            // Eliminar el archivo antiguo si existe
            if ($aux->pdf && Storage::exists('public/' . $aux->pdf)) {
                Storage::delete('public/' . $aux->pdf);
            }

            // Subir el nuevo archivo PDF
            $file = $request->file('pdf');
            $name = str_pad($request['id'], 5, '0', STR_PAD_LEFT) . $file->getClientOriginalName();

            // Guardar el archivo en el almacenamiento público
            Storage::disk('public')->put('politica/' . $name, \File::get($file));

            // Actualizar el campo 'pdf' en la base de datos
            DB::table('politica')
                ->where('id', $request['id'])
                ->update(['pdf' => 'politica/' . $name]);
        }

        // Mensaje de éxito
        Session::flash('message', 'Archivo modificado con éxito');
        Session::flash('alert-class', 'alert-success');

        return redirect('politicas');
    }

}