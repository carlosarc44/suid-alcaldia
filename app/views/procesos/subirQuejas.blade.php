@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>ARCHIVOS QUEJAS</h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li><a href="javascript: void(0)">Quejas</a></li>
	<li class="active">Subir Archivos Quejas</li>
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
				<div class="col-md-8 col-md-offset-2">
					
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">													
							<div class="row">
								<div class="col-sm-2">
						        	<label class="pull-right">Proceso:</label>
						      	</div>
						      	<div class="col-sm-4" style="padding:0px;">              
						      		<input type="text" class="form-control" style="width: 50%; font-weight: bold; font-size: 1.5em;" id="proceso" onclick="buscarSelProceso({{date('Y')}});" autocomplete="off" readonly >
						      	</div>
						    </div>	
							
							<div class="row" style="margin-top: 10px;">
						      	<div class="col-sm-12">
						        	<div class="row" id="resultadoEtapaProceso">
						        		<!-- CARGA AJAX -->
						        	</div>
						      	</div>
							</div>	
						</li>
						<li class="list-group-item">							
							<fieldset>
								<h3><i class="fa fa-file-pdf-o"></i> Archivo queja </h3>
							    <div class="row" style="margin-bottom: 20px;">
							      	<div class="col-xs-12">
							        	<p><code>Seleccione un archivo en formato pdf</code></p> 
							      	</div>
							    </div>
							  		    
							    <div class="row">
							      	<div class="col-xs-12">      
							        	<form action = "javascript:;" enctype="multipart/form-data" id="formularioQueja" class="form-horizontal">
							            	<div class="form-group">
							              		<div class="col-md-12">  
							                		<input type="file" class="file" data-preview-file-type="any" id="archivoQueja" name="archivoQueja" accept="application/pdf">
							              		</div>                                           
							            	</div>
							        	</form>
							      	</div>
							    </div>
							</fieldset>	
							<button type="button" class="btn btn-info pull-right" style="margin:22px 0 4px 0;" onclick="validarSubirQueja()"><li class="fa fa-cloud-upload" style="color:#fff;"></li> Confirmar</button>						
						</li>
					</ul>
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

@section("scriptsFin")
<script type="text/javascript" src="{{ asset('js/fileinput.min.js') }}"></script>
<script>
$(document).ready(function() {
    $("input[type=file]").fileinput({
        showUpload: true,
        maxFileCount: 1
    });
});

function buscarSelProceso(vigencia)
{
  	$("#modalBuscarSelProceso").modal('show');  
  	buscarSelProcesoVigencia(vigencia);
}

function buscarSelProcesoVigencia(vigencia)
{
  	var ruta = "{{URL::to('procesos/mostrarBuscarSelProceso/')}}";
  	var loader = '<img src="{{ asset("img/loading.gif") }}">';
  	var parametros = {
  		"vigencia" : vigencia
  	};
 
  	$.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            beforeSend: function(){
            	$("#resultadoBuscarSelProceso").html("<p style='width:100%; margin-top:20px; text-align:center;'>"+loader+"</p>");        		   
            },
            success:  function (responseText) {
              	$("#resultadoBuscarSelProceso").html(responseText);  
              	$('#tablaProcesos').DataTable({
				   	'iDisplayLength': 50,
				   	"aaSorting": [[0, "asc"]],				   
					columnDefs: [
					       { type: 'natural-nohtml', targets: 0 }
					     ]
				});      		   
          	},
           error: function (responseText) {
             alert("Error/#94" + responseText)
        }
   	});
}

function seleccionadoProceso(proceso)
{
	//Oculta la ventana modal
    $("#modalBuscarSelProceso").modal('hide');  
	$("#proceso").val(proceso);
	$("#motivo").focus();  

	var ruta = "{{URL::to('procesos/cargarEtapasProceso/')}}";
  	var loader = '<img src="{{ asset("img/loading.gif") }}">';
  	var parametros = {
  		"proceso" : proceso
  	};
 
  	$.ajax({                
        data:  parametros,
        url:   ruta,
        type:  'post',
        success:  function (responseText) {
           $("#resultadoEtapaProceso").html(responseText);
    	   playAudio('alert');    		
      	},
        error: function (responseText) {
         	alert("Error/#208" + responseText)
    	}
   	});
}

function validarSubirQueja()
{
	var proceso = $("#proceso").val();
	var etapa = $("#etapaExterno").val();
	
	if(proceso == "")
	{
		playAudio('fail');
		alertify.error("Seleccione un proceso"); 
    	$("#proceso").focus();
    	return;
	}
	else if(etapa == "default")
	{
		playAudio('fail');
		alertify.error("Seleccione la etapa"); 
    	$("#etapaExterno").focus();
    	return;
	}  	

  	var fileArchivoQueja = document.getElementById("archivoQueja").value; 
  	
  	if(fileArchivoQueja == "")
  	{
   		alertify.error("Seleccione el archivo");
   		playAudio('fail');      
   		return false;
  	} 

  	//información del formulario
  	var formData = new FormData($("#formularioQueja")[0]); 
    formData.append("proceso", proceso);
    formData.append("etapa", etapa);
  
  	var ruta = "{{URL::to('procesos/subirArchivoQueja/')}}";
  	var loader = '<img src="{{ asset("img/loaders/default.gif") }}">';

  	//hacemos la petición ajax  
  	$.ajax({
	    url: ruta,  
	    type: 'POST',
	    // Form data
	    //datos del formulario
	    data: formData,
	    //necesario para subir archivos via ajax
	    cache: false,
	    contentType: false,
	    processData: false,
	    //mientras enviamos el archivo
	    beforeSend: function(){            
	       	$('#resultadoAgregarArchivos').html('<p style="margin-top:10px; width:100%; text-align:center;">'+loader+'</p>');
	    },
	    //una vez finalizado correctamente
	    success:  function (responseText) {
	        if(responseText == 0)
			{
	        	playAudio('alert');
	        	alertify.success("El archivo se agregó correctamente");
	        }
	        else
	        {
	        	playAudio('fail');
	        	alertify.error("Hubo un error al subir el archivo.  Inténtelo nuevamente.");
	        }
	        //Limpia los campos
	        $("#proceso").val("");
			$("#etapaExterno").val("default");
			$(".fileinput-remove").click();
	    },
	    //si ha ocurrido un error
	    error: function(){
	       	playAudio('fail');
	        alertify.error("Error #983");
	    }
    });    
}
</script>
@stop