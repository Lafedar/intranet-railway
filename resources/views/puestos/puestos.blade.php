@extends('layouts.app')
<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 


<div id="puestos-container" class="container-fluid">
    @if(Session::has('message'))
        <div class="container" id="div.alert">
            <div class="row">
                <div class="col-1"></div>
                <div class="alert {{Session::get('alert-class')}} col-10 text-center" role="alert">
                    {{Session::get('message')}}
                </div>
            </div>
        </div>
    @endif
    <button class="btn btn-info" onclick='fnOpenModalStore()' data-toggle="modal" id="btn-agregar"> Nuevo
        puesto</button>
    <div>
        <h1>
            <div class="form-inline pull-right">
                <form method="GET">
                    <div class="form-group">
                        <div class="form-group">
                            <h6>Puesto:</h6>
                            <input type="text" name="puesto" class="form-control" id="puesto" value="{{$puesto}}">
                        </div>
                        &nbsp
                        <div class="form-group">
                            <h6>Usuario:</h6>
                            <input type="text" name="usuario" class="form-control" id="usuario" value="{{$usuario}}">
                        </div>
                        &nbsp
                        <div class="form-group">
                            <h6>Localizacion:</h6>
                            <input type="text" name="localizacion" class="form-control" id="localizacion"
                                value="{{$localizacion}}">
                        </div>
                        &nbsp
                        <div class="form-group">
                            <h6>Area:</h6>
                            <input type="text" name="area" class="form-control" id="area" value="{{$area}}">
                        </div>
                        &nbsp
                        <button type="submit" class="btn btn-default" id="asignar-btn"> Buscar</button>
                    </div>
                </form>
            </div>
        </h1>
    </div>

    <div>
        <table>
            <thead>
                <th class="text-center">Nombre</th>
                <th class="text-center">Usuario</th>
                <th class="text-center">Localizacion</th>
                <th class="text-center">Area</th>
                <th class="text-center">Observación</th>
                @can('editar-puesto')
                    <th class="text-center">Acciones</th>
                @endcan
            </thead>
            <tbody>
                @if(count($puestos))
                    @foreach($puestos as $puesto) 
                        <tr>
                            <td>{{$puesto->desc_puesto}}</td>
                            <td>{{$puesto->nombre . ' ' . $puesto->apellido}}</td>
                            <td>{{$puesto->localizacion}}</td>
                            <td>{{$puesto->area}}</td>
                            <td align="center">{{$puesto->obs}}</td>
                            @can('editar-puesto')
                                <td align="center">
                                    <div class="botones">
                                        <!-- Boton para eliminar puesto -->
                                        <a href="{{url('destroy_puesto', $puesto->id_puesto)}}" class="fa-solid fa-xmark eliminar"
                                            title="Eliminar" onclick="return confirm ('¿Está seguro que desea eliminar el puesto?')"
                                            data-position="top" data-delay="50" data-tooltip="Borrar" id="icono"><img
                                                src="{{ asset('storage/cursos/eliminar.png') }}" alt="Eliminar" id="img-icono"> </a>
                                        <!-- Boton para editar puesto -->
                                        <button class="fa-solid fa-pen default" onclick='fnOpenModalUpdate({{$puesto->id_puesto}})'
                                            data-toggle="modal" style="outline: none;" id="icono" title="Editar"><img
                                                src="{{ asset('storage/cursos/editar.png') }}" alt="Editar" id="img-icono"></button>

                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        {{ $puestos->links('pagination::bootstrap-4') }}
    </div>
</div>
<div class="modal fade" id="show2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog estilo" role="document">
        <div class="modal-content">
            <form id="myForm" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div id="modalshow" class="modal-body">
                    <!-- Datos -->
                </div>
                <div id="modalfooter" class="modal-footer">
                    <!-- Footer -->
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="show3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog estilo" role="document">
        <div class="modal-content">
            <form id="myForm3" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div id="modalshow3" class="modal-body">
                    <!-- Datos -->
                </div>
                <div id="modalfooter3" class="modal-footer">
                    <!-- Footer -->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("document").ready(function () {
        setTimeout(function () {
            $("div.alert").fadeOut();
        }, 5000); // 5 secs

    });
</script>
<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

<script>
    var ruta_update = '{{ route('update_puesto') }}';
    var ruta_store = '{{ route('store_puesto') }}';
    var closeButton = $('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>');
    var saveButton = $('<button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>');

    function fnOpenModalStore() {
        var myModal = new bootstrap.Modal(document.getElementById('show2'));
        var url = window.location.origin + "/show_store_puesto/";
        $.get(url, function (data) {
            // Borrar contenido anterior
            $("#modalshow").empty();

            // Establecer el contenido del modal
            $("#modalshow").html(data);

            // Borrar contenido anterior
            $("#modalfooter").empty();

            // Agregar el botón "Cerrar y Guardar" al footer
            $("#modalfooter").append(closeButton);
            $("#modalfooter").append(saveButton);

            // Cambiar la acción del formulario
            $('#myForm').attr('action', ruta_store);

            // Mostrar el modal
            myModal.show();

            // Cambiar el tamaño del modal a "modal-lg"
            var modalDialog = myModal._element.querySelector('.modal-dialog');
            modalDialog.classList.remove('modal-sm');
            modalDialog.classList.remove('modal-lg');
            //para cerrar modales
closeButton.on('click', function () {
      myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
    });
        });
        $('#show2').on('show.bs.modal', function (event) {
            $.get('select_area/', function (data) {
                var html_select = '<option value="">Seleccione</option>'
                for (var i = 0; i < data.length; i++)
                    html_select += '<option value ="' + data[i].id_a + '">' + data[i].nombre_a + '</option>';
                $('#area').html(html_select);
            });
            $.get('select_persona/', function (data) {
                var html_select = '<option value="">Seleccione</option>'
                for (var i = 0; i < data.length; i++) {
                    if (data[i].activo == 1) {
                        if (data[i].apellido == null) {
                            html_select += '<option value ="' + data[i].id_p + '">' + data[i].nombre_p + '</option>';
                        } else {
                            html_select += '<option value ="' + data[i].id_p + '">' + data[i].nombre_p + ' ' + data[i].apellido + '</option>';
                        }
                    }
                }
                $('#persona').html(html_select);
            });
            $.get('select_localizaciones/', function (data) {
                var html_select = '<option value="">Seleccione</option>'
                $('#localizacion').html(html_select);
            });

            // Variable para almacenar el valor seleccionado de localizacion
            var selectedLocalizacion = $('#localizacion').val();

            // Al seleccionar un área, cargar las localizaciones correspondientes
            $('#area').on('change', function () {
                var areaId = $(this).val();
                if (areaId === "") {
                    var html_select = '<option value="">Seleccione</option>';
                    $('#localizacion').html(html_select);
                    $('#localizacion').val("");
                } else {
                    $.get('select_localizaciones_by_area/' + areaId, function (data) {
                        var html_select = '<option value="">Seleccione</option>';
                        for (var i = 0; i < data.length; i++) {
                            html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
                        }
                        $('#localizacion').html(html_select);
                    });
                }
            });

            // Al seleccionar una localización, cargar el área correspondiente
            $('#localizacion').on('change', function () {
                var localizacionId = $(this).val();
                $.get('select_area_by_localizacion/' + localizacionId, function (data) {
                    // Primero, deseleccionamos el área actualmente seleccionada
                    $('#area').val('');

                    // Luego, seleccionamos el área correspondiente a la localización
                    if (data.id_a) {
                        $('#area').val(data.id_a);
                    }
                });
            });
        });
    }
    function getPuesto(idPuesto) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: window.location.protocol + '//' + window.location.host + "/getPuesto/" + idPuesto,
                method: 'GET',
                success: function (data) {
                    resolve(data);
                },
                error: function (error) {
                    reject(error);
                }
            });
        });
    }

    let puesto;
    async function fnOpenModalUpdate(id) {
        try {
            puesto = await getPuesto(id);
            console.log(puesto);

            var myModal = new bootstrap.Modal(document.getElementById('show3'));
            var url = window.location.origin + "/show_update_puesto/" + id;
            var data = await $.get(url); // Esperar a que los datos se obtengan

            // Borrar contenido anterior
            $("#modalshow3").empty();

            // Establecer el contenido del modal
            $("#modalshow3").html(data);
            // Borrar contenido anterior
            $("#modalfooter3").empty();

            // Agregar el botón "Cerrar y Guardar" al footer
            $("#modalfooter3").append(closeButton);
            $("#modalfooter3").append(saveButton);

            // Cambiar la acción del formulario
            $('#myForm3').attr('action', ruta_update);

            // Mostrar el modal
            myModal.show();

            // Cambiar el tamaño del modal a "modal-lg"
            var modalDialog = myModal._element.querySelector('.modal-dialog');
            modalDialog.classList.remove('modal-sm');
            modalDialog.classList.remove('modal-lg');
            //para cerrar modales
closeButton.on('click', function () {
      myModal.hide(); // Cierra el modal cuando se hace clic en el botón Cerrar
    });


            // Aquí puedes colocar el código que depende de los datos de puesto,
            // por ejemplo, el código para actualizar los campos del modal.
            // ...
        } catch (error) {
            console.error('Error al obtener los datos o al mostrar el modal:', error);
        }
    }
    $('#show3').on('show.bs.modal', function (event) {
        $.get('select_area/', function (data) {
            var areaSeleccionada = null;
            var html_select = '<option value="">Seleccione</option>'
            for (var i = 0; i < data.length; i++) {
                if (puesto.idArea == data[i].id_a) {
                    console.log("INGRESA area: ", puesto.idArea, data[i].id_a);
                    html_select += '<option value ="' + data[i].id_a + '" selected>' + data[i].nombre_a + '</option>';
                    areaSeleccionada = data[i].id_a;
                } else {
                    html_select += '<option value ="' + data[i].id_a + '">' + data[i].nombre_a + '</option>';
                }
            }
            if (areaSeleccionada) {
                $.get('select_localizaciones_by_area/' + areaSeleccionada, function (data) {
                    var html_select = '<option value="">Seleccione</option>';
                    for (var i = 0; i < data.length; i++) {
                        if (puesto.idLocalizacion == data[i].id) {
                            html_select += '<option value="' + data[i].id + '" selected>' + data[i].nombre + '</option>';
                        } else {
                            html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
                        }
                    }
                    $('#localizacion1').html(html_select);
                });
            }
            $('#area1').html(html_select);
        });
        $.get('select_persona/', function (data) {
            var html_select = '<option value="">Seleccione</option>'
            for (var i = 0; i < data.length; i++) {
                if (data[i].activo == 1) {
                    if (puesto.idPersona == data[i].id_p) {
                        if (data[i].apellido == null) {
                            html_select += '<option value ="' + data[i].id_p + '" selected>' + data[i].nombre_p + '</option>';
                        } else {
                            html_select += '<option value ="' + data[i].id_p + '" selected>' + data[i].nombre_p + ' ' + data[i].apellido + '</option>';
                        }
                    } else {
                        if (data[i].apellido == null) {
                            html_select += '<option value ="' + data[i].id_p + '">' + data[i].nombre_p + '</option>';
                        } else {
                            html_select += '<option value ="' + data[i].id_p + '">' + data[i].nombre_p + ' ' + data[i].apellido + '</option>';
                        }
                    }
                }
            }
            $('#persona1').html(html_select);
        });

        // Variable para almacenar el valor seleccionado de localizacion
        var selectedLocalizacion = $('#localizacion').val();

        // Al seleccionar un área, cargar las localizaciones correspondientes
        $('#area1').on('change', function () {
            console.log("ingresa change area");
            var areaId = $(this).val();
            if (areaId === "") {
                var html_select = '<option value="">Seleccione</option>';
                $('#localizacion1').html(html_select);
                $('#localizacion1').val("");
            } else {
                $.get('select_localizaciones_by_area/' + areaId, function (data) {
                    var html_select = '<option value="">Seleccione</option>';
                    for (var i = 0; i < data.length; i++) {
                        if (puesto.idLocalizacion == data[i].id) {
                            html_select += '<option value="' + data[i].id + '" selected>' + data[i].nombre + '</option>';
                        } else {
                            html_select += '<option value="' + data[i].id + '">' + data[i].nombre + '</option>';
                        }
                    }
                    $('#localizacion1').html(html_select);
                });
            }
        });

        // Al seleccionar una localización, cargar el área correspondiente
        $('#localizacion1').on('change', function () {
            var localizacionId = $(this).val();
            $.get('select_area_by_localizacion/' + localizacionId, function (data) {
                // Primero, deseleccionamos el área actualmente seleccionada
                $('#area1').val('');

                // Luego, seleccionamos el área correspondiente a la localización
                if (data.id_a) {
                    $('#area1').val(data.id_a);
                }
            });
        });
        $('#desc_puesto1').val(puesto.nombrePuesto);
        if (puesto.observaciones != null) {
            $('#obs1').val(puesto.observaciones);
        }
    });
</script>

<script>
    $('#agregar_software').on('show.bs.modal', function (event) { });
</script>