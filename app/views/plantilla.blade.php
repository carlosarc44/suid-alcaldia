@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>RADICAR QUEJA<small> Registro de quejas e informes</small></h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li><a href="javascript: void(0)">Quejas</a></li>
	<li class="active">Radicar Queja</li>
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
			<h3 class="box-title">Título</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body" style="display: block;" id="resultadoQuejas">

		</div>
	</div>
	<!-- # box-->
@stop