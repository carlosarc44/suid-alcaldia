<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Fecha de los hechos del proceso {{$vigencia."-".$idRadicado}}</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <form autocomplete="off">
                        <input type="text" class="form-control pull-right" id="fechaHechos" value="{{$fechaHechos}}">
                    </form>
                </div>
                <br>
                <span className="help-block">Año-Mes-Día <i>Ej: 2021-01-25</i></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">	      		
  <button type="button" class="btn btn-info pull-right" data-dismiss="modal" onclick="cambiarFechaHechos();">Modificar Fecha</button>
</div>
