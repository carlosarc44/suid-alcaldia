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
<h1>RADICAR QUEJA O INFORME<small> Registro de quejas / informes</small></h1>
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
				<div class="form-group">
					<label>Origen</label>
					<select class="form-control select2 select2-hidden-accessible" id="origenQueja">
						<option value="default">Seleccione ...</option>
						<option value="1">Queja</option>
						<option value="2">Informe</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Tipo de recepción</label>
					{{ Form::select('tipoRecepcion', array('default' => 'Seleccione..') + $lista_tiposRecepcion, 
					Input::old('tipoRecepcion'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'tipoRecepcion', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Fecha de la Queja</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control pull-right" id="fechaQueja">
					</div>
				</div>
			</div>

			<div class="col-md-6">	
				<div class="form-group">
					<label>Fecha de recepción</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control pull-right" id="fechaRecepcion">
					</div>
				</div>
			</div>			
		</div>

		<div class="row">
			<div class="col-xs-6">	
				<div class="form-group">
					<label>Oficio</label>
					<input type="text" class="form-control pull-right" id="numeroOficio" placeholder="Número de Oficio" style="width:100%">
				</div>
			</div> 
			<div class="col-xs-6">
				<div class="form-group">
					<label>Dependencia del presunto responsable</label>
					{{ Form::select('dependenciaQueja', array('default' => 'Seleccione..') + $lista_dependencias, 
					null, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'dependenciaQueja', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">	
				<div class="form-group">
					<label>Presunto Lugar</label>
					<input type="text" class="form-control pull-right" id="presuntoLugar" autocomplete="off">
				</div>
				<!-- /.form-group -->
			</div>
		</div>
		<br>
		<!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label>Presuntos hechos</label>
					<textarea id="presuntosHechos" class="form-control" rows="4" placeholder="Hacer una breve descripción de los presuntos hechos..."></textarea>
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
		<button type="button" class="btn btn-success btn-sm pull-right" onclick="validarGuardarQueja();"><i class="fa fa-save"></i> Guardar Queja</button>
		<br>
	</div>
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('js/quejas/radicarQueja.js?v=3')}}"></script>

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
</script>
@stop
<!--# scriptsFin -->