<?php

namespace App\Exports;

use App\Models\EnrolamientoCurso;
use App\Models\CursoInstancia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InscriptosExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $curso;
    protected $instancia;

    public function __construct($curso, $instancia)
    {
        $this->curso = $curso;
        $this->instancia = $instancia;
    }
    public function collection()
    {
        
        return EnrolamientoCurso::with('persona') 
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
    }

    public function headings(): array
    {
        return [
            'Legajo',
            'Nombre y Apellido',
            'Fecha de Inscripción',
            'Versión de Instancia',
            'Evaluación',
        ];
    }
}
