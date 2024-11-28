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
        try{
            $person = Persona::where('dni', $userDni)->first();
            return EnrolamientoCurso::where('id_persona', $person->id_p)
                ->where('id_instancia', $instanciaId)
                ->exists();
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al verificar el enrolamiento' . $e->getMessage());
            throw $e;
        }
       
    }
    public function isEnrolled2($id_persona): ?bool
    {
        return EnrolamientoCurso::where('id_persona', $id_persona)
            ->exists();
    }
    public function enroll($userDni, $instanceId, $cursoId): ?EnrolamientoCurso
    {
        try{
            $courseEnrollment = null;
       
            $person = Persona::where('dni', $userDni)->first();
            
            if ($this->cursoInstanciaService->checkInstanceQuota($cursoId, $instanceId) - $this->getCountPersonsByInstanceId($instanceId, $cursoId) > 0) {
                $data = [
                    'id_persona' => $person->id_p,
                    'id_instancia' => $instanceId,
                    'id_curso' => $cursoId,
                    'fecha_enrolamiento' => Carbon::now(),
                    'estado' => 'Alta',
                    'evaluacion' => 'N/A',
                ];
                $courseEnrollment = EnrolamientoCurso::create($data);
            } else Log::alert('Alert in class: ' . get_class($this) .'.No hay cupo para el id_curso: ' . $cursoId . ' y la instancia id: ' . $instanceId );
            return $courseEnrollment;
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al crear el enrolamiento' . $e->getMessage());
            throw $e;
        }
        
    }

    public function getCoursesByUserId(int $userId): Collection  //obtengo los cursos de una persona
    {
        try{
            return EnrolamientoCurso::where('id_persona', $userId)
            ->with('curso') 
            ->get(['id_curso', 'evaluacion']); 
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener los cursos del usuario' . $e->getMessage());
            throw $e;
        }
        
    }

    public function getCursosByUserId(int $userId): \Illuminate\Database\Eloquent\Collection //envio los cursos con la relacion de area
    {
        try{
            return EnrolamientoCurso::where('id_persona', $userId)
            ->with('curso.areas')  
            ->get()  
            ->map(function ($enrolamiento) {
                return $enrolamiento->curso;  
            });
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener los cursos del usuario en getCursosByUserId' . $e->getMessage());
            throw $e;
        }
    }
    
    public function getPersonsByCourseId(int $cursoId): Collection  //obtengo las personas enroladas en un curso
    {
        try{
            return EnrolamientoCurso::where('id_curso', $cursoId)
            ->with('persona') 
            ->get(); 
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las personas del curso' . $e->getMessage());
            throw $e;
        }
        

    }
    public function getCountPersonas(int $cursoId){
        try{
            return EnrolamientoCurso::with('persona') 
            ->where('id_curso', $cursoId)
            ->count();
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al contar las personas del curso' . $e->getMessage());
            throw $e;
        }
        
    }


    public function getPersonsByInstanceId(int $instanceId, int $cursoId)
    {
        try{
            return EnrolamientoCurso::with('persona') 
            ->where('id_curso', $cursoId)
            ->where('id_instancia', $instanceId)
            ->get();
        } catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las personas de la instancia' . $e->getMessage());
            throw $e;
        }
       
            
    }
    public function getCountPersonsByInstanceId(int $instanceId, int $cursoId)
    {
        try{
            return EnrolamientoCurso::with('persona') 
            ->where('id_curso', $cursoId)
            ->where('id_instancia', $instanceId)
            ->count();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al contar las personas de la instancia' . $e->getMessage());
            throw $e;
        }
        
            
    }

    

    public function deleteByInstanceId(int $idCurso, int $idInstancia)
    {
        try{
            return EnrolamientoCurso::where('id_instancia', $idInstancia)
            ->where('id_curso', $idCurso)
            ->delete();
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al eliminar el enrolamiento' . $e->getMessage());
            throw $e;
        }
        
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
        try{
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
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al desenrolar a la persona' . $e->getMessage());
            throw $e;
        }
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
            
        } catch (Exception $e) {
            Log::error('Error in class: ' . get_class($this) . ' .Error al evaluar a la persona' . $e->getMessage());
            throw $e;
            return response()->json(['error' => 'Ocurrió un error al cambiar la evaluacion del curso: ' . $e->getMessage()], 500);
        }
    }

    public function getFechaCreacion(int $instanciaId, int $cursoId, int $userId){
        try{
            return EnrolamientoCurso::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->where('id_persona', $userId)
            ->value('created_at');
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al obtener la fecha de enrolamiento' . $e->getMessage());
            throw $e;
        }
        
    }
    public function getCountAprobados(int $cursoId){
        try{
            return EnrolamientoCurso::where('id_curso', $cursoId)
            ->where('evaluacion', 'Aprobado')
            ->count();
        }
        catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al contar los aprobados del curso.' . $e->getMessage());
            throw $e;
        }
       
    }
    public function getPorcentajeAprobacion(int $cursoId){
        try{
            $cantInscriptos = $this->getCountPersonas($cursoId);
            $cantAprobados = $this->getCountAprobados($cursoId);
            if ($cantInscriptos == 0) {
                return 0;
            }
            $porcentaje = ($cantAprobados * 100) / $cantInscriptos;
            return $porcentaje;
        }catch(Exception $e){
            Log::error('Error in class: ' . get_class($this) . ' .Error al calcular el % de aprobacion.' . $e->getMessage());
            throw $e;
        }
       
        
    }
    public function getInstancesByPersonId(int $cursoId, int $persona_id)
{
    try {
        
        return EnrolamientoCurso::where('id_curso', $cursoId)
            ->where('id_persona', $persona_id)
            ->pluck('id_instancia');  
    } catch (Exception $e) {
        Log::error('Error in class: ' . get_class($this) . ' .Error al obtener las instancias de enrolamiento: ' . $e->getMessage());
        throw $e;
    }
}

public function getEvaluacion(int $cursoId, int $persona_id)
{
    try {
       
        $evaluacion = EnrolamientoCurso::where('id_curso', $cursoId)
            ->where('id_persona', $persona_id)
            ->value('evaluacion');
        return $evaluacion;
    } catch (\Exception $e) {
      
        Log::error('Error en la clase: ' . get_class($this) . '. Error al obtener la evaluación: ' . $e->getMessage());
        throw $e; 
    }
}


public function getAprobados(int $cursoId, int $instanciaId)
{
    try {
        // Obtener la lista de id_persona de los aprobados
        $aprobados = EnrolamientoCurso::where('id_curso', $cursoId)
            ->where('id_instancia', $instanciaId)
            ->where('evaluacion', 'Aprobado')
            ->pluck('id_persona'); // pluck retorna una colección de los valores de la columna

        return $aprobados;
        
    } catch (\Exception $e) {
        Log::error('Error en la clase: ' . get_class($this) . '. Error al obtener la evaluación: ' . $e->getMessage());
        throw $e; 
    }
}

public function getCountAprobadosInstancia(int $cursoId, int $instanciaId){
    try{
        return EnrolamientoCurso::where('id_curso', $cursoId)
        ->where('id_instancia', $instanciaId)
        ->where('evaluacion', 'Aprobado')
        ->count();
    }
    catch(Exception $e){
        Log::error('Error in class: ' . get_class($this) . ' .Error al contar los aprobados de la instancia.' . $e->getMessage());
        throw $e;
    }
   
}

}
