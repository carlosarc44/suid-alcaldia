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
.select2-container {
    width: 100%;
    font-size: 0.9em;
    font-weight: 400;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #c5fe01;
    border-color: #859748;
    color: #444;
    padding: 5px;
}
.select2-dropdown .select2-search__field:focus, .select2-search--inline .select2-search__field:focus {
    border: none
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #c5fe01;
    color: #444;
}
</style>
{{ HTML::script('js/ajax.js') }}
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   
<h1>ESTADOS DE LAS QUEJAS<small><span id="tituloFecha">Recibidas</span></small></h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="{{asset('/inicio')}}"><i class="fa fa-home"></i> Inicio</a></li>
	<li class="active">Informes Quejas</li>
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
    <img src="{{asset('img/banner-estado-quejas.png')}}" style="width: 70%; height:250px;" class="pull-right">
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">
            <br>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Quejas desde:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="fechaInicio" value="{{date('Y').'-01-01'}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Quejas hasta:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="fechaFin" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">   
                    <div class="form-group">
                        <label>Estado:</label>           
                        {{ Form::select('estado', array('0' => 'Todos los estados') + $lista_estados, 
                        0, array('class' => 'form-control select2 select2-hidden-accessible', 'multiple' => 'multiple', 'autocomplete' => 'new-password', 'id'=>'estado', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
                    </div>
                </div>
                <div class="col-sm-4">   
                    <br>
                    <button class="btn btn-info" onclick="consultarEstadosQueja()"><i class="fa fa-search"></i> Consultar</button>
                </div>
            </div>
		</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
      	<div id="ajax-estadosQueja">                   		
       		<!-- ajax -->                   		
		</div>
	</div>
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin') 
{{ HTML::script('js/Chart.bundle.js') }}
{{ HTML::script('js/Chart.bundle.min.js') }}
{{ HTML::script('js/Chart.js') }}
{{ HTML::script('js/Chart.min.js') }}
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js" type="text/javascript"></script>
<script src="{{ asset('js/quejas/estadosQuejas.js?v='.rand(0,1000)) }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	//cargarQuejas(vigencia);

	$(".select2").select2();

    $('#fechaInicio').datepicker({
        autoclose: true,
        dateFormat: 'yyyy-mm-dd'
    });

    $('#fechaFin').datepicker({
        autoclose: true,
        dateFormat: "yyyy-mm-dd"
    });

});
</script>
@stop