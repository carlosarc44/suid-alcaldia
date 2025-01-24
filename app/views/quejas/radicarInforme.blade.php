@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
{{ HTML::script('js/ajax.js') }}
<style>
.example-modal .modal {
  position: relative;
  top: auto;
  bottom: auto;
  right: auto;
  left: auto;
  display: block;
  z-index: 1;
}

.example-modal .modal {
  background: transparent !important;
}
</style>
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>RADICAR INFORME<small> Registro de informes</small></h1>
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
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body" style="display: block;">
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label>Fecha del Informe</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="fechaQueja">
							</div>
						</div>
						<!-- /.form-group -->
					</div>

					<div class="col-xs-6">	
						<div class="form-group">
							<label>Fecha de recepción</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="fechaRecepcion">
							</div>
						</div>
						<!-- /.form-group -->
					</div>
				</div>
			</div>


			<!-- /.col -->
			<div class="col-md-6">
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label>Tipo de recepción</label>
							{{ Form::select('tipoRecepcion', array('default' => 'Seleccione..') + $lista_tiposRecepcion, 
							Input::old('tipoRecepcion'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'tipoRecepcion', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
						</div>
						<!-- /.form-group -->
					</div>

					<div class="col-xs-6">	
						<div class="form-group">
							<label>Oficio</label>
							<div class="input-group date">
								<input type="text" class="form-control pull-right" id="numeroOficio" placeholder="Número de Oficio">
							</div>
						</div>
						<!-- /.form-group -->
					</div>
				</div>				
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Presunta fecha ocurrencia</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control pull-right" id="presuntaFecha">
					</div>
				</div>
				<!-- /.form-group -->
			</div>

			<div class="col-md-9">	
				<div class="form-group">
					<label>presuntoLugar</label>
					<input type="text" class="form-control pull-right" id="presuntoLugar">
				</div>
				<!-- /.form-group -->
			</div>
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label>Presuntos hechos</label>
					<textarea id="presuntosHechos" class="form-control" rows="2" placeholder="Hacer una breve descripción de los presuntos hechos..."></textarea>
				</div>
				<!-- /.form-group -->
			</div>			
		</div>
		<!-- /.row -->
	</div>	
	<!-- /.box-body -->
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Presunto Responsable</h3>
				<div class="box-tools pull-right">
					{{ Form::select('tipoImplicado', array('default' => 'Seleccione..') + $lista_tiposImplicados, 
							2, array('class' => 'form-control', 'id'=>'tipoImplicado', 'style'=>'width:140px;', 'onchange'=>'cambiarTipoImplicado();')) }}
				</div>
			</div>
			<div class="box-body">
				<!-- /.form group -->
				<div class="row" id="contenedorIdentificadoPR" hidden>	
					<div class="col-xs-12">
						<div class="input-group input-group-sm" style="margin-bottom:8px;">
							<button type="button" onclick="agregarPR();" class="btn btn-block btn-info btn-xs pull-right"><i class="fa fa-user-plus"></i> Agregar Presunto Responsable</button>
						</div>
						<table class="table table-hover table-bordered" id="tablaPR" hidden>
							<thead>
								<tr>													
									<th width="50">id</th>
									<th>Nombre</th>
									<th>Documento</th>
									<th>Cargo</th>
									<th>Dependencia</th>
									<th width="20">Quitar</th>
								</tr>
							</thead>
							<tbody>
								<!-- CONTENIDO -->				
							</tbody>
						</table>							
					</div>
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>

	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Informante</h3>
			</div>
			<div class="box-body">
				<!-- /.form group -->
				<div class="row">	
					<div class="col-xs-12">
						<div class="input-group input-group-sm" style="margin-bottom:8px;">
							<button type="button" onclick="agregarInformante();" class="btn btn-block btn-info btn-xs pull-right"><i class="fa fa-user-plus"></i> Agregar Informante</button>
						</div>
						<table class="table table-hover table-bordered" id="tablaInformantes" hidden>
							<thead>
								<tr>													
									<th width="5%">Id</th>
									<th width="40%">Nombre</th>
									<th width="40%">Dirección</th>
									<th width="10%">Teléfono</th>
									<th width="5%">Quitar</th>
								</tr>
							</thead>
							<tbody>
								<!-- CONTENIDO -->				
							</tbody>
						</table>							
					</div>
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
		<button type="button" class="btn btn-success btn-sm pull-right" onclick="validarGuardarInforme();"><i class="fa fa-save"></i> Guardar Informe</button>
	</div>
</div>
<!-- /.row -->

<!-- MODAL -->
<div class="modal fade in" id="modalAgregarInformante">
  <div class="modal-dialog" style="width:90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Seleccionar Entidad</h4>
      </div>
      <div class="modal-body">
        <!-- resultadoAgregarInformante -->
		<div id="resultadoAgregarInformante">
			<!-- CONTENIDO AJAX --> 
		</div>
		<!-- # resultadoAgregarInformante -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- MODAL -->
<div class="modal fade in" id="modalAgregarPR">
   <div class="modal-dialog" style="width:90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Seleccionar Funcionario</h4>
      </div>
      <div class="modal-body">
        <!-- resultadoAgregarPR -->
		<div id="resultadoAgregarPR">
			<!-- CONTENIDO AJAX --> 
		</div>
		<!-- # resultadoAgregarPR -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- # MODAL -->
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
$(function () { 
	//Initialize Select2 Elements
	$(".select2").select2();

	//fechaQueja
	$('#fechaQueja').datepicker({
		autoclose: true,
		dateFormat: 'yyyy-mm-dd'
	});

	//fechaRecepcion
	$('#fechaRecepcion').datepicker({
		autoclose: true,
		dateFormat: "yyyy-mm-dd"
	});
	//presuntaFecha
	$('#presuntaFecha').datepicker({
		autoclose: true,
		dateFormat: "yyyy-mm-dd"
	});

	//iCheck for checkbox and radio inputs
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue'
	});
	//Red color scheme for iCheck
	$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		checkboxClass: 'icheckbox_minimal-red',
		radioClass: 'iradio_minimal-red'
	});
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
});

//Funciones
function validarGuardarInforme()
{
	//QUEJA
	var fechaQueja = $("#fechaQueja").val();
	var fechaRecepcion = $("#fechaRecepcion").val();
	var tipoRecepcion = $("#tipoRecepcion").val();
	var numeroOficio = $("#numeroOficio").val();
	var presuntaFecha = $("#presuntaFecha").val();
	var presuntoLugar = $("#presuntoLugar").val();
	var presuntosHechos = $("#presuntosHechos").val();
	//-----------------------------------------------

	var tipoImplicado = $("#tipoImplicado").val();

	if(fechaQueja == "")
	{
		playAudio('fail');
		alertify.error("Seleccione la fecha de la queja"); 
    	$("#fechaQueja").focus();
    	return;
	}
	else if(fechaRecepcion == "")
	{
		playAudio('fail');
		alertify.error("Seleccione la fecha de recepción"); 
    	$("#fechaRecepcion").focus();
    	return;
	}
	else if(tipoRecepcion == "default")
	{
		playAudio('fail');
		alertify.error("Seleccione el tipo de recepción"); 
    	$("#tipoRecepcion").focus();
    	return;
	}
	else if(presuntoLugar == "")
	{
		playAudio('fail');
		alertify.error("Ingrese el presunto lugar"); 
    	$("#presuntoLugar").focus();
    	return;
	}
	else if(presuntosHechos == "")
	{
		playAudio('fail');
		alertify.error("Ingrese los presuntos hechos"); 
    	$("#presuntosHechos").focus();
    	return;
	}	

	if(presuntaFecha == "")
	{
		presuntaFecha = "Por determinar";
	}	
	
	if($("#tipoImplicado").val() == 1)//Si se conoce el presunto responsable
	{
		var filasPR = $("#tablaPR tr").length;
	
		if(filasPR <= 1)//Si no seleccionado al menos un presunto responsable
		{
			alertify.error("Seleccione al menos un presunto responsable"); 
			//Muestra la ventana modal
	    	agregarPR();
	    	return;
		}//Si seleccionó al menos un presunto responsable 
		else
		{
			//PRESUNTOS RESPONSABLES
			var presuntosResponsables = [];
			var campoPR;
			$("#tablaPR tbody tr").each(function (index) 
		    {
		        
		        $(this).children("td").each(function (index2) 
		        {
		            switch (index2) 
		            {
		                case 0: 
		                campoPR = $(this).text();
		                break;
		            }
		        })
		        //Almacena el documento de cada Informante
		        presuntosResponsables.push(campoPR);
		    });
		    //----------------------------------------------
		    //Convierte arreglo en formato JSON para ser enviados vía AJAX
    		var jsonPR = JSON.stringify(presuntosResponsables);
		}
	}
	else//Si es por determinar
	{
		var jsonPR = null;
	}	
	
	var filasInformante = $("#tablaInformantes tr").length;

	if(filasInformante <= 1)//Si no seleccionado al menos un Informante
	{
		playAudio('fail');
		alertify.error("Seleccione al menos un Informante"); 
		//Muestra la ventana modal
    	agregarInformante();
    	return;
	}//Si seleccionó al menos un Informante 
	else
	{
		//InformanteS
	    var informantes = [];
	    var campoInformante;
		$("#tablaInformantes tbody tr").each(function (index) 
	    {		        
	        $(this).children("td").each(function (index2) 
	        {
	            switch (index2) 
	            {
	                case 0: 
	                campoInformante = $(this).text();
	                break;
	            }
	        })
	        //Almacena el documento de cada informante
	        informantes.push(campoInformante);
	    });
	    //---------------------------------------------

	    //Convierte arreglo en formato JSON para ser enviados vía AJAX
		var jsonInformantes = JSON.stringify(informantes);
	}	

	var ruta = "{{URL::to('quejas/guardarInforme/')}}";

    var parametros = { 
        "fechaQueja" : fechaQueja,
		"fechaRecepcion" : fechaRecepcion,
		"tipoRecepcion" : tipoRecepcion,
		"numeroOficio" : numeroOficio,
		"presuntaFecha" : presuntaFecha,
		"presuntoLugar" : presuntoLugar,
		"presuntosHechos" : presuntosHechos,
		"jsonPR" : jsonPR,
        "jsonInformantes" : jsonInformantes,
      };
      
    $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            success:  function (responseText) { 
            	if(responseText == 0)//0 No errores
            	{
            		playAudio('alert');
            		alertify.confirm('<b>Registro Exitoso<b>', 'Desea ver las quejas pendientes de enviar a reparto?', function()
            			{ 
            				var ruta1 = "{{URL::to('quejas/quejasEnviar/')}}";
            				window.location.href = ruta1;
            			}
            		,function()
            			{                 				
            				var ruta2 = "{{URL::to('quejas/radicarQueja/')}}";
            				window.location.href = ruta2;
            			}
            		);
            	}
            	else
            	{
            		alertify.error("Error /#482");
            	}	                
            },
            error: function (responseText) {
              alertify.error("Error /#486");
            }
    });	
}

function cambiarTipoImplicado()
{
	if(document.getElementById("tipoImplicado").value == 1)
	{
		$('#contenedorIdentificadoPR').show();		
	}
	else
	{
		$('#contenedorIdentificadoPR').hide();		
	}
}

//agregarInformante
function agregarInformante()
{	
	$('#modalAgregarInformante').modal('show');
	var loader = '<img src="{{ asset("img/loading.gif") }}">';  

	$('#resultadoAgregarInformante').html('<p style="margin-top:10px; width:100%; text-align:center;">'+loader+'</p>');

	var ruta = "{{URL::to('quejas/showSelecInformante/')}}";

	ajax=objetoAjax();
	ajax.open("GET", ruta, true); 
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==4)
		{			
			resultadoAgregarInformante.innerHTML = ajax.responseText;          
			$('#tablaEntidades').DataTable();
		}
	}
	ajax.send(null);  
}

function agregarPR()
{	
	var loader = '<img src="{{ asset("img/loading.gif") }}">';  

	$('#modalAgregarPR').modal('show');

	$('#resultadoAgregarPR').html('<p style="margin-top:10px; width:100%; text-align:center;">'+loader+'</p>');

	var ruta = "{{URL::to('quejas/showSelecPR/')}}";

	ajax=objetoAjax();
	ajax.open("GET", ruta, true); 
	ajax.onreadystatechange=function()
	{
		if(ajax.readyState==4)
		{
			resultadoAgregarPR.innerHTML = ajax.responseText;          
			$('#tablaFuncionarios').DataTable();      
		}
	}
	ajax.send(null);  
}

//Borra la fila seleccionada en la tabla -----
$(".borrar").live('click', function(event) {
	$(this).parent().parent().remove();
});
//--------------------------------------------

function seleccionadoInformante(idEntidad, nombre, direccion, telefono)
{
	//$('#modalAgregarInformante').modal('hide');
	// Obtenemos el numero de filas (td) que tiene la primera columna
	// (tr) del id "tabla"

	$('#tablaInformantes').show();

	var tds = $("#tablaInformantes tr:first th").length;

	// Obtenemos el total de columnas (tr) del id "tabla"
	var trs=$("#tablaInformantes tr").length;
	var nuevaFila="<tr>";

	// añadimos las columnas
	nuevaFila+='<td>'+idEntidad+'</td>';	
	nuevaFila+='<td>'+nombre+'</td>';	
	nuevaFila+='<td>'+direccion+'</td>';
	nuevaFila+='<td>'+telefono+'</td>';
	nuevaFila+='<td style="padding:0;"><button type="button" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;" onclick="quitarFila($(this));"><i class="fa fa-trash"></i></button></td>';

	// Añadimos una columna con el numero total de columnas.
	// Añadimos uno al total, ya que cuando cargamos los valores para la
	// columna, todavia no esta añadida

	nuevaFila+="</tr>";
	$("#tablaInformantes").append(nuevaFila);    
}

function seleccionadoPR(idFuncionario, nombre, documento, cargo, dependencia)
{
	//$('#modalAgregarPR').modal('hide');
	// Obtenemos el numero de filas (td) que tiene la primera columna
	// (tr) del id "tabla"

	$('#tablaPR').show();
	var tds = $("#tablaPR tr:first th").length;

	// Obtenemos el total de columnas (tr) del id "tabla"
	var trs=$("#tablaPR tr").length;
	var nuevaFila="<tr>";

	// añadimos las columnas
	nuevaFila+='<td>'+idFuncionario+'</td>';
	nuevaFila+='<td>'+nombre+'</td>';
	nuevaFila+='<td>'+documento+'</td>';
	nuevaFila+='<td>'+cargo+'</td>';
	nuevaFila+='<td>'+dependencia+'</td>';
	nuevaFila+='<td style="padding:0;"><button type="button" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;" onclick="quitarFila($(this));"><i class="fa fa-trash"></i></button></td>';

	// Añadimos una columna con el numero total de columnas.
	// Añadimos uno al total, ya que cuando cargamos los valores para la
	// columna, todavia no esta añadida

	nuevaFila+="</tr>";
	$("#tablaPR").append(nuevaFila);    
}

$(".borrar").live('click', function(event) {
	$(this).parent().parent().remove();
});

//fechaQueja
$('#fechaQueja').pickmeup({
	position    : 'left',
	hide_on_select  : true,
	format  : 'Y-m-d'
});

//fechaRecepcion
$('#fechaRecepcion').pickmeup({
	position    : 'left',
	hide_on_select  : true,
	format  : 'Y-m-d'
});   

function quitarFila(row)
{
	row.closest('tr').remove();
}
</script>
@stop
<!--# scriptsFin -->