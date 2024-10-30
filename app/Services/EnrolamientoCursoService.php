<?php

namespace App\Services;

use App\Models\CursoInstancia;
use App\Models\EnrolamientoCurso;
use App\Services\CursoInstanciaService;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Curso; 




class EnrolamientoCursoService
{
    private $cursoInstanciaService;

    public function __construct(CursoInstanciaService $cursoInstanciaService)
    {
        $this->cursoInstanciaService = $cursoInstanciaService;
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

    public function isEnrolled($userDni, $instanceId): ?bool
    {

        $person = Persona::where('dni', $userDni)->first();
        return EnrolamientoCurso::where('id_persona', $person->id_p)
            ->where('id_instancia', $instanceId)
            ->exists();
    }

    public function enroll($userDni, $instanceId): ?EnrolamientoCurso
    {
        $courseEnrollment = null;
        $person = Persona::where('dni', $userDni)->first();
        if ($this->isEnrolled($person->dni, $instanceId)) {
            Log::error('Error el id: ' . $person->id_p . ' de persona ya existe: ');
            throw new \Exception('El usuario no se puede enrolar porque ya se encuentra inscripto.');
        }

        $courseId = CursoInstancia::where('id', $instanceId)
            ->value('id_curso');
            

        if ($this->cursoInstanciaService->checkInstanceQuota($courseId, $instanceId) > 0) {
            $quota = $this->cursoInstanciaService->decrementQuota($courseId, $instanceId);
            $data = [
                'id_persona' => $person->id_p,
                'id_instancia' => $instanceId,
                'fecha_enrolamiento' => Carbon::now(),
                'estado' => 'Alta',
                'evaluacion' => null
            ];
            $courseEnrollment = EnrolamientoCurso::create($data);
        } else Log::alert('Alert in class: ' . get_class($this) .'.No hay cupo para el id_curso: ' . $courseId . ' y la instancia id: ' . $instanceId );
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

}
