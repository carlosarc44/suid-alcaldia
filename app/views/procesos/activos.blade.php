@extends('plantillas.layout3')
<!--includes de la cabecera-->
@section('cabecera')
    <!-- iCheck for checkboxes and radio inputs -->
    <style type="text/css">
        fieldset {
            border: 1px solid #c4c4c4;
            border-radius: 10px;
            padding: 20px;
            background: #f0f0f0;
        }

        th,
        td {
            white-space: nowrap;
        }

        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }
    </style>
    {{ HTML::script('js/ajax.js') }}
    <link rel="stylesheet" href="{{ asset('css/fixedHeader.dataTables.min.css') }}" />
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')
    <h1>PROCESOS ACTIVOS <small><span id="tituloFecha">Procesos en curso</span></small></h1>
    <!--  MIGA DE PAN -->
    <ol class="breadcrumb">
        <li><a href="{{ asset('/inicio') }}"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Procesos Activos</li>
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
                Procesos para Reparto en Fase de Juzgamiento
            </h3>
        </div>
        <div class="box-body" style="display: block;" id="resultadoQuejas">
            <div id="ajax-procesos">
                <!-- Ajax -->
            </div>
        </div>
    </div>
@stop

<!--scriptsFin-->
@section('scriptsFin')
    <script type="text/javascript" src="{{ asset('js/quejas/comun.js?v=' . rand(1, 1000)) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            cargarProcesosActivosEtapa();
        });
    </script>
@stop
