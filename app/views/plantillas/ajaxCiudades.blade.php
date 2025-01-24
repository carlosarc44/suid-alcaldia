	<div class="col-md-5 col-xs-offset-1"> 
	{{ Form::select('ciudad', array('default' => 'Ciudad') + $lista_ciudades, 
  Input::old('ciudad'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'ciudad', 'style'=>'color:#696969; padding-left:0; margin:0; width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
	</div>
	<div class="col-xs-4">
  	<span class="tituloAyuda">Ciudad de destino</span>
</div>