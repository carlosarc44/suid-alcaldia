<div class="row">
  	<div class="col-xs-5 col-xs-offset-1">
    	<strong><input type="text" class="form-control no-border" id="destinatario" placeholder="Destinatario" style="padding-left:2px; text-transform: uppercase;" value="{{$persona[0]->nombre}}"/></strong>
  	</div>
  	<div class="col-xs-3">
    	<span class="tituloAyuda">Destinatario</span>
  	</div>
</div>

<div class="row">
	<div class="col-xs-5 col-xs-offset-1">
  		<input type="text" class="form-control no-border" id="entidad" placeholder="Entidad" style="padding-left:2px;"/>
	</div>
	<div class="col-xs-3">
  		<span class="tituloAyuda">Entidad de destino</span>
	</div>
</div>

<div class="row">
	<div class="col-md-5 col-xs-12 col-xs-offset-1">
  		<input type="text" class="form-control no-border" id="direccion" placeholder="Dirección" style="padding-left:2px;" value="{{$persona[0]->direccionCorrespondencia}}"/>
	</div> 
	<div class="col-xs-5">
  		<span class="tituloAyuda">Dirección del destinatario</span>
	</div>  
</div>

<div class="row">
	<div class="col-md-5 col-xs-offset-1" style="margin-bottom: 5px">   
		{{ Form::select('departamento', array('default' => 'Departamento') + $lista_departamentos, 
   		$persona[0]->idDepartamento, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'departamento', 'onchange' => 'cargarCiudad(this.value)', 'style'=>'color:#696969; padding-left:0; width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true', 'onchange' => 'cargarCiudad(this.value)')) }}
	</div>
	<div class="col-xs-4">
			<span class="tituloAyuda">Departamento de destino</span>
	</div>
</div>

<div id="resultadoCargarCiudad" class="row">              
	<!-- CARGA AJAX --> 
	<div class="col-md-5 col-xs-offset-1"> 
		{{ Form::select('ciudad', array('default' => 'Ciudad') + $lista_ciudades, 
  		$persona[0]->idCiudad, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'ciudad', 'style'=>'color:#696969; padding-left:0; margin:0; width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}             
	</div>
</div>	 