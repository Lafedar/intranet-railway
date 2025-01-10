<?php

namespace App\Exports;

use App\Models\EnrolamientoCurso;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Services\CursoInstanciaService;

class CursosExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $persona;

    public function __construct($persona)
    {
        $this->persona = $persona;
    }

    public function collection()
    {
        // Obtener los cursos de la persona con los detalles de las instancias
        $cursos = EnrolamientoCurso::with(['curso', 'curso.instancias']) // Cargar instancias del curso
            ->where('id_persona', $this->persona->id_p)
            ->get()
            ->map(function($enrolamiento) {
                // Obtener la instancia del curso asociada
                $cursoInstancia = $enrolamiento->curso->instancias->first(); // Suponiendo que tomas la primera instancia disponible

                return [
                    $enrolamiento->curso->titulo, // Nombre del curso
                    \Carbon\Carbon::parse($cursoInstancia->fecha_inicio)->format('d/m/Y'), // Fecha de inicio de la instancia
                    $cursoInstancia->capacitador ?? 'N/A', // Capacitador
                    $cursoInstancia->modalidad ?? 'N/A', // Modalidad
                    $enrolamiento->curso->tipo, // Tipo de curso
                    $enrolamiento->evaluacion ?? 'N/A', // Evaluación
                    $cursoInstancia->obligatorio == 1 ? 'Sí' : 'No', // Obligatorio
                ];
            });

        // Datos adicionales antes de la tabla
        $datosAdicionales = [
            ['Capacitaciones de: ' . $this->persona->nombre_p . ' ' . $this->persona->apellido], // Fila con el nombre de la persona
            [], // Fila vacía para separación
        ];
        $filaVacia = collect([['']]);
        // Crear encabezados de la tabla
        $encabezadoTabla = [
            ['Capacitación', 'Fecha de Inicio', 'Capacitador', 'Modalidad', 'Tipo', 'Evaluación', 'Obligatorio'],
        ];

        // Combinar los datos adicionales, la fila vacía y la tabla de cursos con encabezado
        return collect($datosAdicionales)->merge($filaVacia)->merge($encabezadoTabla)->merge($cursos);
    }

    // Método para definir los encabezados de la tabla
    public function headings(): array
    {
        // El encabezado ya está gestionado dentro del método collection, así que lo dejamos vacío aquí.
        return [];
    }
}
