<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="asignar" role="dialog" align="center">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ action('EquipamientoController@store_relacion') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-field col s12 ">Equipamiento:
                                <input class="form-control" rows="5" name="equipamiento" id="equipamiento"
                                    required></input>
                            </div>
                            <div class="input-field col s12 ">Puesto:
                                <select class="form-control" name="puesto" id="select_puesto" required></select>
                            </div>
                            <p></p>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                id="asignar-btn">Cerrar</button>
                            <button type="submit" class="btn btn-info" id="asignar-btn">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>