<div class="container mt-5">
    <div id="cursos-instancias-create-container">
        <h1 class="mb-4 text-center">Crear Instancia</h1>

        @csrf

        <div class="row">
            <!-- Columna 1 -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        placeholder="Igual a Fecha De Inicio" required>
                </div>
                <div class="form-group">
                    <label for="quota">Cupos</label>
                    <input type="number" class="form-control" id="quota" name="quota" placeholder="100" required min="0"
                        max="999999999" oninput="limitInputLength(this)">
                </div>
                <div class="form-group">
                    <label for="code">Codigo</label>
                    <input type="text" class="form-control" id="code" name="code" placeholder="Null" value=" "
                        maxlength="49">
                </div>

                <div class="form-group">
                    <label for="version">Version</label>
                    <input type="number" name="version" class="form-control" min="0" max="999999999" placeholder="Null"
                        oninput="limitInputLength(this)">
                </div>

            </div>

            <!-- Columna 2 -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="hour">Hora</label>
                    <input type="time" class="form-control" id="hour" name="hour" placeholder="00:00" required>
                </div>
                <div class="form-group">
                    <label for="modality">Modalidad</label>
                    <select class="form-control" id="modality" name="modality">
                        <option value="">Seleccione una modalidad</option>
                        <option value="Presencial" selected>Presencial</option>
                        <option value="Hibrido">Hibrido</option>
                        <option value="Remoto">Remoto</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="place">Lugar</label>
                    <input type="text" class="form-control" id="place" name="place" placeholder="Lafedar"
                        maxlength="100">
                </div>
                <div class="form-group">
                    <label for="status">Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="Activo" selected>Activo</option>
                        <option value="No Activo">No Activo</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="annexes">Registros de Capacitación</label>
            <div id="annexes">
                @foreach($annexes as $form)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="annexes[]"
                            id="anexo_{{ $form->formulario_id }}" value="{{ $form->formulario_id }}" checked>
                        <p class="form-check-label" for="anexo_{{ $form->formulario_id }}">
                            {{ $form->valor_formulario }} - {{ $form->valor2 }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Certificados</label><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="certificate" id="approval_certificate"
                    value="Aprobacion" required checked>
                <label for="approval_certificate" style="font-weight: normal;">
                    Certificado de Aprobación
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="certificate" id="participation_certificate"
                    value="Participacion" required>
                <label for="participation_certificate" style="font-weight: normal;">
                    Certificado de Participación
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="exam">Examen (Insertar Link de Microsoft Form)</label>
            <input type="text" name="exam" class="form-control" maxlength="200" placeholder="Null" id="examInput">
        </div>

        <div id="botones-modal">
            <button type="button" data-dismiss="modal" aria-label="Close" id="asignar-btn">
                Cancelar
            </button>

            <button id="cargar" class="asignar-btn">Guardar</button>
        </div>

    </div>

</div>




<script>
    document.addEventListener('click', function (e) {

        if (e.target && e.target.id === 'cargar') {

            e.preventDefault(); // Evita el envío del formulario
            // Obtener los valores del formulario de la modal
            const endDate = document.getElementById('end_date').value;
            const hour = document.getElementById('hour').value;
            const quota = document.getElementById('quota').value;
            const modality = document.getElementById('modality').value;
            const code = document.getElementById('code').value;
            const place = document.getElementById('place').value;
            const exam = document.getElementById('examInput').value;
            const certificate = document.querySelector('input[name="certificate"]:checked')?.value;
            const status = document.getElementById('status')?.value;
            const version = document.getElementById('version')?.value;
            const annexes = Array.from(document.querySelectorAll('input[id^="anexo_"]:checked'))
                .map(el => el.value);

            // Asignar valores a los inputs ocultos de la vista principal
            document.getElementById('end_date_main').value = endDate;
            document.getElementById('hour_main').value = hour;
            document.getElementById('quota_main').value = quota;
            document.getElementById('modality_main').value = modality;
            document.getElementById('code_main').value = code;
            document.getElementById('place_main').value = place;
            document.getElementById('exam_main').value = exam;
            document.getElementById('certificate_main').value = certificate;
            document.getElementById('status_main').value = status;
            document.getElementById('version_main').value = version;
            document.getElementById('annexes_main').value = annexes.join(',');


            // Cierra la modal
            $('#defaultFeaturesModal').modal('hide');
        }
    });



</script>