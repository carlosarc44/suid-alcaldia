@extends('plantillas.layout2')
<!--includes de la cabecera-->
@section('cabecera')
<link rel="stylesheet" type="text/css" href="{{asset('css/inettuts.css')}}"/>
{{ HTML::script('js/ajax.js') }}
{{ HTML::script('js/jquery.js') }}
@stop
<!--includes de la cabecera-->

<!--menu lateral izquierdo-->
@section('menuLateral') 
  @include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
  <div id="headRoute">
    <h1>Envíos</h1>
    <div class="row" style="position:absolute; top:64px; right:10px;width:100%;">       
        <div class="col-xs-12"> 
            <button class="btn btn-info btn-rounded pull-right" onclick="guardarReparto();"><span class="fa fa-save"></span> Guardar este Reparto</button>
        </div>
    </div>
</div>

<div id="columnsRoute">
    <ul class="column"> 
        <div style="color:#2ac7e1; margin:15px 0;">
        	<span style="color:#2ac7e1;">QUEJAS PARA REPARTO</span> 	
        </div>
        @foreach ($quejas as $queja)
            <li class="widgetRoute color-interno" id='{{ $queja->idQueja }}'>  
                <div class="widget-head">
                    <span>
                        <span style="font-family:Arial; font-size:13px;">QUEJA</span> {{ $queja->idQueja }}
                        <a href="javascript: void(0)" onclick="verQueja({{$queja->idQueja}})" class="pull-right" style="color:#444; font-size:14px; position:relative;">
                        	<i class="fa fa-eye"></i>
                        </a>
                    </span>
                </div>
                <div class="widget-content">
					{{""; 
						$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);
						$quejosos = Util::traerQuejososPorQueja($queja->idQueja);
					}}
                	@if (count($quejosos) > 0)
            			@foreach ($quejosos as $quejoso)
		                    <p>{{strtoupper($quejoso->nombre)}}</p>
		            	@endforeach 
		            @else
		            	<div class="alert alert-white alert-dismissible" style="margin:0px; height:26px; padding:6px;">
			                <h4 style="font-size:0.95em;"><b>Quejoso no agregado</b></h4>
			            </div>
					@endif
                	@if (count($presuntos) > 0)
            			@foreach ($presuntos as $presunto)
		                    <p style="color:#2ac7e1">{{strtoupper($presunto->nombre)}}</p>
		            	@endforeach 
		            @else
		            	<div class="alert alert-white alert-dismissible" style="margin:0px; height:26px; padding:6px;">
			                <h4 style="font-size:0.95em;"><b>Presunto Responsable por determinar</b></h4>
			            </div>
					@endif
                </div>
            </li>
        @endforeach           
        </ul>
        
        {{''; $col = 1;}}
	    @foreach ($abogados as $abogado) 
	        <ul id="column{{$col}}" class="column">
	            <div class="row cabecera">
	            	<div class="col-xs-2" style="padding:0;">
	            		{{''; $nombre_fichero = '../public/img/fotos/'.$abogado->documentoPersona.'.jpg' }}
                        @if(file_exists($nombre_fichero))
                        	<img src="{{ asset('img/fotos/'.$abogado->documentoPersona.'.jpg')}}" title="{{ $abogado->nombre; }}" class="image-round">
                        @else			                           
                            @if(Util::traerGenero($abogado->documentoPersona) == 'Femenino')
                                <img src="{{ asset('img/ella.png')}}" title="{{ $abogado->nombre; }}" class="image-round">
                            @else
                                <img src="{{ asset('img/el.png')}}" title="{{ $abogado->nombre; }}" class="image-round">
                			@endif
                        @endif
	            	</div>	
	            	<div class="col-xs-10" style="padding:0;">
	            		<div id="tituloColumn">{{$abogado->nombre}}</div>             		
	            	</div>
	            </div>
	        </ul>
	         {{''; $col++;}}
	    @endforeach     
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
	<!-- # MODAL -->
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<script type="text/javascript" src="{{ asset('js/jquery-1.2.6.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui-personalized-1.6rc2.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/inettuts.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
    $(document.body).addClass('sidebar-collapse');
});

function guardarReparto()
{ 
	//Cantidad de columnas que corresponden a la cantidad de abogados activos
    var columnas = "<?php echo count($abogados); ?>";

    //Recorre las columnas para almacenar en los vectores las quejas asignadas a cada abogado
    for (var i = 1; i <= columnas; i++) 
    {
    	//Cantidad de quejas por cada columna
    	var cantQuejas = $("#column"+i+" li").size();
    	//Arreglo para guardar las quejas
    	window["vector" + i] = [];
	    //Si se encontró al menos una queja
	    if(cantQuejas > 0)
	    {
	        //Inserta en el vector el id de cada queja
	        $("#column"+i+" li").each(function(n) {                
	            window["vector" + i].push($(this).attr("id"));
	        });                
	    }

	    //Convierte arreglos en formato JSON para ser enviados vía AJAX
    	window["json" + i] = JSON.stringify(window["vector" + i]);
    }
   
    //Parámetros que se van a enviar al controlador
    //var parametros = {"json1": json1};
    var parametros = {};

   //Recorre la cantidad de columnas
    for(var i = 1; i <= columnas; i++) 
    {
        //Agrega a la variable parámetros, los vectores convertidos en json
        parametros['json'+i] = window["json" + i];
    }

	//alert(JSON.stringify(parametros, null, 4)); return;

    var ruta = "{{URL::to('quejas/guardarReparto/')}}";
    $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            success:  function (responseText) { 
            //Mensaje de notificación ------------------------------------------
            playAudio('alert');    
            alertify.success("Reparto guardado.");
            window.location.reload();
            //------------------------------------------------------------------                                                  
            },
            error: function (responseText) {
              alert("Error quejasReparto/#136")
            }
    });    
}
</script>
@stop