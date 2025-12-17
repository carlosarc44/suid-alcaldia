@extends('plantillas.layout3')
<!--includes de la cabecera-->
@section('cabecera')
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
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>REMITIR {{$queja[0]->nombreOrigenQueja." ".$queja[0]->idQueja}} </h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li><a href="javascript: void(0)">Quejas</a></li>
	<li class="active">Remitir Queja</li>
</ol>
<!--  #MIGA DE PAN -->
@stop
<!--# miga de pan-->

<!--menu lateral izquierdo-->
@section('menuLateral') 
  @include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
	<!--  box-->
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body box-profile" style="display: block;" id="resultadoQuejas">

			<div class="row">
				<div class="col-sm-6">
					<div id="hoja">
						<div class="row">
				          	<div class="col-xs-2 col-xs-offset-1">
				            	<img src="{{ asset('img/logoAlcaldia2.png')}}" width="190"/>
				          	</div>
				        </div>
						<div class="row">
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
				        	<input type="hidden" id="idEntidadSeleccionada">
				          	<div class="col-xs-1 col-xs-offset-1">
				            	<strong>Señor:</strong>
				          	</div>
				        </div>
				        
					    <!-- resultadoDestino -->
					    <div id="resultadoDestino">    
					        <div class="row">
					          	<div class="col-xs-5 col-xs-offset-1">
					            	<strong><input type="text" class="form-control" id="destinatario" placeholder="Destinatario" style="padding-left:2px; text-transform: uppercase;"/></strong>
					          	</div>
					          	<div class="col-xs-3">
					            	<span class="tituloAyuda">Destinatario</span>
					          	</div>
					        </div>       
				        
				          	<div class="row">
				            	<div class="col-xs-5 col-xs-offset-1">
				              		<input type="text" class="form-control" id="entidad" placeholder="Entidad" style="padding-left:2px;"/>
				            	</div>
				            	<div class="col-xs-3">
				              		<span class="tituloAyuda">Entidad de destino</span>
				            	</div>
				          	</div>
			         
				          	<div class="row">
				            	<div class="col-md-5 col-xs-12 col-xs-offset-1">
				              		<input type="text" class="form-control" id="direccion" placeholder="Dirección" style="padding-left:2px;"/>
				            	</div> 
				            	<div class="col-xs-5">
				              		<span class="tituloAyuda">Dirección del destinatario</span>
				            	</div>  
				          	</div>
			        
			          		<div class="row">
			            		<div class="col-md-5 col-xs-offset-1">   
			                		{{ Form::select('departamento', array('default' => 'Departamento') + $lista_departamentos, 
			                   		null, array('class' => 'form-control', 'id'=>'departamento', 'onchange' => 'cargarCiudad(this.value)', 'style'=>'color:#696969; padding-left:0; width:100%;', 'onchange' => 'cargarCiudad(this.value)')) }}
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
			          		<div class="col-xs-2 col-xs-offset-1">
			              		<label class="pull-right">ASUNTO: </label>
			            	</div>
			            	<div class="col-xs-9">
			            		<span>Remisión <strong>RXC - {{$remisionCompetencia."/".date('Y')}}</strong> {{$queja[0]->nombreOrigenQueja." <strong>".$queja[0]->idQueja."</strong>"}}</span>
			            		<br>
			            	</div>
			          		
			          	</div>
					</div>
					<hr>
					<div class="row">	
						<div class="col-md-4">
							<label class="pull-right">Oficio que se remite:</label>
						</div>
						<div class="col-md-8">
							<input type="text" id="oficio" class="form-control">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<label class="pull-right">Motivo de la remisión:</label>
						</div>
						<div class="col-md-8">
							<textarea id="motivo" rows="2" class="form-control"></textarea>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<label class="pull-right">Al generar el oficio utilizar:</label>
						</div>
						<div class="col-md-8">
							<select id="tipoRemision" class="form-control pull-left">
								<option value="1">Plantilla para remitir a una Entidad</option>
								<option value="2">Plantilla para remitir al Comité de Convivencia Laboral</option>
								<option value="3">Plantilla para realizar una Devolución</option>
							</select>
						</div>
					</div>
					<hr>
					<button type="button" class="btn btn-success btn-sm pull-right" onclick="validarRemitirQueja('{{$queja[0]->nombreOrigenQueja}}', '{{$queja[0]->idQueja}}');"><i class="fa fa-save"></i> Remitir Queja</button>

				</div>
				<div class="col-sm-6">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab_Enti" data-toggle="tab">Entidades Remisión</a></li>
						</ul>
						<div class="tab-content">
							<!-- Entidades Remisión-->
							<div class="tab-pane my-tabs active" id="tab_Enti">
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
												<td><b><a href="javascript: void(0)" style="color:#000;" onclick="fijarDestinatarioEnt('{{ $entidad->idEntidadRemision }}')">{{ $entidad->nombreEncargado }}</a></b></td>
												<td>{{ $entidad->nombreEntidad }}</td>		
												<td>{{ $entidad->direccionEntidad }}</td>
											</tr>
										@endforeach  
						            </tbody>
						      	</table>
							</div>
							<!-- Entidades Remisión-->
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
	<!-- # box-->

	<!-- modalBuscarSelProceso -->
	<div class="modal fade in" id="modalBuscarSelProceso">
		<div class="modal-dialog sm" style="width:94%;">
			<div class="modal-content">    
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title"><i class="fa fa-search"></i> Procesos</h4>
				</div>

				<div class="modal-body">
					<fieldset>
						{{''; $vigenciaActual = date("Y");}}     
						<div class="row"> 
						    <div class="col-xs-6">
								<label class="pull-right" style="margin-top:10px;">Vigencia:</label>
						    </div>
						    <div class="col-xs-1" style="padding:0px;">              
						      	<select class="form-control" onchange="buscarSelProcesoVigencia(this.value);" style="width:110%;">
						        	<option value='{{ $vigenciaActual }}'>{{ $vigenciaActual }}</option>
						            <?php 
						                for ($i=2014; $i<=$vigenciaActual; $i++) 
						                {
						                  echo "<option value='$i'>$i</option>";
						                }  
						            ?>
						      	</select>
						    </div>
						</div> 
						<div class="row">
							<!-- resultadoBuscarSelProceso -->
							<div class="col-xs-12" id="resultadoBuscarSelProceso">
								<!-- CARGA AJAX -->								
							</div>
							<!-- # resultadoBuscarSelProceso -->
						</div>   
					</fieldset>                        
				</div> 

				<div class="modal-footer">		      		
		        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
		      	</div>

			</div>
		</div>
	</div>
	<!-- # modalBuscarSelProceso -->
@stop
	<!--scriptsFin-->
@section('scriptsFin') 
<script>
$(document).ready(function() {
    $('#tablaEntidades').DataTable();
});

//CargarCiudad
function cargarCiudad(idDepartamento)
{ 
	if(idDepartamento != 'default')
	{
	    var loader = '<img src="{{ asset("img/loading.gif") }}">'; 
		var ruta = "{{URL::to('procesos/cargarCiudad/')}}";
	    var parametros = {"idDepartamento" : idDepartamento};
	      
	    $.ajax({                
	            data:  parametros,
	            url:   ruta,
	            type:  'post',
	            success:  function (responseText) { 
	            	$('#resultadoCargarCiudad').html(responseText); 
	            	//Initialize Select2 Elements
					$(".select2").select2();  
	            },
	            error: function (responseText) {
	            	playAudio('fail');
	              	alertify.error("Error /#870");
	            }
	    });	
    }
}

function fijarDestinatarioEnt(idEntidad)
{
	$("#idEntidadSeleccionada").val(idEntidad);

	var loader = '<img src="{{ asset("img/loading.gif") }}">';  

	var ruta = "{{URL::to('procesos/fijarDestinatarioEntRem/')}}";

    var parametros = { 
        "idEntidad" : idEntidad
      };
      
    $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            beforeSend: function(responseText) {
			    $('#resultadoDestino').html('<p style="margin-top:10px; width:100%; text-align:center;">' +loader + '</p>');
			},
            success:  function (responseText) { 
            	$('#resultadoDestino').html(responseText); 
            	//Initialize Select2 Elements
				$(".select2").select2();               
            },
            error: function (responseText) {
              	alertify.error("Error /#878");
            }
    });	
}
</script>
@stop