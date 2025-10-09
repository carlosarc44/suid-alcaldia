@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')

    <style type="text/css">
        #textoPlantillas {
            text-align: center;
            color: #c4c4c4;
        }

        .desaturada {
            filter: grayscale(100%);
            -webkit-filter: grayscale(100%);
            -moz-filter: grayscale(100%);
            -ms-filter: grayscale(100%);
            -o-filter: grayscale(100%);
            margin-top: 10px;
        }

        fieldset {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
    <link href="{{ asset('css/timeline-progress.css?v=7') }}" rel="stylesheet">
@stop
<!--includes de la cabecera-->

<!--menu lateral izquierdo-->
@section('menuLateral')
    @include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->
@section('contenido')
    <input type="hidden" id="idRadicado" value="{{ $proceso[0]->idRadicado }}">
    <input type="hidden" id="vigencia" value="{{ $proceso[0]->vigencia }}">

    @if ($fase != '')
        <!-- box -->
        <div class="box">
            <div class="box-body" style="display: block; padding: 0px !important;min-height:440px">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="{{ $fase == 1 ? 'active' : '' }}"><a href="#tab_instruccion" data-toggle="tab"
                                        onclick="traerFase({{ '1, ' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ', 1' }});">Fase
                                        de Instrucción</a></li>
                                <li class="{{ $fase == 2 ? 'active' : '' }}"><a href="#tab_juicio" data-toggle="tab"
                                        onclick="traerFase({{ '2, ' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ', 1' }});">Fase
                                        de Juzgamiento</a></li>
                                <li class="{{ $fase == 3 ? 'active' : '' }}"><a href="#tab_segunda" data-toggle="tab"
                                        onclick="traerFase({{ '3, ' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ', 1' }});">Segunda
                                        Instancia</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_instruccion" style="background: #fff">
                                    <div id="ajax-fase"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- # box -->
    @endif

    {{ '';
    $numerosQuejas = Util::traerQuejasProceso($proceso[0]->vigencia, $proceso[0]->idRadicado);
    
    //Valida la etapa del proceso para decidir si muestra o no el campo del auto
    $et = [1, 2, 3, 5, 8, 9, 10];
    
    if (in_array($idEtapa, $et)) {
        $reqAuto = 1;
    } else {
        $reqAuto = 0;
    } }}

    <!-- row -->
    <div class="row">
        <div class="col-md-4" id="ajax-widgetPrescripcion">
            {{ Util::traerWidgetPrescripcion($proceso[0]->vigencia, $proceso[0]->idRadicado, 1) }}
            <div class="box box-info">
                <div class="box-body box-profile">
                    <a href="{{ asset('/procesos/ver/' . $proceso[0]->vigencia . '/' . $proceso[0]->idRadicado) }}"
                        class="btn btn-app">
                        <i class="fa fa-eye"></i> Ver
                    </a>
                    <a class="btn btn-app"
                        onclick="portada('{{ $proceso[0]->vigencia }}', '{{ $proceso[0]->idRadicado }}');">
                        <i class="fa fa-file-word-o"></i> Portada
                    </a>

                    <a class="btn btn-app"
                        onclick="{{ $autoRemisionCompetencia > 0 ? 'modalRemitirPorCompetencia()' : 'mensajeAutoRemision()' }}">
                        <i class="fa fa-undo"></i> <strong>Remitir este proceso por competencia</strong> <span
                            class="pull-right"
                            style="color:red;font-size:0.82em; font-weight:600; margin-left:6px">Nuevo!</span>
                    </a>
                    <!--
                                                                                            <a class="btn btn-app">
                                                                                             <span class="badge bg-red">15</span><i class="fa fa-calendar-check-o"></i> Tareas
                                                                                            </a>  -->
                    <a class="btn btn-app"
                        onclick="mostrarTerminarEtapa('{{ $proceso[0]->vigencia }}', '{{ $proceso[0]->idRadicado }}');"
                        style="display: none">
                        <i class="fa fa-flag-checkered"></i> Terminar Etapa
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-info">
                        <div class="box-body box-profile">
                            <h3 class="profile-username text-center">
                                {{ $proceso[0]->vigencia . '-' . $proceso[0]->idRadicado }}</h3>
                            <p class="text-muted text-center">
                                @if (count($numerosQuejas) > 0)
                                    Queja:
                                    @foreach ($numerosQuejas as $numeroQueja)
                                        <strong>{{ $numeroQueja . ' ' }}</strong>
                                    @endforeach
                                @endif
                            </p>
                        </div>
                    </div>

                    <div id="ajax-widgetProceso">
                        {{ Util::traerWidgetProceso($proceso[0]->vigencia, $proceso[0]->idRadicado) }}
                        <!-- small box -->
                        <div id="ajax-widgetFaltas">
                            {{ Util::traerWidgetFaltas($proceso[0]->vigencia, $proceso[0]->idRadicado, 1) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    @if ($reqAuto == 1)
                        <div id="resultadoAutoWidget" style="display: none">
                            <!-- Util::traerAutoWidget($proceso[0]->vigencia, $proceso[0]->idRadicado, $idEtapa)  -->
                        </div>
                    @endif

                    <div class="box box-success widget-user-2">
                        <div class="widget-user-header bg-gray-light">
                            <div class="widget-user-image">
                                <img src="{{ asset('img\SUID_transp2.png') }}" class="img-circle">
                            </div>
                            <h3 class="widget-user-username">Autos
                                {{ Util::actionTraerNombreFase($proceso[0]->vigencia, $proceso[0]->idRadicado) }}</h3>
                            <h5 class="widget-user-desc">
                                Numeración asignada por el líder de la fase de
                                @if (Util::actionTraerFase($proceso[0]->vigencia, $proceso[0]->idRadicado) == 1)
                                    instrucción
                                @else
                                    juzgamiento
                                @endif
                            </h5>
                            <hr>
                            <label>Tipos de Auto
                                {{ Util::actionTraerNombreFase($proceso[0]->vigencia, $proceso[0]->idRadicado) }}:</label>
                            {{ Form::select('etapas', ['0' => 'Seleccione tipo de Auto'] + $lista_etapas_fase, 0, [
                                'class' => 'form-control',
                                'id' => 'etapaAuto',
                                'style' => 'width:100%;',
                                'tabindex' => '-1',
                                'aria-hidden' => 'true',
                            ]) }}
                            <br>
                            <label>Asunto del Auto:</label>
                            <textarea id="observacionAuto" rows="4" style="width:100%; text-transform: uppercase;"
                                placeholder='Escriba el asunto del Auto.  Ej: "Por medio del cual..."' onfocus="this.value = 'POR MEDIO DEL CUAL '"></textarea>
                            <hr>
                            <button type="button" style="font-weight:bold" class="btn btn-success btn-block"
                                onclick="solicitarNumeroAuto()"> <i class="fa fa-hashtag" id="btn-solicitar-auto"></i>
                                Solicitar Número de Auto</button>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked" id="ajax-AutosWidget">
                                <!-- ajax -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- # row -->

    <!-- row -->
    <div class="row">
        <!-- # col-sm-12 -->
        <div class="col-sm-12">
            @if (count($numerosQuejas) > 0)
                @foreach ($numerosQuejas as $numeroQueja)
                    <!--  box-->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Queja {{ $numeroQueja }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <!-- queja datos básicos -->
                            <div class="box" style="border-top: 0">
                                <div class="box-body no-padding" id="ajax-verQueja_{{ $numeroQueja }}">
                                    <!-- ajax -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- # box-->
                @endforeach
            @else
                <div class="alert alert-white alert-dismissible" style="margin:20px;">
                    <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                    No se encontraron quejas.
                </div>
            @endif

            <!--  box-->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Plantillas para iniciar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!--  box-body -->
                <div class="box-body">
                    <div class="tab-pane active">
                        {{ '';
                        $plantillasIniciar = DB::table('plantilla')->where('Etapa_idEtapa', '=', $idEtapa)->where('iniciar', '=', 1)->get() }}

                        <ul class="bs-glyphicons">
                            @if (count($plantillasIniciar) > 0)
                                @foreach ($plantillasIniciar as $plantilla)
                                    <a style="cursor:pointer;"
                                        onclick="plantilla('{{ $plantilla->idPlantilla }}', '{{ $plantilla->TipoPlantilla_idTipoPlantilla }}');">
                                        <li>
                                            <img src="{{ asset('img/word.png') }}">
                                            <span class="glyphicon-class">
                                                {{ $plantilla->nombrePlantilla }}
                                            </span>
                                        </li>
                                    </a>
                                @endforeach
                            @else
                                <div class="alert alert-white alert-dismissible" style="margin:20px;">
                                    <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                                    No se encontraron plantillas para iniciar.
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
                <!--  box-body -->
            </div>
            <!-- # box-->

            <!-- nav-tabs-custom-->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Plantillas</a></li>
                    <li><a href="#tab_2" data-toggle="tab"
                            onclick="verNotificaciones('{{ $idEtapa }}');">Notificaciones</a></li>
                    <!--
                                                                                            <li><a href="#tab_3" data-toggle="tab">Edictos</a></li>
                                                                                            <li><a href="#tab_4" data-toggle="tab">Apoderado</a></li>
                                                                                            -->
                    <li><a href="#tab_5" data-toggle="tab"
                            onclick="verExpediente({{ $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado }});">Expediente
                            Digital</a></li>
                    <li><a href="#tab_6" data-toggle="tab" onclick="verTareas('{{ $idEtapa }}');">Tareas</a></li>

                </ul>
                <div class="tab-content">
                    <!-- Plantillas -->
                    <div class="tab-pane my-tabs active" id="tab_1">
                        <br>
                        <div class="box-body table-responsive no-padding">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:100px;">
                                        <div class="btn-group-vertical">
                                            @foreach ($tiposPlantillas as $tipoPlantilla)
                                                <button type="button" class="btn btn-default"
                                                    onclick="cargarPlantillas('{{ $tipoPlantilla->idTipoPlantilla }}', '{{ $idEtapa }}', '{{ $proceso[0]->vigencia }}', '{{ $proceso[0]->idRadicado }}')">
                                                    {{ $tipoPlantilla->nombreTipoPlantilla }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td style="border:1px solid #ddd; padding:8px;">
                                        <!-- resultadoPlantillas -->
                                        <div id="resultadoPlantillas" style="height: 550px; overflow: scroll;">
                                            <div id="textoPlantillas">
                                                <h4>PLANTILLAS
                                                    {{ Util::traerNombreEtapa($proceso[0]->vigencia, $proceso[0]->idRadicado, 1) }}
                                                </h4>
                                                <img src="{{ asset('img/SUID_transp2.png') }}" class="desaturada">
                                                <br>
                                                <span>Seleccione una categoría</span>
                                            </div>
                                        </div>
                                        <!-- # resultadoPlantillas -->
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- # Plantillas -->

                    <!-- Notificaciones -->
                    <div class="tab-pane my-tabs" id="tab_2">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row"
                                    style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 10px 0; padding: 4px;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-default pull-right"
                                            onclick="registrarNotificacion()">
                                            <li class="fa fa-commenting-o" style="color:#0082ff;"></li> Registrar
                                            Notificación
                                        </button>
                                    </div>
                                </div>

                                <div id="resultadoTareas">
                                    <!-- CARGA AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- # Notificaciones -->

                    <!-- Edictos -->
                    <div class="tab-pane my-tabs" id="tab_3">
                        Edictos
                    </div>
                    <!-- # Edictos -->

                    <!-- Apoderado -->
                    <div class="tab-pane my-tabs" id="tab_4">
                        Apoderado
                    </div>
                    <!-- # Apoderado -->

                    <!-- Expediente Digital -->
                    <div class="tab-pane my-tabs" id="tab_5">

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row"
                                    style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 10px 0; padding: 4px;">
                                    <div class="col-xs-2">
                                        <label class="pull-right" style="margin-top:10px;">Expediente sólo de:</label>
                                    </div>
                                    <div class="col-xs-4" style="padding:0px;">

                                        {{ Form::select(
                                            'etapas',
                                            ['0' => 'Todo el expediente..', '-1' => 'Sólo documentos externos..'] + $lista_etapas,
                                            0,
                                            [
                                                'class' => 'form-control',
                                                'id' => 'etapaExpediente',
                                                'style' => 'width:100%;',
                                                'tabindex' => '-1',
                                                'aria-hidden' => 'true',
                                                'onchange' => 'verExpediente(' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ')'
                                            ]
                                        ) }}
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-default pull-right"
                                            onclick="agregarArchivosGenerados()">
                                            <li class="fa fa-cloud-upload" style="color:#0082ff;"></li> Agregar Archivos
                                            Generados
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-default pull-right"
                                            onclick="agregarOtrosArchivos()">
                                            <li class="fa fa-cloud-upload" style="color:#0082ff;"></li> Agregar Otros
                                            Archivos
                                        </button>
                                    </div>
                                </div>

                                <div id="resultadoExpediente">
                                    <!-- CARGA AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #Expediente Digital -->

                    <!-- Tareas -->
                    <div class="tab-pane my-tabs" id="tab_6">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row"
                                    style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 10px 0; padding: 4px;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-default pull-right"
                                            onclick="programarTarea()">
                                            <li class="fa fa-check-square-o" style="color:#0082ff;"></li> Programar una
                                            tarea
                                        </button>
                                    </div>
                                </div>

                                <div id="resultadoTareas">
                                    <!-- CARGA AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- # Tareas -->
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- # nav-tabs-custom-->
    </div>
    <!-- # col-xs-9 -->
    </div>
    <!-- # row -->
    <!-- MODAL -->
    <div class="modal fade in" id="modalSolicitarNumero">
        <div class="modal-dialog sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Solicitar Número de Auto</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <textarea id="observacionNumAuto" name="observacionNumAuto" rows="4" style="width:100%"
                            placeholder="Escriba el asunto del Auto" autofocus></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-sm pull-right" onclick="validarEnviarSolicitud();"><i
                            class="fa fa-external-link"></i> Enviar esta solicitud</button>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade in" id="modalPdfGenerado" tabindex="-1" role="dialog" aria-labelledby="defModalHead"
        aria-hidden="true">
        <div class="modal-dialog" style="width:96%; margin-top: 4px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="defModalHead">EXPEDIENTE DIGITAL PROCESO
                        {{ $proceso[0]->vigencia . '-' . $proceso[0]->idRadicado }}</h4>
                </div>
                <div class="modal-body">
                    <iframe id="framePdf" style="width: 100%;height: 80vh;"></iframe>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Cerrar esta
                                ventana</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="modalAgregarArchivos">
        <div class="modal-dialog sm" style="width: 64%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="verExpediente({{ $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado }});">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Agregar archivos al expediente</h4>
                </div>
                <div class="modal-body">
                    <!-- resultadoAgregarArchivos -->
                    <div id="resultadoAgregarArchivos">
                        <!-- CARGA AJAX-->
                    </div>
                    <!-- # resultadoAgregarArchivos -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"
                        onclick="verExpediente({{ $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado }});">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade in" id="modalProgramarTarea">
        <div class="modal-dialog sm" style="width: 86%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="verTareas('{{ $idEtapa }}');">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Programar una Tarea</h4>
                </div>
                <div class="modal-body">
                    <!-- resultadoProgramarTarea -->
                    <div id="resultadoProgramarTarea">
                        <!-- CARGA AJAX-->
                    </div>
                    <!-- # resultadoProgramarTarea -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"
                        onclick="verTareas('{{ $idEtapa }}');">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade in" id="modalRegistrarNotificacion">
        <div class="modal-dialog sm" style="width: 86%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="verTareas('{{ $idEtapa }}');">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Registrar una Notificación</h4>
                </div>
                <div class="modal-body">
                    <!-- resultadoRegistrarNotificacion -->
                    <div id="resultadoRegistrarNotificacion">
                        <!-- CARGA AJAX-->
                    </div>
                    <!-- # resultadoRegistrarNotificacion -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"
                        onclick="verNotificaciones('{{ $idEtapa }}');">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade in" id="modalCambiarFecha">
        <div class="modal-dialog sm" style="width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="lineaTiempo('{{ $idEtapa }}');">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Cambiar Fecha de la Etapa</h4>
                </div>
                <div id="ajax-cambiarFecha">
                    <!-- CARGA AJAX-->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="modalCambiarFechaHechos">
        <div class="modal-dialog sm" style="width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Cambiar fecha de los hechos</h4>
                </div>
                <div id="ajax-cambiarFechaHechos">
                    <!-- CARGA AJAX-->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="modalCambiarFaltasComunes">
        <div class="modal-dialog sm" style="width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Cambiar faltas comunes</h4>
                </div>
                <div id="ajax-cambiarFaltasComunes">
                    <!-- CARGA AJAX-->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="modalRemitirPorCompetencia">
        <div class="modal-dialog sm" style="width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Remitir Proceso por Competencia</h4>
                </div>
                <div class="modal-body">
                    <div id="ajax-remitirPorCompetencia">
                        <!-- CARGA AJAX-->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-default pull-right"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="modalTerminarEtapa">
        <div class="modal-dialog sm" style="width: 36%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Terminar esta etapa</h4>
                </div>
                <div class="modal-body">
                    <!-- resultadoTerminarEtapa -->
                    <div id="resultadoTerminarEtapa">
                        <!-- CARGA AJAX-->
                    </div>
                    <!-- # resultadoTerminarEtapa -->
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-info pull-right"
                                onclick="terminarEtapa('{{ $proceso[0]->vigencia }}', '{{ $proceso[0]->idRadicado }}')">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@stop


<!--scriptsFin-->
@section('scriptsFin')
    <!-- CK Editor -->
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/fileinput.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('js/procesos/actuaciones.js?v=' . rand(1, 1000)) }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            traerFase({{ ($fase != NULL ? $fase : 0).', '.$proceso[0]->vigencia.', '.$proceso[0]->idRadicado }}, 1)
            //cargarAutosEspeciales()

            var quejas = "<?php echo json_encode($numerosQuejas); ?>";
            quejas = JSON.parse(quejas)

            for (let i = 0; i < quejas.length; i++) {
                verQueja(quejas[i], 1)
            }

            //Tabla archivos
            $('#tablaArchivos').DataTable({
                'iDisplayLength': 50
            });
            //Tabla bitácora
            $('#tablaBitacora').DataTable({
                'iDisplayLength': 50
            });
        });

        function verTareas(idEtapa) {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var parametros = {
                'vigencia': vigencia,
                'idRadicado': idRadicado,
                'idEtapa': idEtapa
            };

            var loader = "<img src='{{ asset('img/loading2.gif') }}'>";
            var ruta = "{{ URL::to('procesos/verTareas') }}";

            $.ajax({
                data: parametros,
                url: ruta,
                type: "post",
                beforeSend: function() {
                    $("#resultadoTareas").html('<p style="width:100%; text-align:center; margin-top:10px;">' +
                        loader + '</p>')
                },
                success: function(responseText) {
                    $('#resultadoTareas').html(responseText);
                    $('#tablaTareas').DataTable();
                },
                error: function(responseText) {
                    alertify.error('error #773');
                }
            });
        }

        function verArchivo(idArchivo) {
            //--------------GENERA PDF ------------------------------------------------
            //Carga el pdf generado
            //Lanza la modal
            $('#modalPdfGenerado').modal('show');
            //Carga el pdf en el iframe
            var rutaRedirect = "<?php echo URL::to('procesos/verArchivo/'); ?>";
            document.getElementById("framePdf").src = rutaRedirect + "/" + idArchivo;
            //--------------------------------------------------------------------------		
        }

        //Funciones
        function mostrarSeleccionarArchivo(idArchivo) {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var loader = '<img src="{{ asset('img/loading.gif') }}">';
            var ruta = "{{ URL::to('procesos/mostrarSeleccionarArchivo') }}";

            var parametros = {
                "idArchivo": idArchivo,
                "vigencia": vigencia,
                "idRadicado": idRadicado
            };

            $.ajax({
                data: parametros,
                url: ruta,
                type: "post",
                beforeSend: function() {
                    $("#resultadoAgregarArchivos").html(
                        '<p style="width:100%; text-align:center; margin-top:10px;">' + loader + '</p>')
                },
                success: function(responseText) {
                    $("#resultadoAgregarArchivos").html(responseText);
                    $("input[type=file]").fileinput({
                        showUpload: true,
                        maxFileCount: 1
                    });
                },
                error: function(responseText) {
                    alert("Error #786" + responseText)
                }
            });
        }

        function borrarArchivo(idArchivo) {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var ruta = "{{ URL::to('procesos/borrarArchivoGenerado') }}";
            var parametros = {
                'idArchivo': idArchivo
            };

            alertify.confirm('<b>Borrar Archivo Generado</b>',
                'Se va a borrar el registro de un archivo generado.  <b>Desea continuar?</b>',
                function() {
                    //SI
                    $.ajax({
                        data: parametros,
                        url: ruta,
                        type: 'post',
                        success: function(responseText) {
                            alertify.success('El registro del archivo generado se borró correctamente.');
                            verArchivosGenerados();
                        },
                        error: function(responseText) {
                            alertify.error("error 884");
                        }
                    });
                },
                function() {
                    //NO	
                    alertify.error("Acción cancelada");
                    alertify.closeAll();
                    return false;
                }
            );
        }

        function validarEnviarSolicitud_old() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";
            var idEtapa = "<?php echo $idEtapa; ?>";
            var observacion = $('#observacionNumAuto').val();

            if (observacion == '') {
                playAudio('fail');
                alertify.error('Ingrese el asunto del auto');
                $('#observacionNumAuto').focus();
                return false;
            }

            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/solicitarNumeroAuto/') }}";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado,
                "idEtapa": idEtapa,
                "observacion": observacion
            };

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#modalSolicitarNumero').modal('hide');
                    $('#resultadoAutoWidget').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    //Formato json para los datos recibidos
                    var arrayJS = JSON.parse(JSON.stringify(responseText));

                    //Realtime ---------------------------------------------------------------------
                    if (arrayJS['ok'] == true) {
                        socket.emit("actualizarSolicitudNumeros", {
                            vistaAutos: arrayJS['vistaAutos'],
                            nombresUsuario: arrayJS['nombresUsuario']
                        });
                    }
                    //-------------------------------------------------------------------------------

                    //Recarga local -------------------------------------------
                    resultadoAutoWidget.innerHTML = arrayJS['vistaAutoWidget'];
                    playAudio('alert');
                    alertify.success("Se envió la solicitud al director");
                    //---------------------------------------------------------

                },
                error: function(responseText) {
                    playAudio('fail');
                    alertify.error("Error /#1037");
                }
            });
        }

        function solicitarNumero(vigencia, idRadicado, idEtapa) {
            $('#modalSolicitarNumero').modal('show');
        }

        function agregarArchivosGenerados() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/agregarArchivos/') }}";
            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado
            };

            $('#modalAgregarArchivos').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoAgregarArchivos').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoAgregarArchivos').html(responseText);
                },
                error: function(responseText) {
                    alertify.error("Error /#857");
                }
            });
        }

        function programarTarea() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado
            };

            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/programarTarea/') }}";

            $('#modalProgramarTarea').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoProgramarTarea').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoProgramarTarea').html(responseText);
                    //fechaTareas
                    $('#fechaTareas').datepick({
                        onSelect: cargarHoras
                    }).datepick('setDate', new Date());
                },
                error: function(responseText) {
                    alertify.error("Error /#1051");
                }
            });
        }

        function registrarNotificacion() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado
            };

            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/registrarNotificacion/') }}";

            $('#modalRegistrarNotificacion').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoRegistrarNotificacion').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoRegistrarNotificacion').html(responseText);
                },
                error: function(responseText) {
                    alertify.error("Error /#1220");
                }
            });
        }

        function cargarHoras() {
            var fechaTarea = $.datepick.formatDate("yyyy-mm-dd", $('#fechaTareas').datepick('getDate')[0]);

            var loader = '<img src="{{ asset('img/loading2.gif') }}">';

            var ruta = "{{ URL::to('procesos/cargarHoras/') }}";

            var parametros = {
                "fechaTarea": fechaTarea
            };

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoCargarHoras').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoCargarHoras').html(responseText);

                },
                error: function(responseText) {
                    alertify.error("Error /#1085");
                }
            });
        }

        function nuevaTarea(fechaTarea, horaTarea) {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var loader = '<img src="{{ asset('img/loading2.gif') }}">';
            var ruta = "{{ URL::to('procesos/nuevaTarea/') }}";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado,
                "fechaTarea": fechaTarea,
                "horaTarea": horaTarea
            };

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoCargarHoras').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoCargarHoras').html(responseText);
                    //Timepicker
                    $(".timepicker").timepicker({
                        showInputs: false
                    });

                },
                error: function(responseText) {
                    alertify.error("Error /#1121");
                }
            });
        }

        function guardarTarea(fechaTarea, horaTarea) {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";
            var horaTareaFin = $("#horaTareaFin").val();
            var asuntoTarea = $("#asuntoTarea").val();
            var descripcionTarea = $("#descripcionTarea").val();
            var lugarTarea = $("#lugarTarea").val();

            if (horaTareaFin == "") {
                playAudio('fail');
                alertify.error("Seleccione la hora de finalización de la tarea");
                $("#horaTareaFin").focus();
                return false;
            } else if (asuntoTarea == "") {
                playAudio('fail');
                alertify.error("Ingrese el asunto");
                $("#asuntoTarea").focus();
                return false;
            } else if (descripcionTarea == "") {
                playAudio('fail');
                alertify.error("Ingrese la descripción");
                $("#descripcionTarea").focus();
                return false;
            } else if (lugarTarea == "") {
                playAudio('fail');
                alertify.error("Ingrese el lugar");
                $("#lugarTarea").focus();
                return false;
            }

            var loader = '<img src="{{ asset('img/loading2.gif') }}">';
            var ruta = "{{ URL::to('procesos/guardarTarea/') }}";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado,
                "fechaTarea": fechaTarea,
                "horaTarea": horaTarea,
                "horaTareaFin": horaTareaFin,
                "asuntoTarea": asuntoTarea,
                "descripcionTarea": descripcionTarea,
                "lugarTarea": lugarTarea
            };

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                success: function(responseText) {
                    //Recarga las horas
                    cargarHoras();
                    playAudio('alert');
                    alertify.info("La tarea se programó correctamente");
                },
                error: function(responseText) {
                    alertify.error("Error /#1196");
                }
            });
        }

        function agregarOtrosArchivos() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";
            var idEtapa = "<?php echo $idEtapa; ?>";

            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/agregarOtrosArchivos/') }}";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado,
                "idEtapa": idEtapa
            };

            $('#modalAgregarArchivos').modal('show');

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoAgregarArchivos').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoAgregarArchivos').html(responseText);
                    $("input[type=file]").fileinput({
                        showUpload: true,
                        maxFileCount: 1
                    });
                },
                error: function(responseText) {
                    alertify.error("Error /#992");
                }
            });
        }

        function verArchivosGenerados() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";

            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/agregarArchivos/') }}";
            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado
            };

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoAgregarArchivos').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoAgregarArchivos').html(responseText);
                },
                error: function(responseText) {
                    alertify.error("Error /#857");
                }
            });
        }

        function validarSubirArchivo(idArchivoGenerado) {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";
            var idEtapa = "<?php echo $idEtapa; ?>";

            var fileArchivoImportar = document.getElementById("archivoImportar").value;

            //Valida el número del archivo que se va a subir para que coincida con el generado  
            var nombreArchivo = $('input[type=file]')[0].files[0].name;
            var str = $('input[type=file]')[0].files[0].name;
            var ret = str.split("_");
            var num = ret[0];

            if (num != idArchivoGenerado) {
                playAudio('fail');
                alertify.error("Debe agregar un archivo cuyo nombre comienza con: " + idArchivoGenerado + "_");
                return false;
            }
            //-------------------------------------------------------------------------------

            if (fileArchivoImportar == "") {
                alertify.error("Seleccione el archivo");
                playAudio('fail');
                return false;
            }

            //información del formulario
            var formData = new FormData($("#formulario")[0]);
            formData.append("idArchivoGenerado", idArchivoGenerado);
            formData.append("vigencia", vigencia);
            formData.append("idRadicado", idRadicado);
            formData.append("idEtapa", idEtapa);

            var ruta = "{{ URL::to('procesos/subirArchivoExpediente/') }}";
            var loader = '<img src="{{ asset('img/loaders/default.gif') }}">';

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
                beforeSend: function() {
                    $('#resultadoAgregarArchivos').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                //una vez finalizado correctamente
                success: function(responseText) {
                    $("#resultadoAgregarArchivos").html(responseText);
                    playAudio('alert');
                    alertify.info("El archivo se agregó correctamente");
                },
                //si ha ocurrido un error
                error: function() {
                    playAudio('fail');
                    alertify.error("Error #983");
                }
            });
        }

        function validarSubirOtroArchivo() {
            var vigencia = "<?php echo $proceso[0]->vigencia; ?>";
            var idRadicado = "<?php echo $proceso[0]->idRadicado; ?>";
            var idTipoArchivo = $("#tipoArchivoOtro").val();
            var idEtapa = $("#etapaOtro").val();

            var fileArchivoImportar = document.getElementById("archivoImportarOtro").value;

            if (idEtapa == "default") {
                alertify.error("Seleccione la etapa donde se va a agregar el archivo");
                playAudio('fail');
                return false;
            } else if (idTipoArchivo == "default") {
                alertify.error("Seleccione el tipo de archivo");
                playAudio('fail');
                return false;
            } else if (fileArchivoImportar == "") {
                alertify.error("Seleccione el archivo");
                playAudio('fail');
                return false;
            }

            //información del formulario
            var formData = new FormData($("#formularioOtro")[0]);
            formData.append("vigencia", vigencia);
            formData.append("idRadicado", idRadicado);
            formData.append("idEtapa", idEtapa);
            formData.append("idTipoArchivo", idTipoArchivo);

            var ruta = "{{ URL::to('procesos/subirOtroArchivoExpediente/') }}";
            var loader = '<img src="{{ asset('img/loaders/default.gif') }}">';

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
                beforeSend: function() {
                    $('#modalAgregarArchivos').modal('hide');
                },
                //una vez finalizado correctamente
                success: function(responseText) {
                    if (responseText == 0) {
                        playAudio('alert');
                        alertify.success("El archivo se agregó correctamente");
                        verExpediente(vigencia, idRadicado);
                    } else {
                        playAudio('fail');
                        alertify.error("Hubo un error al subir el archivo.  Inténtelo nuevamente.");
                        verExpediente(vigencia, idRadicado);
                    }
                },
                //si ha ocurrido un error
                error: function() {
                    playAudio('fail');
                    alertify.error("Error #1149");
                }
            });
        }

        //CargarCiudad
        function cargarCiudad(idDepartamento) {
            if (idDepartamento != 'default') {
                var loader = '<img src="{{ asset('img/loading.gif') }}">';
                var ruta = "{{ URL::to('procesos/cargarCiudad/') }}";
                var parametros = {
                    "idDepartamento": idDepartamento
                };

                $.ajax({
                    data: parametros,
                    url: ruta,
                    type: 'post',
                    success: function(responseText) {
                        $('#resultadoCargarCiudad').html(responseText);
                        //Initialize Select2 Elements
                        $(".select2").select2();
                    },
                    error: function(responseText) {
                        playAudio('fail');
                        alertify.error("Error /#870");
                    }
                });
            }
        }

        function mostrarTerminarEtapa(vigencia, idRadicado) {
            var idEtapa = "<?php echo $idEtapa; ?>";
            var loader = '<img src="{{ asset('img/loading.gif') }}">';

            var ruta = "{{ URL::to('procesos/mostrarTerminarEtapa/') }}";

            var parametros = {
                "vigencia": vigencia,
                "idRadicado": idRadicado,
                "idEtapa": idEtapa
            };

            $('#modalTerminarEtapa').modal('show');

            $.ajax({
                data: parametros,
                url: ruta,
                type: 'post',
                beforeSend: function(responseText) {
                    $('#resultadoTerminarEtapa').html(
                        '<p style="margin-top:10px; width:100%; text-align:center;">' + loader + '</p>');
                },
                success: function(responseText) {
                    $('#resultadoTerminarEtapa').html(responseText);
                },
                error: function(responseText) {
                    alertify.error("Error /#1688");
                }
            });
        }
    </script>
@stop
