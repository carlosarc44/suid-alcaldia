@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
<style>
.desaturada{									
	filter: grayscale(100%);
	-webkit-filter: grayscale(100%);
	-moz-filter: grayscale(100%);
	-ms-filter: grayscale(100%);
	-o-filter: grayscale(100%);
	margin-top: 10px;
}
fieldset{
	border: 1px solid #ddd;
	border-radius: 8px;
	padding: 20px;
}
#textoPlantillas {
    text-align: center;
    color: #c4c4c4;
}
.no-border {
	border:none;
}
.select2-container--default .select2-selection--single {
	border: 1px solid #f0f0f0 !important;
}
</style>
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>PLANTILLAS PARA GESTIÓN DE ACTUACIONES</h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li><a href="javascript: void(0)">Procesos</a></li>
	<li class="active">Plantillas Notificaciones</li>
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
	<div class="box">
		<div class="box-body box-profile" style="display: block;">
			<div class="row">	
				<div class="col-md-3 col-md-offset-2">
					<a href="javascript:void()" class="btn btn-default" onclick="oficioGeneral()">
						<h4>Generar un Oficio General</h4>
						<img src="{{asset('img/oficio.png')}}" alt="oficio" style="height: 200px">					
					</a>
				</div>
				<div class="col-md-3">
					<a href="javascript:void()" class="btn btn-default" onclick="contenedorBuscar()">
						<h4>Generar Plantillas de un Proceso</h4>
						<img src="{{asset('img/plantillas.png')}}" alt="oficio" style="height: 200px">					
					</a>
				</div>
				<div class="col-md-3">
					<div class="row" style="padding-top: 60px;display:none" id="contenedorBuscar">	
						<div class="col-md-9" style="background: #f0f0f0;padding: 20px;border-radius: 5px;border: 1px solid #dd">					
							<label className="col-form-label" >Número de Proceso:</label>
							<input type="text" class="form-control" placeholder="Vigencia-Número" id="radicado" placeholder="AAAA-0000" autocomplete="rutjfkde" readonly onfocus="this.removeAttribute('readonly');" autoFocus style="font-weight: bold;font-size: 1.5em;"/>
							<span className="help-block">Vigencia-Número <i>Ej: 2020-0044</i></span>
							<button type="button" class="btn btn-info btn-block" style="margin-top:15px;"  onclick="buscarRadicadoPlantillas()">
								<li class="fa fa-search" style="color:#fff;"></li> Buscar
							</button>						
						</div>
					</div>
				</div>
			</div>
			<br>
			<hr>
			<div class="row">
				<div class="col-sm-12" id="ajax-buscar">
					<!-- ajax -->
				</div>
			</div>
		</div>
	</div>
	<!-- # box-->
@stop

@section("scriptsFin")
<script type="text/javascript" src="{{ asset('js/fileinput.min.js') }}"></script>
@stop