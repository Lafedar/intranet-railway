<?php

namespace App\Http\Controllers;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Log;
use Exception;





class ExcelController extends Controller
{
    protected $excelService;

    public function __construct(
        ExcelService $excelService
    ) {

        $this->excelService = $excelService;
    }
    public function inscribirDesdeExcel(Request $request, int $instancia_id, int $cursoId)
    {
        try {
            // Validación del archivo
            if (!$request->hasFile('excel_file')) {
                return redirect()->back()->with('error', 'No se cargó ningún archivo Excel.');
            }

            // Llamamos al servicio que maneja toda la lógica
            $resultado = $this->excelService->inscribirDesdeExcel($request, $instancia_id, $cursoId);

            // Manejo de los resultados
            if (isset($resultado['error'])) {
                return redirect()->back()->with('error', $resultado['error']);
            }

            if (isset($resultado['archivo_descargable'])) {
                // Generamos la URL pública del archivo
                // El archivo ya debe estar en storage/app/public
                $archivoUrl = 'storage/' . $resultado['archivo_descargable'];  // No es necesario usar asset()

                // Guardamos la URL en la sesión
                session(['archivo_descargable' => $archivoUrl]);

                // Redirigimos con un mensaje de éxito
                return redirect()->back()->with('success', 'Las personas se han inscripto correctamente.');
            }

            session(['inscripcion_desde_excel' => true]);
            return redirect()->back()->with('success', $resultado['success']);

        } catch (Exception $e) {
            Log::error('Error al inscribir personas desde Excel: ' . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un problema al procesar el archivo Excel.');
        }
    }



}
