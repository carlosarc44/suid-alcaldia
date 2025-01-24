@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>ACUMULAR QUEJA</h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li><a href="javascript: void(0)">Quejas</a></li>
	<li class="active">Acumular Queja</li>
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
					<h3 class="profile-username text-center">{{$queja->nombreOrigenQueja." ".$queja->idQueja}}</h3>
					<p class="text-muted text-center">del {{Util::formatearFecha($queja->fechaQueja)}}</p>
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Esta Queja debe ser acumulada al siguiente proceso:</b> <span class="pull-right"></span>
							<input type="text" class="form-control" style="width: 20%; font-weight: bold; font-size: 1.5em;" id="proceso" onclick="buscarSelProceso({{date('Y')}});">
						</li>
						<li class="list-group-item">
							<b>Por el siguiente motivo:</b> <span class="pull-right">
							</span>
							<textarea id="motivo" rows="5" class="form-control"></textarea>
						</li>
					</ul>

					<button type="button" class="btn btn-success btn-sm pull-right" onclick="validarAcumularQueja('{{$queja->idQueja}}');"><i class="fa fa-save"></i> Acumular Queja</button>

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
<script>
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
}
</script>
@stop