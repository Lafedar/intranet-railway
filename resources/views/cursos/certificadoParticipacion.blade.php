<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Participación</title>
    <link href="{{ asset('css/certificado.css') }}" rel="stylesheet">

</head>

<body>
    <div class="certificate">
        <div class="certificate-border">
            <div class="certificate-content">
                <div class="certificate-header">
                    <img src="{{ $imageBase64 }}" alt="Logo" width="200" height="70" />
                    <br><br>
                    <h1>Certificado de Participación</h1>
                    <p>Otorgado por <strong>Laboratorios Lafedar</strong></p>
                </div>

                <div class="certificate-body">
                    <p>Se otorga el presente certificado a:</p>
                    <h2 class="recipient-name">{{$person->nombre_p}} {{$person->apellido}}</h2>
                    <p>por haber participado de la capacitación</p>
                    <h3 class="course-title">{{$course->titulo}}</h3>

                </div>

                <div class="certificate-footer">
                    <p>Capacitador/a: {{$instance->capacitador}}</p>
                    <br>
                    <br>
                    <p>Fecha de Capacitación: {{$instance->fecha_inicio->format('d-m-Y')}}</p>

                    <br><br><br><br><br>
                    <div style="display: inline-block; width: 48%; text-align: center;">
                    <img src="{{ $imageBase64_firma }}" alt="Logo" width="auto" height="150px;" style="margin-bottom: -30px; margin-top: -90px;"/>
                        <p>______________________________</p>
                        <p>Firma de RRHH</p>
                    </div>
                    

                </div>

            </div>
        </div>
    </div>
    </div>

    @if(empty($is_pdf))
        <form
            action="{{ route('cursos.generatePDFcertificate', ['instanciaId' => $instance->id_instancia, 'cursoId' => $course->id, 'personaId' => $person->id_p]) }}"
            method="GET" class="hide-when-pdf">
            @csrf
            <button type="submit" class="btn btn-primary">Generar PDF</button>
        </form>
    @endif
</body>

</html>




