<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<div class="modal fade" id="editar" role="dialog" align="center">
    <div class="modal-dialog">
       <div class="modal-content">           
        <form action="{{ route('update_proyectos')}}" method="POST" enctype="multipart/form-data">
         {{csrf_field()}}
         <div class="modal-body">
           <div class="row">
               <div class="col-md-12">
                <input type="hidden" name="id" id="id" value="">
                <div class="form-group col-md-12">
                    <label for="title"><strong>Titulo:</strong></label>
                    <textarea rows="3" type="text" class="form-control" name="titulo" id="titulo" required></textarea>
                    <label for="title"><strong>Fecha de proyecto:</strong></label>
                    <input type="date" name="fecha"  class="form-control col-md-5" step="1" min="2019-01-01" value="<?php echo date("Y-m-d");?>">
                    <label for="title"><strong>Observación:</strong></label>
                    <textarea rows="3" type="text" class="form-control" name="obs" id="obs"></textarea>
                    <label for="title" class="col-md-10"><strong>ASM:</strong></label>
                    <input type="file"  name="asm" accept=".asm" id="asm">
                    <div class="elim_asm">
                        <label for="eliminar_asm">Eliminar</label>
                        <input type="checkbox"value="1" id="eliminar_asm" name="eliminar_asm">
                    </div>
                    <label for="title" class="col-md-10"><strong>DWG:</strong></label>
                    <input type="file"  name="dwg" accept=".dwg" id="dwg">
                    <div class="elim_dwg">
                        <label for="eliminar_dwg">Eliminar</label>
                        <input type="checkbox"value="1" id="eliminar_dwg" name="eliminar_dwg">
                    </div>
                    <label for="title" class="col-md-10"><strong>PAR:</strong></label>
                    <input type="file"  name="par" accept=".par" id="par">
                    <div class="elim_par">
                        <label for="eliminar_par">Eliminar</label>
                        <input type="checkbox"value="1" id="eliminar_par" name="eliminar_par">
                    </div>
                    <label for="title" class="col-md-10"><strong>STL:</strong></label>
                    <input type="file"  name="stl" accept=".stl"  id="stl">
                    <div class="elim_stl">
                        <label for="eliminar_stl">Eliminar</label>
                        <input type="checkbox"value="1" id="eliminar_stl" name="eliminar_stl">
                    </div>
                    <label for="title" class="col-md-10"><strong>PDF:</strong></label>
                    <input type="file"  name="pdf" accept=".pdf"  id="pdf">
                    <div class="elim_pdf">
                        <label for="eliminar_pdf">Eliminar</label>
                        <input type="checkbox"value="1" id="eliminar_pdf" name="eliminar_pdf">
                    </div>
                    <label for="title" class="col-md-10"><strong>MPP:</strong></label>
                    <input type="file"  name="mpp" accept=".mpp"  id="mpp">
                    <div class="elim_mpp">
                        <label for="eliminar_mpp">Eliminar</label>
                        <input type="checkbox"value="1" id="eliminar_mpp" name="eliminar_mpp">
                    </div>
                </div>     
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="asignar-btn">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="asignar-btn">Guardar</button>
            </div>
        </div>
    </form>                
</div>
</div>
</div>