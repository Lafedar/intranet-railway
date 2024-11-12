<?php

namespace App\Services;

use App\Models\CursoInstancia;
use App\Models\EnrolamientoCurso;
use App\Services\CursoInstanciaService;
use App\Services\PersonaService;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;
use App\Models\Curso; 

class EnrolamientoCursoService
{
    private $cursoInstanciaService;
    private $personaService;


    public function __construct(CursoInstanciaService $cursoInstanciaService, PersonaService $personaService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
        $this->personaService = $personaService;

    }

    public function getAll(): Collection
    {
        return EnrolamientoCurso::all();
    }

    public function getById(int $id): ?EnrolamientoCurso
    {
        return EnrolamientoCurso::find($id);
    }

    public function update(EnrolamientoCurso $enrolamiento, array $data)
    {

        return $enrolamiento->update($data);
    }

    public function delete(EnrolamientoCurso $enrolamiento): ?bool
    {
        return $enrolamiento->delete();
    }

    public function isEnrolled($userDni, $instanciaId): ?bool
    {
       
        $person = Persona::where('dni', $userDni)->first();
        return EnrolamientoCurso::where('id_persona', $person->id_p)
            ->where('id_instancia', $instanciaId)
            ->exists();
    }
    public function isEnrolled2($id_persona): ?bool
    {
        return EnrolamientoCurso::where('id_persona', $id_persona)
            ->exists();
    }
    public function enroll($userDni, $instanceId, $cursoId): ?EnrolamientoCurso
    {
        $courseEnrollment = null;
       
        $person = Persona::where('dni', $userDni)->first();
        
        if ($this->cursoInstanciaService->checkInstanceQuota($cursoId, $instanceId) - $this->getCountPersonsByInstanceId($instanceId, $cursoId) > 0) {
            $data = [
                'id_persona' => $person->id_p,
                'id_instancia' => $instanceId,
                'id_curso' => $cursoId,
                'fecha_enrolamiento' => Carbon::now(),
                'estado' => 'Alta',
                'evaluacion' => 'No Aprobado',
            ];
            $courseEnrollment = EnrolamientoCurso::create($data);
        } else Log::alert('Alert in class: ' . get_class($this) .'.No hay cupo para el id_curso: ' . $cursoId . ' y la instancia id: ' . $instanceId );
        return $courseEnrollment;
    }



    public function getCoursesByUserId(int $userId): Collection  //obtengo los cursos de una persona
    {
        return EnrolamientoCurso::where('id_persona', $userId)
            ->with('curso') 
            ->get(['id_curso', 'evaluacion']); 
    }

    public function getPersonsByCourseId(int $cursoId): Collection  //obtengo las personas enroladas en un curso
    {
        return EnrolamientoCurso::where('id_curso', $cursoId)
            ->with('persona') 
            ->get(); 

    }
    public function getCountPersonas(int $cursoId){
        return EnrolamientoCurso::with('persona') 
        ->where('id_curso', $cursoId)
        ->count();
    }


    public function getPersonsByInstanceId(int $instanceId, int $cursoId)
    {
        return EnrolamientoCurso::with('persona') 
            ->where('id_curso', $cursoId)
            ->where('id_instancia', $instanceId)
            ->get();
            
    }
    public function getCountPersonsByInstanceId(int $instanceId, int $cursoId)
    {
        return EnrolamientoCurso::with('persona') 
            ->where('id_curso', $cursoId)
            ->where('id_instancia', $instanceId)
            ->count();
            
    }

    

    public function deleteByInstanceId(int $idCurso, int $idInstancia)
    {
        return EnrolamientoCurso::where('id_instancia', $idInstancia)
                                ->where('id_curso', $idCurso)
                                ->delete();
    }

    

    public function getAllNonEnrolledCourses (int $idPerson) :?Collection
    {
        $cursos= DB::table ('cursos as c')
            ->select ('c.id', 'c.codigo', 'c.titulo')
            ->leftJoinSub (
                DB::table ('enrolamiento_cursos')
                ->select ('id_curso')
                ->distinct()
                ->where('id_persona', $idPerson),
                'ec',
                'c.id',
                '=',
                'ec.id_curso'
            ) 
            ->whereNull ('ec.id_curso')
            ->get();
        
        $result= $cursos->map (function ($curso) {
            return [
                'id_course'=>$curso->id,
                'id_code'=>$curso->codigo,
                'title'=>$curso->titulo,
                'state'=>'No realizado' 
            ];
        });
        return $result;
    }
    
    
    public function getAllEnrolledCourses(int $idPerson): ?Collection
    {
        $persona = Persona::find($idPerson);

        if (!$persona) {
            return null; 
        }
        $cursos = $persona->cursos;  

        return $cursos;
    }



    public function getAllCourses (int $idPerson) :?Collection{

        $enrolled = $this->getAllEnrolledCourses($idPerson);
        $notEnrolled= $this->getAllNonEnrolledCourses($idPerson);
        $allCourses = $enrolled->merge($notEnrolled);
        $result = $allCourses->sortBy('title')->values();
        return $result;
    
    }

    public function unEnroll($userId, $instanceId, $cursoId): ?bool
    {

        $person = $this->personaService->getById($userId);
    
        if (!$person) {
            Log::error('Error: Persona no encontrada para el ID: ' . $userId);
            return false;
        }
       
        $enrollment = EnrolamientoCurso::where('id_persona', $person->id_p)
                                        ->where('id_instancia', $instanceId)
                                        ->where('id_curso', $cursoId)
                                        ->first();
        
        if (!$enrollment) {
            Log::error('Error: No se encontró enrolamiento para la persona con DNI: ' . $userId . 
                    ' en la instancia id: ' . $instanceId . ' y curso id: ' . $cursoId);
            return false;
        }
        
        $enrollment->delete();
        return true;
    }
    public function evaluarInstancia(int $userId, int $instanciaId, int $cursoId, int $bandera)
    {
        try {
            
            $enrolamiento = DB::table('enrolamiento_cursos')
                ->where('id_persona', $userId)
                ->where('id_curso', $cursoId)
                ->where('id_instancia', $instanciaId)
                ->first();
    
           
            if (!$enrolamiento) {
                return response()->json(['error' => 'El registro de enrolamiento no existe.'], 404);
            }
   
            if($bandera == 0){
                    DB::table('enrolamiento_cursos')
                    ->where('id_persona', $userId)
                    ->where('id_curso', $cursoId)
                    ->where('id_instancia', $instanciaId)
                    ->update(['evaluacion' => 'Aprobado']);
        
                return response()->json(['success' => 'Curso aprobado correctamente.']);
    
            }else{
                    DB::table('enrolamiento_cursos')
                    ->where('id_persona', $userId)
                    ->where('id_curso', $cursoId)
                    ->where('id_instancia', $instanciaId)
                    ->update(['evaluacion' => 'No Aprobado']);
        
                return response()->json(['success' => 'Curso desaprobado correctamente.']);
        
            }
            
        } catch (\Exception $e) {
            
            return response()->json(['error' => 'Ocurrió un error al cambiar la evaluacion del curso: ' . $e->getMessage()], 500);
        }
    }
    
}
