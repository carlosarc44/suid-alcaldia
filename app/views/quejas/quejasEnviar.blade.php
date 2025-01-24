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
</style>
{{ HTML::script('js/ajax.js') }}
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>QUEJAS PARA ENVIAR A REPARTO</h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li class="active">Quejas para reparto</li>
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
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Quejas Radicadas</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body" style="display: block;" id="resultadoQuejas">
		@if(count($quejas) > 0)	
			<div class="row">
				<div class="col-xs-12">
					<small class="label pull-right bg-blue">{{count($quejas)}} 
						@if(count($quejas)==1)
							queja
						@else
							quejas
						@endif
					</small>
					<form name="f1" id="f1">
				      	<table id="tablaQuejas" class="table table-bordered table-hover table-striped">
				            <thead>
				                <tr>
				                 	<th width="80">Queja</th>
									<th width="110">Fecha Queja</th>
									<th>Oficio</th>
									<th>Quejoso / Informante</th>
									<th>Presunto Responsable</th>
									<th width="20"></th>
				                </tr>
				            </thead>
				            <tbody>
				           		@foreach ($quejas as $queja)							
									<tr>
										<td class="text-center">
											<a class="label-rdo" style="cursor: pointer;" onclick="mostrarQueja({{$queja->idQueja}})">{{ $queja->nombreOrigenQueja." ".$queja->idQueja }}</a>
										</td>
										<td>{{ $queja->fechaQueja }}</td>
										<td>{{ $queja->numeroOficio }}</td>
										<td>
											{{""; 
												$quejosos = Util::traerQuejososPorQueja($queja->idQueja);
											}}
											<div class="ajax-listaQuejosos_2_{{$queja->idQueja}}">
												@if(count($quejosos) > 0)
													<ul class="list-group list-group-unbordered">
														@foreach($quejosos as $quejoso)
															<li class="list-group-item" style="padding:6px">
																<b>{{$quejoso->nombre}}</b>
																<br>
																<span style="color:#888787;font-size:0.95em;">{{$quejoso->documentoPersona}}</span>
															</li>
														@endforeach
												   </ul>
												   <button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
												@else
													@if ($queja->anonimo == 1)
														<ul class="list-group list-group-unbordered">
															<li class="list-group-item" style="padding:6px">
																<b>ANÓNIMO</b>
																<br>
																<span style="color:#888787;font-size:0.95em;">Quejoso</span>
															</li>
														</ul>

														<button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
													@else
													
														<button type="button" class="btn btn-danger btn-xs btn-block" style="margin-top:6px" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
														<br>
														<button type="button" class="btn btn-default btn-xs btn-block" onclick="anonimo('{{$queja->idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>												
													@endif
												@endif
												<!--ajax-->
											</div>
										</td>
										<td>
											{{""; 					
												$presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);														
											}}
											<div class="ajax-listaPresuntosResponsables_2_{{$queja->idQueja}}">
												@if(count($presuntosresponsables) > 0)
													<ul class="list-group list-group-unbordered">
														@foreach($presuntosresponsables as $presuntoresponsable)
															<li class="list-group-item" style="padding:6px">
																<b>{{$presuntoresponsable->nombre}}</b>
																<br>
																<span style="color:#888787;font-size:0.95em;">{{$presuntoresponsable->documentoPersona}}</span>
															</li>
														@endforeach
													</ul>
													<button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
												@else
													@if ($queja->porDeterminar == 1)
														<ul class="list-group list-group-unbordered">
															<li class="list-group-item" style="padding:6px">
																<b>POR DETERMINAR</b>
																<br>
																<span style="color:#888787;font-size:0.95em;">Presunto Responsable</span>
															</li>
														</ul>

														<button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
													@else
													
														<button type="button" class="btn btn-danger btn-xs btn-block" style="margin-top:6px" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Agregar Presunto Responsable</button>
														<br>
														<button type="button" class="btn btn-default btn-xs btn-block" onclick="porDeterminar('{{$queja->idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>											
													@endif
												@endif
											</div>
										</td>
										<td>
											<label class="cb-checkbox">
											  	<input type="checkbox" name='seleccion[]'  value="{{$queja->idQueja}}">	
											</label>										
										</td>
									</tr>
								@endforeach  
				            </tbody>
				      	</table>
				    </form>	
				</div>
			</div>
			<button type="button" class="btn btn-success btn-sm pull-right" onclick="enviarSeleccionadas();"><i class="fa fa-external-link"></i> Enviar Seleccionadas</button>
		@else
			<div class="alert alert-white alert-dismissible">
                <h5><i class="icon fa fa-info"></i><b>Atención</b></h5>
                No se encontraron quejas radicadas para enviar a reparto.
            </div>
		@endif
	</div>
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<script src="{{asset('js/checkBo.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">


$(window).load(function() {
    //Checkbox
    $('form').checkBo();  
});

//Funciones
function enviarSeleccionadas()
{
	
	$('#f1').submit(function() {
	  return false;
	});
	//Checkbox seleccionados
	var values = new Array();
	$("#f1 input[type=checkbox]:checked").each(function(){
		//cada elemento seleccionado
		values.push($(this).val());
	});

	if(values.length == 0)
	{
	    playAudio('fail');
    	/* # MESSAGE BOX */ 
    	alertify.error("<p>Seleccione las quejas que desea enviar a reparto.</p>");
		return false;
	}

    var jsonSeleccionados = JSON.stringify(values);
    //----------------------

    var ruta = "{{URL::to('quejas/enviarSeleccionadas/')}}";
	var loader = '<img src="{{ asset("img/loading.gif") }}">';

	var parametros = {  
	    "jsonSeleccionados" : jsonSeleccionados
	  };
  
    $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            beforeSend: function () {              
               $(resultadoQuejas).html('<p style="margin-top:10px; width:100%; text-align:center;">'+loader+'</p>');
            },
            success:  function (responseText) {
               $(resultadoQuejas).html(responseText); 
               //Checkbox
    		   $('form').checkBo();  
               //Mensade de notificación ------------------------------------------
               playAudio('alert');    
               alertify.success("El envío se realizó correctamente.");
            },
            error: function (responseText) {
              alert("Error quejasEnviar/#168 " + responseText)
        }
    });
}
</script>
@stop