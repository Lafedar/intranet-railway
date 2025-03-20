<?php

namespace App\Services;

use App\Models\Anexo;
use Exception;
use Log;
use Illuminate\Database\Eloquent\Collection;
use DB;

class AnnexedService
{

    public function insert_annexed_course_instance($courseId, $nextInstanceId, $annexedId, $annexedType){
        DB::table('relacion_curso_instancia_anexo')->insert([
            'id_curso' => $courseId,
            'id_instancia' => $nextInstanceId,
            'formulario_id' => $annexedId,
            'tipo' => $annexedType, 
        ]);
    }
    public function delete_annexed_course_instance($instanceId, $courseId){
        DB::table('relacion_curso_instancia_anexo')
                ->where('id_instancia', $instanceId)
                ->where('id_curso', $courseId)
                ->delete();
    }

    public function getById($form_id){
        return DB::table('anexos')
            ->where('formulario_id', $form_id)
            ->value('tipo');
    }
    public function getAll()
    {
        return Anexo::all();
    }

    public function deleteAnnexCourseInstanceByCourseId(int $id){
        DB::table('relacion_curso_instancia_anexo')
        ->where('id_curso', $id)
        ->delete();
    }
    
}
