<?php

namespace App\Services;


use Illuminate\Support\Facades\Storage;
use DB;
use Exception;
use Log;
use App\Models\PublicDocumentation;


class PublicDocumentationService
{
    public function store_public_documentation($request)
    {
        try {
            $aux = PublicDocumentation::get()->max('id');
            if ($aux == null) {
                $aux = 0;
            }

            $document = new PublicDocumentation;
            $document->titulo = $request->input('title');
            $document->fecha = $request->input('date');

            if ($request->file('pdf')) {
                $name = str_pad($aux + 1, 5, '0', STR_PAD_LEFT) . $request->file('pdf')->getClientOriginalName();
                Storage::disk('public')->put('documentacion_publica/' . $name, \File::get($request->file('pdf')));
                $request->file('pdf')->move(public_path('storage/documentacion_publica'), $name);
                $document->pdf = 'documentacion_publica\\' . $name;
            }

            $document->save();

            return true;
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error creating public documentation' . $e->getMessage());
            return false;

        }

    }

    public function destroy_documentation(int $id)
    {
        try {
            $document = PublicDocumentation::find($id);

            if ($document && $document->pdf) {
                $pdfPath = 'public/' . $document->pdf;
                if (Storage::exists($pdfPath)) {
                    Storage::delete($pdfPath);
                }

             
                $filePath = public_path('storage/documentacion_publica/' . basename($document->pdf));
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            if ($document) {
                $document->delete();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error deleting document ' . $e->getMessage());
            return false;
        }

    }

    public function update_documentation(int $id, string $title, $date, $pdf)
    {
        try {
            $updateData = [];

            if ($title) {
                $updateData['titulo'] = $title;
            }

            if ($date) {
                $updateData['fecha'] = $date;
            }

            
            if (!empty($updateData)) {
                DB::table('documentacion_publica')->where('id', $id)->update($updateData);
            }

            
            if (!$pdf) {
                return !empty($updateData);
            }

           
            $document = PublicDocumentation::find($id);

            if ($document && $document->pdf) {
                $pdfPath = 'public/' . $document->pdf;
                if (Storage::exists($pdfPath)) {
                    Storage::delete($pdfPath);
                }

                
                $filePath = public_path('storage/documentacion_publica/' . basename($document->pdf));
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
           
            $name = str_pad($id, 5, '0', STR_PAD_LEFT) . $pdf->getClientOriginalName();
            Storage::disk('public')->put('documentacion_publica/' . $name, \File::get($pdf));
            $pdf->move(public_path('storage/documentacion_publica'), $name);


            
            $document = PublicDocumentation::find($id);

            if ($document) {
                $document->pdf = 'documentacion_publica/' . $name;
                $document->save();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error modifying the document' . $e->getMessage());
            return false;
        }
    }




    public function get_public_documentation()
    {
        try {
            return PublicDocumentation::query();
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error getting public documentation' . $e->getMessage());
            return null;
        }

    }
}
