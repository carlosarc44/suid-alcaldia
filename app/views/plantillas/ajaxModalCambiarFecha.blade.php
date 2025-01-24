<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Fecha de inicio de: {{$etapa->nombreEtapa}}</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <form autocomplete="off">
                        <input type="text" class="form-control pull-right" id="fechaEtapa" value="{{$etapa->fechaEtapa}}">
                    </form>
                </div>
                <br>
                <span className="help-block">Año-Mes-Día <i>Ej: 2021-01-15</i></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">	      		
  <button type="button" class="btn btn-info pull-right" data-dismiss="modal" onclick="cambiarFecha({{$etapa->idEtapa.', '.$fase.', '.$vigencia.', '.$idRadicado.', '.$actuacion}});">Modificar Fecha</button>
</div>
