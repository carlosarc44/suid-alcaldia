<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Falta asociada al proceso: {{$vigencia."-".$idRadicado}}</label>
                <br>
                <form autocomplete="off">
                    <fieldset style="margin-top:20px">
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-xs-2">
                            <label class="pull-right">Falta:</label>
                            </div>
                            <div class="col-xs-10" style="padding:0px;">              
                            {{ Form::select('falta', array('default' => 'Seleccione..') + $lista_faltas, 
                                $falta, array('class' => 'form-control', 'id'=>'falta', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">	      		
  <button type="button" class="btn btn-info pull-right" data-dismiss="modal" onclick="cambiarFalta();">Modificar Falta</button>
</div>
