@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('css/fixedHeader.dataTables.min.css')}}"/>
<style>
.strikethrough:checked + small{
   background: #ddd !important;
  color: #444;
}
.strikethrough:checked + small + span{
  text-decoration:line-through;
  color: #999;
}
input[type=checkbox] {
  transform: scale(1.3);
}

.icon-stat {
    display: block;
    overflow: hidden;
    position: relative;
    padding: 15px;
    margin-bottom: 1em;
    background-color: #fff;
    border-radius: 4px;
    border: 1px solid #ddd;
}
.icon-stat-value {
    display: block;
    font-size: 28px;
    font-weight: 600;
}
.icon-stat-label {
    display: block;
    color: #999;
    font-size: 14px;
}
.icon-stat-footer {
    padding: 10px 0 0;
    margin-top: 10px;
    color: #aaa;
    font-size: 12px;
    border-top: 1px solid #eee;
}
.icon-stat-visual {
    position: relative;
    top: 22px;
    display: inline-block;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    text-align: center;
    font-size: 16px;
    line-height: 30px;
}
</style>
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>PROCESOS <small><span id="tituloFecha">en curso</span></small></h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="{{asset('/inicio')}}"><i class="fa fa-home"></i> Inicio</a></li>
	<li class="active">Procesos activos</li>
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
<div class="row" style="background: #275ec6">
  <img src="{{asset('img/banner-mis-procesos-activos.png')}}" style="width: 70%; height:250px;" class="pull-right">
</div>
<br>
<div class="row-fluid">
  @if (count($arrEtapas) > 0)
    @foreach ($arrEtapas as $etapa)
      <div class="col-md-2 col-sm-3">
        <a href="javascript:void(0)" style="color:#222d32" onclick="cargarProcesosActivosEtapa({{$etapa['idEtapa']}})">
          <div class="icon-stat">
            <div class="row">
              <div class="col-xs-8 text-left">
                <span class="icon-stat-label">{{$etapa["etapa"]}}</span> <!-- /.icon-stat-label -->
                <span class="icon-stat-value">{{$etapa["total"]}}</span> <!-- /.icon-stat-value -->
              </div>
            </div>
            <div class="icon-stat-footer">
              <i class="fa fa-clock-o"></i> {{$etapa["tipoEtapa"]}}
            </div>
          </div>
        </a> 
      </div>
    @endforeach
  @endif
</div>

<br>

<div class="row">
  <!-- # col-sm-12 -->
  <div class="col-md-12">	
    <!-- nav-tabs-custom-->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab"> <strong>MIS PROCESOS ACTIVOS</strong></a></li>
        <li style="display: none"><a href="#tab_2" data-toggle="tab">Resúmen</a></li>
      </ul>
      <div class="tab-content">
        <!-- Tab1 -->
        <div class="tab-pane active" id="tab_1">
          <div id="ajax-procesos">
            <!-- ajax -->
            <div class="row">
              <div class="col-sm-12">
                <div class="widget-user-header bg-gray-light" style="text-align: center;padding: 50px 10px">
                  <div class="widget-user-image">
                    <br>
                    <img src="{{ asset('img/logo-2024-n.png')}}" height="100">
                  </div>
                  <br>
                  <!-- /.widget-user-image -->
                  <h3 class="widget-user-username">Para visualizar sus procesos activos seleccione una etapa <i class="fa fa-arrow-up"></i> </h3>
                  <h5 class="widget-user-desc">Encontramos procesos activos a su cargo en las etapas que se indican y totalizan en la sección superior</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- #Tab1 -->

        <!-- Tab2 -->
        <div class="tab-pane" id="tab_2">
          <div class="row">
            <!--  col-sm-6-->
            <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="box box-info">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-xs-4">
                      <h3 class="box-title">Tareas para hoy</h3>
                    </div>
                    <div class="col-xs-7" style="padding: 0;" id="resultadoPorcentaje">
                            @if (count($tareas) > 0)
                                <div class="progress-group" style="margin: 0;">
                                    <span class="progress-text">Cumplimiento</span>
                                    <span class="progress-number">{{round($porcentaje, 1)}}%</span>
                                    <div class="progress sm" style="margin: 0;">
                             @if($porcentaje < 70)
                              <div class="progress-bar progress-bar-red" style="width: {{round($porcentaje, 1)}}%"></div>
                            @elseif($porcentaje > 69 && $porcentaje < 100)
                              <div class="progress-bar progress-bar-yellow" style="width: {{round($porcentaje, 1)}}%"></div>
                            @elseif($porcentaje >= 100)
                              <div class="progress-bar progress-bar-green" style="width: {{round($porcentaje, 1)}}%"></div>
                            @endif
                                    </div>
                                </div>
                            @endif
                    </div>
                  </div>
          
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                      <i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body direct-chat-messages">
                  @if (count($tareas) > 0)
                    <ul class="todo-list">
                      @foreach ($tareas as $tarea)
                          <li style="position: relative;">
                            @if ($tarea->finalizadaTarea == 1)
                              <input type="checkbox" value="{{$tarea->Id}}" id="chk-{{$tarea->Id}}" class="strikethrough" onclick="finalizarTarea(this.value)" checked>
                            @else
                              <input type="checkbox" value="{{$tarea->Id}}" id="chk-{{$tarea->Id}}" class="strikethrough" onclick="finalizarTarea(this.value)">
                            @endif
          
                            <small class="label label-warning" style="font-size: 0.9em;">
                              <i class="fa fa-clock-o"></i>
                              {{date("g:i a", strtotime(substr($tarea->fechaInicioTarea, -8, 8)))}}
                            </small>
                            <span class="text" style="margin-left: 40px; display: block;">{{$tarea->asuntoTarea}}</span>
                            <a href="{{asset('/procesos/ver/'.$tarea->Radicado_vigencia."/".$tarea->Radicado_idRadicado)}}">
                              <small class="label label-info" style="font-size: 0.9em; position: absolute; top: 10px; right: 4px;">
                                {{$tarea->Radicado_vigencia."-".$tarea->Radicado_idRadicado}}
                              </small>
                          </a>
                          </li>
                      @endforeach
                      </ul>
                  @else
                    <div class="alert alert-white alert-dismissible">
                        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                        No se encontraron tareas programadas para hoy.
                    </div>
                  @endif
                </div>
              </div>
            </div>
            <!--  col-sm-6-->
          
            <!--  col-sm-6-->
            <div class="col-xs-12 col-sm-6 col-md-6">
              <!-- DIRECT CHAT -->
              <div class="box box-gray direct-chat direct-chat-warning">
                  <div class="box-header with-border">
                      <h3 class="box-title">Solicitud números de auto</h3>
                      <div class="box-tools pull-right">
                          <span data-toggle="tooltip" title="3 números de Auto asignados" class="badge bg-green">{{$cantAutos}}</span>
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                      </div>
                  </div>
                  <!-- /.box-header -->
      
                  <div class="box-body">
                      <!-- Conversations are loaded here -->
                      <div class="direct-chat-messages">
                          @if (count($autos))
                            @foreach ($autos as $auto)
                              <div class="direct-chat-msg">
                                  <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">{{$auto->nombre}}</span>
                                    <span class="direct-chat-timestamp pull-right">{{date("d/m/Y g:i a", strtotime($auto->fechaSolicitudAuto))}}</span>
                                  </div>
                                  {{''; $nombre_fichero = '../public/img/fotos/'.$auto->Persona_documentoPersona.'.jpg' }}
                                  @if(file_exists($nombre_fichero))
                                      <img class="direct-chat-img" src="{{ asset('img/fotos/'.$auto->Persona_documentoPersona.'.jpg')}}">
                                  @else
                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg">
                                  @endif
      
                                  <div class="direct-chat-text">
                                    {{"Solicito número de auto de <b>".$auto->nombreEtapa."</b>.  Proceso: "}}
                                    <a href="{{asset('/procesos/ver/'.$auto->Radicado_vigencia."/".$auto->Radicado_idRadicado)}}">
                                  <small class="label label-info" style="font-size: 0.9em;">
                                    {{$auto->Radicado_vigencia."-".$auto->Radicado_idRadicado}}
                                  </small>
                              </a>
                              {{$auto->observaciones}}
                                  </div>
                              </div>
          
                        @if ($auto->asignado == 1)
                          <div class="direct-chat-msg right">
                              <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-right">{{Util::traerNombreDirector()}}</span>
                                <span class="direct-chat-timestamp pull-left">{{date("d/m/Y g:i a", strtotime($auto->fechaAsignacionAuto))}}</span>
                              </div>
                              {{''; $nombre_fichero = '../public/img/fotos/'.Util::traerDocumentoDirector().'.jpg' }}
                              @if(file_exists($nombre_fichero))
                                  <img class="direct-chat-img" src="{{ asset('img/fotos/'.Util::traerDocumentoDirector().'.jpg')}}">
                              @else
                                <img class="direct-chat-img" src="dist/img/user1-128x128.jpg">
                              @endif
                              <div class="direct-chat-text">
                                {{ "Se asignó el auto número <b>".$auto->numAutoAsignado."</b> del ".date("d/m/Y", strtotime($auto->fechaAsignacionAuto))}}
                              </div>
                          </div>
                      @endif


                    @endforeach
                    @else
                  <div class="alert alert-white alert-dismissible">
                      <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                      No se encontraron solicitudes de números de auto.
                  </div>
                @endif
                  </div>

                </div>
                <!-- /.box-body -->

              </div>
              <!--/.direct-chat -->
            </div>
            <!--  col-sm-6-->
          </div>
          
          <div class="row">
            <!--  col-sm-6-->
            <div class="col-xs-12 col-sm-4 col-md-4">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Procesos Activos</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                      <i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body" style="display: block;" id="resultadoQuejas">
                  <div class="row">
                      <div class="col-md-12">
                        <div class="chart-responsive">
                          <canvas id="pieChart" height="150"></canvas>
                        </div>
                      </div>
                      <!-- col-md-12 -->
                      <div class="col-md-12">
                        <ul class="chart-legend clearfix">
                            <li><i class="fa fa-circle-o" style="color:#de000f;"></i> Indagación Previa
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(1);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#7d0096;"></i> Investigación Disciplinaria
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(2);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#00a36a;"></i> Prórroga Investigación
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(3);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#005cde;"></i> Evaluación
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(4);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#00b7de;"></i> Pliego de Cargos
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(5);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#f000ed;"></i> Prueba de Descargos
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(6);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#edc500;"></i> Alegatos de Conclusión
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(7);}}</b></span>
                            </li>
                            <li><i class="fa fa-circle-o" style="color:#ed7b00;"></i> Fallo
                              <span class="pull-right"><b>{{Util::traerCantidadEtapa(8);}}</b></span>
                            </li>
                        </ul>
                      </div>
                      <!-- #col-md-12 -->
                  </div>
                </div>
              </div>
            </div>
            <!--  col-sm-6-->
          
            <!--  col-sm-3-->
            <div class="col-xs-12 col-sm-8 col-md-8">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Asignados reparto {{date("Y")}}  Vs. Asignados reparto {{date ("Y", strtotime('-1 year', strtotime(date("Y"))))}}</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                      <i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body" style="display: block;" id="resultadoQuejas">
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                </div>
              </div>
            </div>
            <!--#  col-sm-3-->
          </div>
        </div>
        <!-- #Tab2 -->
      </div>
    </div>
  </div>
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin')
<!--<script src="asset('js/jquery-ui.js')}}"></script>-->
<!--<script src="asset('js/dataTables.fixedHeader.min.js')}}"></script>-->

<script type="text/javascript" src="{{ asset('js/quejas/comun.js?v=' . rand(1, 1000)) }}"></script>
<script type="text/javascript">
$(document).ready(function() {
  //cargarProcesosActivosEtapa(1);
 
  //Activa los tootltip
  $('[data-toggle="tooltip"]').tooltip()

  /* The todo list plugin */
	$(".todo-list").todolist({
	    onCheck: function (ele) {
	      alert("The element has been checked");
	      return ele;
	    },
	    onUncheck: function (ele) {
	      alert("The element has been unchecked");
	      return ele;
	    }
	});
});

//-------------
  //- PIE CHART -
//-------------
// Get context with jQuery - using jQuery's .get() method.
var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
var pieChart = new Chart(pieChartCanvas);
var PieData = [
  {
    value: "<?php echo Util::traerCantidadEtapa(1); ?>",
    color: "#de000f",
    highlight: "#de0000",
    label: "Indagación Previa"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(2); ?>",
    color: "#7d0096",
    highlight: "#7d0090",
    label: "Investigación Disciplinaria"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(3); ?>",
    color: "#00a36a",
    highlight: "#00a360",
    label: "Prórroga Investigación"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(4); ?>",
    color: "#005cde",
    highlight: "#f0f0f0",
    label: "Evaluación"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(5); ?>",
    color: "#00b7de",
    highlight: "#f0f0f0",
    label: "Pliego de Cargos"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(6); ?>",
    color: "#f000ed",
    highlight: "#f0f0f0",
    label: "Prueba de Descargos"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(7); ?>",
    color: "#edc500",
    highlight: "#f0f0f0",
    label: "Alegatos de Conclusión"
  },
  {
    value: "<?php echo Util::traerCantidadEtapa(8); ?>",
    color: "#ed7b00",
    highlight: "#f0f0f0",
    label: "Fallo"
  }
];
var pieOptions = {
  //Boolean - Whether we should show a stroke on each segment
  segmentShowStroke: true,
  //String - The colour of each segment stroke
  segmentStrokeColor: "#ddd",
  //Number - The width of each segment stroke
  segmentStrokeWidth: 1,
  //Number - The percentage of the chart that we cut out of the middle
  percentageInnerCutout: 50, // This is 0 for Pie charts
  //Number - Amount of animation steps
  animationSteps: 150,
  //String - Animation easing effect
  animationEasing: "easeOutBounce",
  //Boolean - Whether we animate the rotation of the Doughnut
  animateRotate: true,
  //Boolean - Whether we animate scaling the Doughnut from the centre
  animateScale: false,
  //Boolean - whether to make the chart responsive to window resizing
  responsive: true,
  // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
  maintainAspectRatio: false,
  //String - A legend template
  legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
  //String - A tooltip template
  tooltipTemplate: "<%=label%>: <%=value %>"
};
//Create pie or douhnut chart
// You can switch between pie and douhnut using the method below.
pieChart.Doughnut(PieData, pieOptions);
//-----------------
//- END PIE CHART -
//-----------------


var fecha = new Date();
var vig = fecha.getFullYear();
var vigAnt = fecha.getFullYear()-1;

var months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

Morris.Line({
element: 'revenue-chart',
data: [{
  m: '2015-01', // <-- valid timestamp strings
  a: "<?php echo Util::traerCantidadRecibidos(1, '01'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '01'); ?>"
}, {
  m: '2015-02',
  a: "<?php echo Util::traerCantidadRecibidos(1, '02'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '02'); ?>"
}, {
  m: '2015-03',
  a: "<?php echo Util::traerCantidadRecibidos(1, '03'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '03'); ?>"
}, {
  m: '2015-04',
  a: "<?php echo Util::traerCantidadRecibidos(1, '04'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '04'); ?>"
}, {
  m: '2015-05',
  a: "<?php echo Util::traerCantidadRecibidos(1, '05'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '05'); ?>"
}, {
  m: '2015-06',
  a: "<?php echo Util::traerCantidadRecibidos(1, '06'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '06'); ?>"
}, {
  m: '2015-07',
  a: "<?php echo Util::traerCantidadRecibidos(1, '07'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '07'); ?>"
}, {
  m: '2015-08',
  a: "<?php echo Util::traerCantidadRecibidos(1, '08'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '08'); ?>"
}, {
  m: '2015-09',
  a: "<?php echo Util::traerCantidadRecibidos(1, '09'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '09'); ?>"
}, {
  m: '2015-10',
  a: "<?php echo Util::traerCantidadRecibidos(1, '10'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '10'); ?>"
}, {
  m: '2015-11',
  a: "<?php echo Util::traerCantidadRecibidos(1, '11'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '11'); ?>"
}, {
  m: '2015-12',
  a: "<?php echo Util::traerCantidadRecibidos(1, '12'); ?>",
  b: "<?php echo Util::traerCantidadRecibidos(0, '12'); ?>"
}, ],
xkey: 'm',
ykeys: ['a', 'b'],
labels: [vig, vigAnt],
xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
  var month = months[x.getMonth()];
  return month;
},
dateFormat: function(x) {
  var month = months[new Date(x).getMonth()];
  return month;
},
});
</script>
@stop
