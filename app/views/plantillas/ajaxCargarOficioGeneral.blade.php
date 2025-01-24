<style>
	#hoja{
		border: 1px solid #ddd;
		border-radius: 5px;
		background: #fff;
		padding: 10px;
		min-height: 440px;
	}
	.tituloAyuda{
		color:#c4c4c4;	
	}
	</style>
	<div class="row">
		<div class="col-xs-7">
			<div id="hoja">
				<div class="row">
					<img src="{{ asset('img/banner-oficio.png')}}" style="height:auto; width:99%; margin:-10px 0px 5px 5px"/>	        
					<div class="col-xs-10 col-xs-offset-1">
						 <strong>CDI {{$numeroOficio."/".date("Y")." - ".$iniciales}}</strong>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						Manizales, 	{{Util::formatearFecha(date("Y-m-d"))}}
					</div>
				</div>
				<br><br>
				<div class="row">
				  <div class="col-xs-1 col-xs-offset-1">
					<strong>Señor:</strong>
				  </div>
				</div>
				
				<!-- resultadoDestino -->
				<div id="resultadoDestino">    
					<div class="row">
						  <div class="col-xs-5 col-xs-offset-1">
							<strong><input type="text" class="form-control no-border" id="destinatario" placeholder="Destinatario" style="padding-left:2px; text-transform: uppercase;"/></strong>
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
							  <input type="text" class="form-control no-border" id="direccion" placeholder="Dirección" style="padding-left:2px;"/>
						</div> 
						<div class="col-xs-5">
							  <span class="tituloAyuda">Dirección del destinatario</span>
						</div>  
					  </div>
			
					  <div class="row">
						<div class="col-md-5 col-xs-offset-1" style="padding-bottom: 5px">   
							{{ Form::select('departamento', array('default' => 'Departamento') + $lista_departamentos, 
							   Input::old('departamento'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'departamento', 'onchange' => 'cargarCiudad(this.value)', 'style'=>'color:#696969; padding-left:0;width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true', 'onchange' => 'cargarCiudad(this.value)')) }}
						</div>
						<div class="col-xs-4">
							  <span class="tituloAyuda">Departamento de destino</span>
						</div>
					  </div>
		   
					  <div id="resultadoCargarCiudad" class="row">              
						<!-- CARGA AJAX -->              
					  </div>	          
				  </div>
				  <!-- # resultadoDestino -->
				  <div class="row" style="margin: 40px 0;">
					  <div class="col-xs-2 col-xs-offset-1" style="padding-top:6px;">
						  <label class="pull-right">ASUNTO: </label>
					</div>
					<div class="col-xs-9">
						  <input type="text" class="form-control no-border" id="asunto" placeholder="Asunto del Oficio"/>
					</div>
					  
				  </div>
			</div>
			<button type="button" class="btn btn-info btn-sm pull-right" style="margin-top: 6px;" onclick="validarGenerarOficioGeneral();"><i class="fa fa-file-word-o"></i> Generar Oficio</button>
		</div>
		<div class="col-xs-5">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_Pers" data-toggle="tab">Personas Base Datos</a></li>
					<li><a href="#tab_Enti" data-toggle="tab">Entidades</a></li>
				</ul>
				<div class="tab-content">
					<!-- Personas -->
					<div class="tab-pane my-tabs active" id="tab_Pers">
						<b>Intente buscando el destinatario por documento o nombre:</b> 
							<input type="text" 
								   class="form-control" 
								   id="docPersona" 
								   name="docPersona" 
								   style="width:100%; margin-top:6px" 
								   autofocus 
								   autocomplete="new-password" 
								   placeholder="Busque por documento o nombre"/> 
						<hr>
						<div id="ajax-docPersona">
							<!-- carga ajax -->
						</div>
					</div>
					<!-- # Personas -->

					<!-- Entidades -->
					<div class="tab-pane my-tabs" id="tab_Enti">
						<table id="tablaEntidades" class="table table-bordered table-hover table-striped" style="font-size:0.8em;">
							<thead>
								<tr>
									<th width="40%">Encargado</th>
									<th width="30%">Entidad</th>
									<th width="30%">Dirección</th>
								</tr>
							</thead>
							<tbody>
									@foreach ($entidades as $entidad)							
									<tr>
										<td><b><a href="javascript: void(0)" style="color:#000;" onclick="fijarDestinatarioEnt('{{ $entidad->idComunicacionesReglamentarias }}')">{{ $entidad->nombreEncargado }}</a></b></td>
										<td>{{ $entidad->nombreEntidad }}</td>		
										<td>{{ $entidad->direccionEntidad }}</td>
									</tr>
								@endforeach  
							</tbody>
							</table>
					</div>
					<!-- Entidades -->
				</div>
			</div>
		</div>
	</div>