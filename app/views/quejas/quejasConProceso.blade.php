@extends('plantillas.layout3')
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
<h1>QUEJAS CON PROCESO ASIGNADO<small><span id="tituloFecha">Recibidas durante la vigencia {{date("Y")}}</span></small></h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="{{asset('/inicio')}}"><i class="fa fa-home"></i> Inicio</a></li>
	<li class="active">Todas las Quejas</li>
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
		<h3 class="box-title">
			{{''; $vigenciaActual = date("Y");}}     
            <div class="row"> 
                <div class="col-xs-7">
					<label class="pull-right" style="margin-top:10px;">Vigencia:</label>
                </div>
                <div class="col-xs-5" style="padding:0px;">              
                  	<select class="form-control" onchange="cargarQuejas(this.value);" style="width:110%;">
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
		</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body" style="display: block;" id="resultadoQuejas">
		<!-- resultadoQuejas -->                                            
      	<div id="resultadoQuejas">                   		
       		<!-- CARGA AJAX-->                   		
		</div>
		<!-- #resultadoQuejas -->		
	</div>
</div>

<!-- MODAL -->
<div class="modal fade in" id="modalVerQueja">
  <div class="modal-dialog" style="width:96%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Ver Queja</h4>
      </div>
      <div class="modal-body" style="background:#f0f0f0;">
        <!-- resultadoVerQueja -->
		<div id="resultadoVerQueja">
			<!-- CONTENIDO AJAX --> 
		</div>
		<!-- # resultadoVerQueja -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<script src="{{asset('js/checkBo.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
	var vigencia = "<?php echo date('Y'); ?>";
	cargarQuejas(vigencia);
});

function cargarQuejas(vigencia) 
{ 
    var loader = '<img src="{{ asset("img/loading.gif") }}">';

    $(resultadoQuejas).html('<p style="margin-top:80px; width:100%; text-align:center;">'+loader+'</p>');

    var ruta = "{{URL::to('quejas/traerQuejasConProceso/')}}";
      
    ajax=objetoAjax();
    ajax.open("GET", ruta +"/"+ vigencia, true); 
    ajax.onreadystatechange=function()
    {
        if(ajax.readyState == 4)
        { 
            $("#tituloFecha").html("Recibidas durante la vigencia "+vigencia);
            resultadoQuejas.innerHTML=ajax.responseText;
            //Tabla quejas
      			$('#tablaQuejas').DataTable({
      			   	'iDisplayLength': 100
      			}); 
        }
    }
    ajax.send(null);
}



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