<fieldset>
  <div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-5">
      <label class="pull-right">Siguiente Etapa:</label>
    </div>
    <div class="col-xs-7" style="padding:0px;">              
      {{ Form::select('etapas', array('default' => 'Seleccione..') + $lista_etapas, 
          null, array('class' => 'form-control', 'id'=>'etapaSiguiente', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
    </div>
  </div>
</fieldset>