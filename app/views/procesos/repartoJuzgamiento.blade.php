@extends('plantillas.layout3')
<!--includes de la cabecera-->
@section('cabecera')
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('css/checkBo.css') }}">
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
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')
    <h1>BUZÓN DE REPARTO <small><span id="tituloFecha">Procesos en Fase de Juzgamiento</span></small></h1>
    <!--  MIGA DE PAN -->
    <ol class="breadcrumb">
        <li><a href="{{ asset('/inicio') }}"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Buzón Reparto</li>
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
<style>
    h5 {
    font-size: 1.28571429em;
    font-weight: 700;
    line-height: 1.2857em;
    margin: 0;
}

.card {
    font-size: 1em;
    overflow: hidden;
    padding: 0;
    border: none;
    border-radius: .28571429rem;
    box-shadow: 0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
}

.card-block {
    font-size: 1em;
    position: relative;
    margin: 0;
    padding: 1em;
    border: none;
    border-top: 1px solid rgba(34, 36, 38, .1);
    box-shadow: none;
    background: #fff;
}

.card-img-top {
    display: block;
    width: 100%;
    height: 80px;
}

.card-title {
    font-size: 1.28571429em;
    font-weight: 700;
    line-height: 1.2857em;
}

.card-text {
    clear: both;
    margin-top: .5em;
    color: rgba(0, 0, 0, .68);
}

.card-footer {
    font-size: 1em;
    position: static;
    top: 0;
    left: 0;
    max-width: 100%;
    padding: .75em 1em;
    color: rgba(0, 0, 0, .4);
    border-top: 1px solid rgba(0, 0, 0, .05) !important;
    background: #fff;
}

.card-inverse .btn {
    border: 1px solid rgba(0, 0, 0, .05);
}

.profile {
    position: absolute;
    top: -30px;
    display: inline-block;
    overflow: hidden;
    box-sizing: border-box;
    width: 50px;
    height: 50px;
    margin: 0;
    border: 1px solid #fff;
    border-radius: 50%;
}

.profile-avatar {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 50%;
}

.profile-inline {
    position: relative;
    top: 0;
    display: inline-block;
}

.profile-inline ~ .card-title {
    display: inline-block;
    margin-left: 4px;
    vertical-align: top;
}

.text-bold {
    font-weight: 700;
}

.meta {
    font-size: 1em;
    color: rgba(0, 0, 0, .4);
}

.meta a {
    text-decoration: none;
    color: #a2a3a5;
    font-size: 1.5em;
}

.meta a:hover {
    color: rgba(0, 0, 0, .87);
}
</style>


    @if (count($abogados) > 0)
        <br>
        <div class="container">
            <div class="row">
                @foreach ($abogados as $abogado) 
                    <div class="col-sm-6 col-md-4 col-lg-3 mt-4">
                        <div class="card card-inverse card-info">
                            <img class="card-img-top" src="{{ asset('img/bg2.png')}}">
                            <div class="card-block">
                                <figure class="profile">
                                    {{''; $nombre_fichero = '../public/img/fotos/'.$abogado->documentoPersona.'.jpg' }}
                                    @if(file_exists($nombre_fichero))
                                        <img src="{{ asset('img/fotos/'.$abogado->documentoPersona.'.jpg')}}" title="{{ $abogado->nombre; }}" class="profile-avatar">
                                    @else			                           
                                        @if(Util::traerGenero($abogado->documentoPersona) == 'Femenino')
                                            <img src="{{ asset('img/ella.png')}}" title="{{ $abogado->nombre; }}" class="profile-avatar">
                                        @else
                                            <img src="{{ asset('img/el.png')}}" title="{{ $abogado->nombre; }}" class="profile-avatar">
                                        @endif
                                    @endif
                                </figure>
                                <h4 class="card-title mt-3">{{$abogado->nombre}}</h4>
                                <div class="meta card-text">
                                    {{""; $total = Util::traerCantidadProcesosActivosFuncionario($abogado->documentoPersona);}}
                                    <a>{{$total}} {{$total == 1 ? 'proceso ' : 'procesos '}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach  
            </div>
        </div>
    @else
        No se encontraron abogados en la fase de Juzgamiento
    @endif
    <br>

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">
                Procesos para Reparto en Fase de Juzgamiento
            </h3>
        </div>
        <div class="box-body" style="display: block;" id="resultadoQuejas">
            <div id="ajax-buzonJuzgamiento">
                <!-- Ajax -->
            </div>
        </div>
    </div>
@stop

<!--scriptsFin-->
@section('scriptsFin')
    <script type="text/javascript" src="{{ asset('js/procesos/repartoJuzgamiento.js?v=' . rand(1, 1000)) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            cargarRepartoJuzgamiento();
        });
    </script>
@stop
