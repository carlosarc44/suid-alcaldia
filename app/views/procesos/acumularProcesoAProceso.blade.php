@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
@stop
<!--includes de la cabecera-->

@section('migaPan')   
<h1>ACUMULAR PROCESO A PROCESO</h1>
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-dashboard"></i> Inicio</a></li>
	<li><a href="javascript: void(0)">Proceso</a></li>
	<li class="active">Acumular Proceso</li>
</ol>
@stop

<!--menu lateral izquierdo-->
@section('menuLateral') 
  @include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
	<!--  box-->
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<div class="box-body box-profile" style="display: block;">
			<div class="row">	
				<div class="col-md-10 col-md-offset-1">
					<h3 class="profile-username text-center">Acumular Proceso a Proceso</h3>
					<p class="text-muted text-center">Módulo de Acumulación</p>
                    
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <label className="col-form-label" >Proceso que se va a acumular:</label>
                            <input type="text" class="form-control procesos" id="procesoOrigen" placeholder="AAAA-0000" autoFocus/>
                            <span className="help-block">Vigencia-Número <i>Ej: 2020-0044</i></span>
                        </div>
                        <div class="col-sm-6">
                            <label className="col-form-label" >Proceso que recibe la acumulación:</label>
                            <input type="text" class="form-control procesos" id="procesoDestino" placeholder="AAAA-0000"/>
                            <span className="help-block">Vigencia-Número <i>Ej: 2019-0319</i></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <label className="col-form-label" >Proceso que se va a acumular:</label>
                            <div id="ajax-origen" style="width: 100%;background:#f9f9f9;min-height:200px;border-radius:5px;border:2px dotted #ddd"></div>
                        </div>
                        <div class="col-sm-6">
                            <label className="col-form-label" >Proceso que recibe la acumulación:</label>
                            <div id="ajax-destino" style="width: 100%;background:#f9f9f9;min-height:200px;border-radius:5px;border:2px dotted #ddd"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row" style="margin-top:20px">
                        <div class="col-sm-9">
                            <label className="col-form-label" >Motivo de la acumulación:</label>
                            <textarea id="motivo" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-sm-3">
                            <label className="col-form-label" >Fecha de la acumulación:</label>
                            <input type="text" class="form-control" id="fechaAcumulacion" placeholder="Fecha"/>
                        </div>
                    </div>
                    
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success btn-sm pull-right" onclick="acumularProceso();"><i class="fa fa-save"></i> Acumular Proceso</button>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<!-- # box-->
@section('scriptsFin') 
<script src="{{asset('js/jquery.mask.min.js')}}"></script>
<script src="{{asset('js/procesos/acumularProcesoAProceso.js?v=7')}}"></script>
<script>
$(function () { 
	$('#fechaAcumulacion').datepicker({
		autoclose: true,
		dateFormat: 'yyyy-mm-dd'
	});

    $('.procesos').mask('0000-0000');
});
</script>
@endsection
@stop