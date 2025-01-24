@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('css/checkBo.css')}}">
<style type="text/css">
fieldset{
	border: 1px solid #c4c4c4;
	border-radius: 10px;
	padding: 20px;
	background: #f0f0f0;
}
.table tr td{
	vertical-align:middle !important;
}
</style>
{{ HTML::script('js/ajax.js') }}
@stop
<!--includes de la cabecera-->

<!--menu lateral izquierdo-->
@section('menuLateral') 
@include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
<div class="row" style="background: #275ec6;display:none">
    <img src="{{asset('img/banner-numeracion-autos.png')}}" style="width: 70%; height:250px;" class="pull-right">
</div>
<br>
<div class="row">
	<div class="col-md-3" id="resultadoNumeracion">
		<!-- ajax -->		
	</div>
	<!-- /.col -->
	<div class="col-md-9">
		<div class="box box-gray">
			<div class="box-header with-border">
				<h3 class="box-title">Solicitudes de números</h3>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding">
					<!-- nav-tabs-custom-->
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab_1" data-toggle="tab">Procesos 2014 a {{date('Y')}}</a></li>
							<li><a href="#tab_3" data-toggle="tab" onclick="historicoAutos();">Histórico de Números</a></li>
						</ul>
						<div class="tab-content" style="min-height:240px; padding-top:20px;">
							<!-- Superiores a 2014 -->
							<div class="tab-pane my-tabs active" id="tab_1">					
								<div class="box-body table-responsive no-padding" id="resultadoAutos">
									<div class="table-responsive mailbox-messages">
										@if (count($autos)>0)												
											<table class="table table-hover table-striped" style="font-size:0.9em;">
												<tbody>
													@foreach ($autos as $auto)
														<tr>
															<td class="text-center" style="width: 5%">
																<a href="{{asset('/procesos/ver/'.$auto->Radicado_vigencia."/".$auto->Radicado_idRadicado)}}">
																	<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
																		{{ $auto->Radicado_vigencia."-".$auto->Radicado_idRadicado }}
																	</span>
																</a>
															</td>
															<td class="mailbox-subject" style="width: 20%">
																<b>{{$auto->nombreEtapa}}</b>
															</td>
															<td class="mailbox-subject" style="width: 20%">
																<b>{{$auto->tipoEtapa}}</b>
															</td>
															<td class="mailbox-subject" style="width: 30%">{{$auto->observaciones}}</td>
															<td class="mailbox-name" style="width: 10%">
																{{$auto->nombre}}
															</td>
															<td class="mailbox-date" style="width: 10%">
																{{
																	date_format(date_create($auto->fechaSolicitudAuto),"d/m/Y h:i a");
																}}
															</td>
															<td style="width: 5%">
																<button type="button" class="btn btn-info btn-sm pull-right btn-block" onclick="asignarNumero('{{$auto->idEtapa}}', '{{$auto->Radicado_vigencia}}', '{{$auto->Radicado_idRadicado}}', '{{$auto->idSolicitudAuto}}')"><i class="fa fa-undo"></i> Asignar</button>
																<button type="button" class="btn btn-default btn-sm pull-right btn-block" onclick="eliminarSolicitud('{{$auto->idSolicitudAuto}}')">
																 <i class="fa fa-trash"></i> Eliminar</button>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
											<!-- /.table -->
										@else
											<div class="alert alert-white alert-dismissible" style="margin:20px;">
									            <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
									            No se encontraron solicitudes de números de auto.
									        </div>
										@endif
									</div>
									<!-- /.mail-box-messages -->
								</div>
							</div>
							<!-- # Superiores a 2014 -->

							<!-- Histórico de números -->
							<div class="tab-pane my-tabs" id="tab_3">					
								<div class="box-body table-responsive no-padding">
									{{''; $vigenciaActual = date("Y");}}     
									<div class="row" style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 10px 0"> 
										<div class="col-xs-6">
											<label class="pull-right" style="margin-top:10px;">Números de autos de:</label>
										</div>
										<div class="col-xs-2" style="padding:0px;">              
											<select class="form-control" id="vigenciaAutos" onchange="historicoAutos();" style="width:80%; margin:4px 0;">
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
									<div id="resultadoHistorico">
										<!-- CARGA AJAX -->
									</div>
								</div>
							</div>
							<!-- # Histórico de números -->
						</div>
					</div>
					<!-- #nav-tabs-custom-->
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /. box -->
		</div>
		<!-- /.col -->
	</div>

<!-- MODAL -->
<div class="modal fade in" id="modalNumeroAntes">
	<input type="hidden" id="idEtapaAntes">
	<div class="modal-dialog sm" style="width:54%;">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">×</span></button>
	        	<h4 class="modal-title">Número de Auto <span id="tituloModal"></span></h4>
	      	</div>
	      	<div class="modal-body">
        		{{''; $vigenciaTope = 2013; }}     
                <div class="row" style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 6px 0; padding:10px 10px 0 10px;"> 
                    <div class="col-xs-2">
						<label class="pull-right" style="margin-top:10px;">Proceso:</label>
                    </div>
                    <div class="col-xs-2" style="padding:0px;">              
                      	<select class="form-control" id="vigenciaAntes" style="width:100%;">
                        <option value='{{ $vigenciaTope }}'>{{ $vigenciaTope }}</option>
                        <?php 
                            for ($i=2011; $i<=$vigenciaTope; $i++) 
                            {
                              echo "<option value='$i'>$i</option>";
                            }  
                            ?>
                      	</select>
                    </div>
                    <div class="col-xs-3">
						<input type="text" class="form-control" id="radicadoAntes" placeholder="Número Proceso" onkeypress="validate(event);" maxlength="4">
                    </div>
                   	<div class="col-xs-5">
						<div class="form-group">
							{{ Form::select('abogados', array('default' => 'Seleccione encargado..') + $lista_abogados, 
							Input::old('abogados'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'idAbogado', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
						</div>
						<!-- /.form-group -->
                    </div>
                </div>  
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" class="btn btn-info btn-sm pull-right" onclick="validarAsignarNumero();"><i class="fa fa-save"></i> Asignar Número de Auto</button>
	        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
	      	</div>
	    </div>
	    <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade in" id="modalNumeroEspecial">
	<input type="hidden" id="idEtapaEspecial">
	<div class="modal-dialog sm" style="width:54%;">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">×</span></button>
	        	<h4 class="modal-title">Número de Auto <span id="tituloModalEspecial"></span></h4>
	      	</div>
	      	<div class="modal-body">
        		{{''; $vigenciaTopeEspecial = date("Y"); }}     
                <div class="row" style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 6px 0; padding:10px 10px 0 10px;"> 
                    <div class="col-xs-2">
						<label class="pull-right" style="margin-top:10px;">Proceso:</label>
                    </div>
                    <div class="col-xs-2" style="padding:0px;">              
                      	<select class="form-control" id="vigenciaEspecial" style="width:100%;">
                        <option value='{{ $vigenciaTopeEspecial }}'>{{ $vigenciaTopeEspecial }}</option>
                        <?php 
                            for ($i=2011; $i<=$vigenciaTopeEspecial; $i++) 
                            {
                              echo "<option value='$i'>$i</option>";
                            }  
                            ?>
                      	</select>
                    </div>
                    <div class="col-xs-3">
						<input type="text" class="form-control" id="radicadoEspecial" placeholder="Número Proceso" onkeypress="validate(event);" maxlength="4">
                    </div>
                   	<div class="col-xs-5">
						<div class="form-group">
							{{ Form::select('abogados', array('default' => 'Seleccione encargado..') + $lista_abogados, 
							Input::old('abogados'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'idAbogadoEspecial', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
						</div>
						<!-- /.form-group -->
                    </div>
                </div>  
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" class="btn btn-info btn-sm pull-right" onclick="validarAsignarNumeroEspecial();"><i class="fa fa-save"></i> Asignar Número de Auto Especial</button>
	        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
	      	</div>
	    </div>
	    <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
$(window).load(function() {
	actualizarNumeracion()
	//Initialize Select2 Elements
	$(".select2").select2();
	//---------------------------
	$('#tablaActivos').DataTable({
		'iDisplayLength': 50,
		'order': [[0, "asc"]]
	});   
});

function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function asignarNumero(idEtapa, vigencia, idRadicado, idSolicitudAuto)
{ 
	var ruta = "{{URL::to('procesos/verificarNumero')}}";
	
	$.ajax({                
        data:  {idEtapa},
        url:   ruta,
        type:  'post',
        success:  function (responseText) { 
			//Pregunta
			Swal.fire({
				title: 'Asignar número de Auto '+responseText+'?',
				text: 'Al hacer clic se asignará el número de auto '+responseText+' al proceso '+vigencia+'-'+idRadicado,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Si, asignar auto',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.isConfirmed) {
					confirmarSolicitarNumeroAuto(idEtapa, vigencia, idRadicado, idSolicitudAuto);
				}
			});        	   
	    },
	    error: function (responseText) {
	      alertify.error(responseText);
	    }
	});	
	return false;     
}

function confirmarSolicitarNumeroAuto(idEtapa, vigencia, idRadicado, idSolicitudAuto) 
{
	var ruta = "{{URL::to('procesos/guardarAuto')}}";

	$.ajax({  					             
		data:  {idEtapa, vigencia, idRadicado, idSolicitudAuto},
		url:   ruta,
		type:  'post',
		success:  function (responseText) { 
			if(responseText == 1)//1 Error: ya está asignado un número de auto
			{
				playAudio('fail');
				alertify.error("Este proceso ya tiene asignado un número de auto para esta etapa");
			}
			else
			{
				playAudio('alert');
				alertify.success("El número se asignó correctamente");
				$("#resultadoAutos").html(responseText);
				//Actualiza el contenedor del contador de números
				actualizarNumeracion();
			}
		},
		error: function (responseText) {
			alertify.error("Error /#216");
		}
	});	
}

function actualizarNumeracion()
{
	$('#resultadoNumeracion').load("{{ asset('procesos/cargarNumeracion') }}");
}

function historicoAutos()
{
	const vigencia = $('#vigenciaAutos').val();

	var loader = '<img src="{{ asset("img/loading2.gif") }}">'; 
	var ruta = "{{URL::to('procesos/historico-autos/')}}";

    $.ajax({                
            url:   ruta,
			data: {vigencia},
            type:  'post',
            beforeSend: function(responseText) {
			    $('#resultadoHistorico').html('<p style="margin-top:70px; width:100%; text-align:center;">'+loader+'</p>');
			},
            success:  function (responseText) {   
            	resultadoHistorico.innerHTML = responseText;  
            	//tablaHistoricoAutos
			    $('#tablaHistoricoAutos').DataTable({
			    	'iDisplayLength': 50
			    });   	              
            },
            error: function (responseText) {
            	playAudio('fail');
              	alertify.error("Error /#263");
            }
    });	
}

function vigenciaHistorico()
{
	const vigencia = $('#vigenciaAutos').val();
	const loader = '<img src="{{ asset("img/loading2.gif") }}">'; 
	const ruta = "{{URL::to('procesos/vigenciaHistoricoAutos/')}}";

    $.ajax({   
    		data: {vigencia},             
            url:   ruta,
            type:  'post',
            beforeSend: function(responseText) {
			    $('#resultadoHistorico').html('<p style="margin-top:70px; width:100%; text-align:center;">'+loader+'</p>');
			},
            success:  function (responseText) {   
            	$('#resultadoHistorico').html(responseText);  
            	//tablaHistoricoAutos
			    $('#tablaHistoricoAutos').DataTable({
			    	'iDisplayLength': 50
			    });   	              
            },
            error: function (responseText) {
            	playAudio('fail');
              	alertify.error("Error /#263");
            }
    });	
}

function asignarNumeroAntes(idEtapa)
{
	switch(idEtapa) 
	{
	    case '1':
	        $('#tituloModal').html('Indagación Previa');	        
	        break;
	    case '2':
	        $('#tituloModal').html('Investigación Disciplinaria');
	        break;
	    case '5':
	        $('#tituloModal').html('Pliego de Cargos');
	        break;
	    case '8':
	        $('#tituloModal').html('Fallo');
	        break;
	    case '9':
	        $('#tituloModal').html('Inhibitorio');
	        break;
	    case '10':
	        $('#tituloModal').html('Archivo');
	        break;
	    default:
	       $('#tituloModal').html('Etapa no especificada');
	       break;
	}

	$('#idEtapaAntes').val(idEtapa);
	$('#modalNumeroAntes').modal('show');
	$('#radicadoAntes').focus();	
}

function asignarNumeroEspecial(idEtapa, nombreEtapa)
{ 
    $('#tituloModalEspecial').html(nombreEtapa);
	$('#idEtapaEspecial').val(idEtapa);
	$('#modalNumeroEspecial').modal('show');
	$('#radicadoEspecial').focus();	
}

function validarAsignarNumero()
{
	var idEtapa = $('#idEtapaAntes').val();
	var vigenciaAntes = $('#vigenciaAntes').val();
	var radicadoAntes = $('#radicadoAntes').val();
	var idAbogado = $('#idAbogado').val();

	if(radicadoAntes == "")
	{
		playAudio('fail');
		alertify.error("Ingrese el número del proceso iniciado antes de 2014"); 
    	$("#radicadoAntes").focus();
    	return;
	}
	else if(idAbogado == "default")
	{
		playAudio('fail');
		alertify.error("Seleccione el profesional responsable del proceso"); 
    	$("#idAbogado").focus();
    	return;
	}

	var loader = '<img src="{{ asset("img/loading2.gif") }}">'; 
	var ruta = "{{URL::to('procesos/guardarAutoAntes/')}}";

	var parametros = {
						"idEtapa" : idEtapa,
						"vigenciaAntes" : vigenciaAntes,
						"radicadoAntes" : radicadoAntes,
						"idAbogado" : idAbogado
					};

    // Ajax
    $.ajax({  					             
	        data:  parametros,
	        url:   ruta,
	        type:  'post',
	        success:  function (responseText) { 
	        	if(responseText == 1)//1 Error: ya está asignado un número de auto
	        	{
	        		playAudio('fail');
	        		alertify.error("Este proceso ya tiene asignado un número de auto para esta etapa");
	        	}
	        	else
	        	{
	        		//Oculta la ventana modal
	        		$('#modalNumeroAntes').modal('hide');
	        		playAudio('alert');
	        		alertify.success("El número se asignó correctamente");
	        		$("#resultadoAutos").html(responseText);
	        		//Actualiza el contenedor del contador de números
	        		actualizarNumeracion();
	        		//Limpia los campos de la ventana modal
	        		$("#radicadoAntes").val("");
	        		$("#idAbogado").val("");
	        	}
	        },
	        error: function (responseText) {
	          alertify.error("Error /#419");
	        }
	    });	
    // # Ajax
}

function validarAsignarNumeroEspecial()
{
	var idEtapa = $('#idEtapaEspecial').val();
	var vigenciaEspecial = $('#vigenciaEspecial').val();
	var radicadoEspecial = $('#radicadoEspecial').val();
	var idAbogadoEspecial = $('#idAbogadoEspecial').val();

	if(radicadoEspecial == "")
	{
		playAudio('fail');
		alertify.error("Ingrese el número del proceso"); 
    	$("#radicadoEspecial").focus();
    	return;
	}
	else if(idAbogado == "default")
	{
		playAudio('fail');
		alertify.error("Seleccione el profesional responsable del proceso"); 
    	$("#idAbogadoEspecial").focus();
    	return;
	}

	var loader = '<img src="{{ asset("img/loading2.gif") }}">'; 
	var ruta = "{{URL::to('procesos/guardarAutoEspecial/')}}";

	var parametros = {
						"idEtapa" : idEtapa,
						"vigenciaEspecial" : vigenciaEspecial,
						"radicadoEspecial" : radicadoEspecial,
						"idAbogadoEspecial" : idAbogadoEspecial
					};

    // Ajax
    $.ajax({  					             
	        data:  parametros,
	        url:   ruta,
	        type:  'post',
	        success:  function (responseText) { 
	        	if(responseText == 1)//1 Error: ya está asignado un número de auto
	        	{
	        		playAudio('fail');
	        		alertify.error("Este proceso ya tiene asignado un número de auto para esta etapa");
	        	}
	        	else
	        	{
	        		//Oculta la ventana modal
	        		$('#modalNumeroEspecial').modal('hide');
	        		playAudio('alert');
	        		alertify.success("El número se asignó correctamente");
	        		//Actualiza el contenedor del contador de números
	        		actualizarNumeracion();
	        		//Limpia los campos de la ventana modal
	        		$("#radicadoEspecial").val("");
	        		$("#idAbogadoEspecial").val("");
	        	}
	        },
	        error: function (responseText) {
	          alertify.error("#587<");
	        }
	    });	
    // # Ajax
}
</script>
@stop