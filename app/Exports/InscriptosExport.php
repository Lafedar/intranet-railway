<?php

namespace App\Exports;

use App\Models\EnrolamientoCurso;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Services\CursoInstanciaService;

class InscriptosExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $curso;
    protected $instancia;
    private $cursoInstanciaService;

    public function __construct($curso, $instancia, CursoInstanciaService $cursoInstanciaService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->curso = $curso;
        $this->instancia = $this->cursoInstanciaService->getInstanceById($instancia, $curso->id);
    }

    public function collection()
    {
        // Obtener los inscritos (enrolamientos) de la instancia
        $inscriptos = EnrolamientoCurso::with('persona') 
            ->where('id_curso', $this->curso->id)
            ->where('id_instancia', $this->instancia->id_instancia)
            ->get()
            ->map(function($enrolamiento) {
                return [
                    $enrolamiento->persona->legajo,
                    $enrolamiento->persona->nombre_p . ' ' . $enrolamiento->persona->apellido,
                    $enrolamiento->fecha_enrolamiento ? $enrolamiento->fecha_enrolamiento : 'No disponible',
                    $this->instancia->version,
                    $enrolamiento->evaluacion,
                ];
            });

        // Crear los datos adicionales antes de la tabla
        $datosAdicionales = collect([
            ['Nombre del Curso', $this->curso->titulo], // Nombre del curso
            ['Fecha de Inicio', \Carbon\Carbon::parse($this->instancia->fecha_inicio)->format('d/m/Y')] // Fecha de inicio
        ]);

        // Agregar una fila vacía para separar los datos del curso de la tabla
        $filaVacia = collect([['']]); // Esta es la fila vacía

        // Crear cabezado de la tabla
        $encabezadoTabla = collect([
            ['Legajo', 'Nombre y Apellido', 'Fecha de Inscripción', 'Versión de Instancia', 'Evaluación']
        ]);

        // Combinar todos los datos y devolver
        return $datosAdicionales->merge($filaVacia)->merge($encabezadoTabla)->merge($inscriptos);
    }

    // Método para definir los encabezados de la tabla (ya está gestionado en el collection)
    public function headings(): array
    {
        return [];
    }
}
