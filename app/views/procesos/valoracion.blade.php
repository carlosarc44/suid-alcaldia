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
    <h1>Procesos</h1>
    <div class="row" style="position:absolute; top:64px; right:10px;width:100%;">       
        <div class="col-xs-12"> 
            <button class="btn btn-info btn-rounded pull-right" onclick="guardarValoracion();"><span class="fa fa-save"></span> Guardar esta Valoración</button>
        </div>
    </div>
</div>

<div id="columnsRoute">
    <ul class="column"> 
        <div style="color:#2ac7e1; margin:15px 0;">
        	<span style="color:#2ac7e1;">PROCESOS PARA VALORAR</span> 	
        </div>  
        @foreach ($procesos as $proceso)
            {{'';   $numProceso = $proceso->vigencia."-".$proceso->idRadicado;
                    $queja = DB::table('acumulaqueja')
                             ->where('Radicado_idRadicado', '=', $proceso->idRadicado)
                             ->where('Radicado_vigencia', '=', $proceso->vigencia)
                             ->get();

                    if(count($queja) > 0)
                    {
                       $numQueja = $queja[0]->Queja_idQueja;
                    }
                    else
                    {
                        $numQueja = 0;
                    }
            }}
            <li class="widgetRoute color-interno" id='{{ $numProceso }}'>  
                <div class="widget-head">
                    <span>
                        <span style="font-family:Arial; font-size:13px;">PROCESO</span> {{ $numProceso }}
                        <a href="javascript: void(0)" onclick="verQueja({{$numQueja}})" class="pull-right" style="color:#444; font-size:14px; position:relative;">
                            <i class="fa fa-eye"></i>
                        </a>
                    </span>
                </div>
                <div class="widget-content">
                    {{""; 
                        $presuntos = Util::traerPresuntosResponsablesPorQueja($numQueja);
                    }}
                    @if (count($presuntos) > 0)
                        @foreach ($presuntos as $presunto)
                            <p>{{$presunto->nombre}}</p>
                        @endforeach 
                    @else
                        <div class="alert alert-white alert-dismissible" style="margin:0px; height:26px; padding:6px;">
                            <h4 style="font-size:0.95em;"><i class="icon fa fa-info"></i><b>Por determinar</b></h4>
                        </div>
                    @endif
                </div>
            </li>
        @endforeach             
    </ul>
        
        <ul id="column2" class="column">
            <div id="tituloColumn2">INDAGACIÓN PREVIA</div>             
        </ul>
        
        <ul id="column3" class="column"> 
            <div id="tituloColumn3">INVESTIGACIÓN DISCIPLINARIA</span> 
        </ul>

        <ul id="column4" class="column">
            <div id="tituloColumn4">INHIBITORIO</div> 
        </ul>
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

function guardarValoracion()
{
    var cantIndagacion  = $("#column2 li").size();//Correo certificado
    var cantInvestigacion  = $("#column3 li").size();//Centro
    var cantInhibitorio = $("#column4 li").size();//Interno

    //indagacion ------------------------------
    var indagacion = [];
    if(cantIndagacion > 0)
    {
        $("#column2 li").each(function(n) {                
            indagacion.push($(this).attr("id"));
        });                
    }
    //-------------------------------------

    //investigacion ------------------------------
    var investigacion = [];
    if(cantInvestigacion > 0)
    {
        $("#column3 li").each(function(n) {                
            investigacion.push($(this).attr("id"));
        });                
    }
    //-------------------------------------

    //inhibitorio ------------------------------
    var inhibitorio = [];
    if(cantInhibitorio > 0)
    {
        $("#column4 li").each(function(n) {                
            inhibitorio.push($(this).attr("id"));
        });                
    }
    //-------------------------------------

    //Convierte arreglos en formato JSON para ser enviados vía AJAX
    var jsonIndagacion = JSON.stringify(indagacion);
    var jsonInvestigacion = JSON.stringify(investigacion);
    var jsonInhibitorio = JSON.stringify(inhibitorio);
  
    //Envía los enrutamientos
    var ruta = "{{URL::to('procesos/guardarValoracion/')}}";

    var parametros = {  
        "jsonIndagacion" : jsonIndagacion,
        "jsonInvestigacion" : jsonInvestigacion,
        "jsonInhibitorio" : jsonInhibitorio
      };
      
    $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            success:  function (responseText) { 
            //Mensaje de notificación ------------------------------------------
            playAudio('alert');    
            alertify.success("Guardado.");
            window.location.reload();
            //------------------------------------------------------------------                                                   
            },
            error: function (responseText) {
              alert("Error valorar/#192")
            }
    });    
}
</script>
@stop