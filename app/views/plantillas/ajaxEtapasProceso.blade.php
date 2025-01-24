<div class="col-sm-2">
	<label class="pull-right">Etapa:</label>
</div>
<div class="col-sm-4" style="padding:0px;">          
	{{ Form::select('etapas', array('default' => 'Seleccione..') + $lista_etapas, 
  $idEtapa, array('class' => 'form-control', 'id'=>'etapaExterno', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
</div>