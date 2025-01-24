@extends('plantillas.layout3')
<!--includes de la cabecera-->
@section('cabecera')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('css/checkBo.css')}}">
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
<h1>TRASLADAR PROCESOS</h1>
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
				<div class="col-sm-4">
					<div class="row">	
						<div class="col-md-4">
							<label class="pull-right" style="font-size:0.9em;">Abogado Origen:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								{{ Form::select('abogados', array('default' => 'Seleccione abogado..') + $lista_abogados, null, array('class' => 'form-control select2 select2-hidden-accessible', 'onchange' => 'procesosAbogado(this.value)', 'id'=>'idAbogadoOrigen', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
							</div>
							<!-- /.form-group -->
	                    </div>
					</div>
					<div class="row">	
						<div class="col-md-4">
							<label class="pull-right" style="font-size:0.9em;">Abogado Destino:</label>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								{{ Form::select('abogados', array('default' => 'Seleccione abogado..') + $lista_abogados, null, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'idAbogadoDestino', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
							</div>
							<!-- /.form-group -->
	                    </div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label class="pull-right" style="font-size:0.9em;">Motivo Traslado:</label>
						</div>
						<div class="col-md-8">
							<textarea id="motivo" rows="2" class="form-control"></textarea>
						</div>
					</div>					
					<hr>
					<button type="button" class="btn btn-success btn-sm pull-right" onclick="enviarSeleccionadas();"><i class="fa fa-save"></i> Trasladar</button>

				</div>
				<div class="col-sm-8">						
					<!-- resultadoProcesos -->
					<div class="tab-pane my-tabs active" id="resultadoProcesos">
						<!-- CARGA AJAX -->
					</div>
					<!-- # resultadoProcesos-->
				</div>
			</div>			
		</div>
	</div>
	<!-- # box-->
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<script src="{{asset('js/checkBo.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script>
$(document).ready(function() {
    $(".select2").select2();

});

function marcarTodo()
{	
    $(".check").each(function(){
        $(this).prop('checked',true);
    });
}

function desmarcarTodo()
{
    $(".check").each(function(){
        $(this).prop('checked',false);
    });
}

//procesosAbogado
function procesosAbogado(idAbogado)
{ 
	if(idAbogado != 'default')
	{ 
	    var loader = '<img src="{{ asset("img/loading.gif") }}">'; 
		var ruta = "{{URL::to('procesos/cargarProcesoAbogado/')}}";
	    var parametros = {"idAbogado" : idAbogado};
	      
	    $.ajax({                
	            data:  parametros,
	            url:   ruta,
	            type:  'post',
	            beforeSend: function(responseText) {
				    $('#resultadoProcesos').html('<p style="margin-top:10px; width:100%; text-align:center;">'+loader+'</p>');
				},
	            success:  function (responseText) { 
	            	$('#resultadoProcesos').html(responseText); 
	            	$('form').checkBo();
	            },
	            error: function (responseText) {
	            	playAudio('fail');
	              	alertify.error("Error /#870");
	            }
	    });	
    }
    else
    {
    	playAudio('fail');
	    alertify.error("Seleccione el abogado de origen");
    }
}

function enviarSeleccionadas()
{	
	var motivo = $("#motivo").val();
	var idAbogadoOrigen = $("#idAbogadoOrigen").val();
	var idAbogadoDestino = $("#idAbogadoDestino").val();

	if(idAbogadoOrigen == "default")
	{
		playAudio('fail');
		alertify.error("Seleccione el abogado de origen"); 
    	$("#idAbogadoOrigen").focus();
    	return;
	}
	else if(idAbogadoDestino == "default")
	{
		playAudio('fail');
		alertify.error("Seleccione el abogado de destino"); 
    	$("#idAbogadoDestino").focus();
    	return;
	}
	else if(idAbogadoOrigen == idAbogadoDestino)
	{
		playAudio('fail');
		alertify.error("No puede seleccionar el mismo profesional"); 
    	$("#idAbogadoOrigen").focus();
    	return;
	}
	else if(motivo == "")
	{
		playAudio('fail');
		alertify.error("Ingrese el motivo del traslado"); 
    	$("#motivo").focus();
    	return;
	}	
	
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
    	alertify.error("<p>Seleccione el o los procesos que desea trasladar.</p>");
		return false;
	}

    var jsonSeleccionados = JSON.stringify(values);    
    //----------------------

    var ruta = "{{URL::to('procesos/trasladarProcesos/')}}";
	var loader = '<img src="{{ asset("img/loading.gif") }}">';

	var parametros = {  
	    "idAbogadoOrigen" : idAbogadoOrigen,
	    "idAbogadoDestino" : idAbogadoDestino,
	    "motivo" : motivo,
	    "jsonSeleccionados" : jsonSeleccionados
	  };
  
    $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            beforeSend: function () {              
               $(resultadoProcesos).html('<p style="margin-top:10px; width:100%; text-align:center;">'+loader+'</p>');
            },
            success:  function (responseText) {
                $('#resultadoProcesos').html(responseText); 
            	$('form').checkBo();
            	$('#tablaProcesos').DataTable({
				    	'iDisplayLength': 10,
				    	'order': [[0, "asc"]]
				}); 

               //Mensade de notificación ------------------------------------------
               playAudio('alert');    
               alertify.success("El traslado se realizó correctamente.");
            },
            error: function (responseText) {
              alert("Error #201 " + responseText)
        }
    });
}
</script>
@stop