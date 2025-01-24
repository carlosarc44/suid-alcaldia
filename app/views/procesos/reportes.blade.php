@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
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

@import url('https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet');

/* html {
  scroll-behavior: smooth;
}

@media (prefers-reduced-motion: reduce) {
  html {
    scroll-behavior: auto;
  }
} */
.scroll {
  cursor: pointer;
  width: 70px;
  height: 70px;
  position: fixed;
  bottom: 40px;
  right: -80px;
  border-radius: 100%;
  background-color:#00a65a; 
  color: #fff;
  font-size: 44px;
  font-weight: bold;
  text-align: center;
  transition: 300ms;
}

.scroll i {
  margin-top: 10px;
  text-shadow: 0 0 2px #fff;
}

.scroll:hover i {
  animation-name: rotate;
  animation-duration: 300ms;
  animation-iteration-count: infinite;
  animation-direction: alternate;
}

@keyframes rotate {
  from {margin-top: 15px}
  to {margin-top: 5px}
}

.visible {
  right: 30px;
  transition: all 400ms;
  transform: rotate(360deg)
}


@import url('https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700&display=swap');
/*LIGHT THEME*/
@media print {
	* {
		color-adjust: exact!important;
		-webkit-print-color-adjust: exact!important;
		print-color-adjust: exact!important;
		/*In FireFox it's in Page Setup -> [Format & Options] Tab under Options.*/
	}
	// If Table Issue with BG
	th,tr,td {
        color-adjust: exact!important;
		-webkit-print-color-adjust: exact!important;
		print-color-adjust: exact!important;
	}
	tr:nth-child(even) td {
        background: light !important;
    }
}

/*DARK THEME*/
.dark-theme {background: #23252b;}
.dark-theme .table-dark {box-shadow: 0 0 10px 0 rgba(0,0,0,.1);}
.dark-theme .table-dark,
.dark-theme .table-dark tr>th .users {color: #a9b5cb;}
.dark-theme .table-dark,
.dark-theme .table-dark tr {background: #2c2e37;}
.dark-theme .table-dark tr th {background: #161821;}
.dark-theme .table-dark tr th {border:0}
.dark-theme .table-dark tbody tr:hover {background: #2c2e37;}
.dark-theme .table-dark tr>th,
.dark-theme .table-dark .project,
.dark-theme .table-dark .date {color: white;}

.assigned {color: #8BC34A;}
.rejected {color: #ffb533;}
.outdated {color: #03A9F4;}
.dot-online {background: #8BC34A;}
.dot-offline {background: #e91e63;}

.strong,strong {font-weight: 600;}
.table-box {padding: 2px;}
.table-dark thead th {
    padding-top: 10px !important;
	padding-bottom: 10px !important;
	text-align: left !important;
}
.table-dark {
	border: 1px solid #444444;
	font-family: 'Quicksand';
    font-size: 13px;
}
.table-dark tr {
    border-width: 1px 0;
    border-style: solid;
}
.table-dark th,
.table-dark td {vertical-align: middle;}
.table-dark tr th,
.table-dark td {
    padding-left: 10px !important;
	padding-right: 10px;
	border-top: 1px solid #474749 !important
}
.table-dark thead th {
    font-size: 16px;
	font-weight: 600;
	box-shadow: inset 0px 0px 0px 0px #ffffff !important
}
.table-dark thead th,
.table-dark td, .table-dark th {border: 0;}
.table-dark .time {font-size: 80%;}
.status-indicator {
    width: 5px;
    height: 5px;
    border-radius: 50px;
}
.project-description {
    width: 300px;
    white-space: initial !important;
}
.table-dark.table-striped>tbody>tr:nth-of-type(odd) {
	background-color: #23252c !important;
}
.select2-container--default .select2-selection--multiple {
	background-color: transparent !important;
	border:1px solid #5d5959 !important;
}
.content-wrapper {
	background: #000;
    color: white;
}
.transparente {
	background-color: transparent !important;
	color:#fff !important;
}
option {
	color:#666
}
.box {
	border-top:0;
}.form-control {
	border:1px solid #5d5959;
}
fieldset {
	border: 1px solid #5d5959 !important;
    border-radius: 10px;
    padding: 10px 20px;
}
</style>
@stop
<!--includes de la cabecera-->

<!--menu lateral izquierdo-->
@section('menuLateral') 
@include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
<div class="row">
	<div class="col-md-12">
		<div class="box box-info widget-user-2 dark-theme">
			<div class="widget-user-header bg-white-light">
				<div class="widget-user-image">
					<img src="{{ asset('img/SUID_transp3.png')}}" class="img-circle desaturada">
				</div>
				<h3 class="widget-user-username">Módulo de Reportes</h3>
				<h5 class="widget-user-desc">Oficina de Control Disciplinario Interno</h5>

				<div id="loader" style="height:100px;width:120px;position: absolute;top:4px;right:50%"></div>
			</div>
			<fieldset style="margin:6px">
				<div class="row" style="margin-bottom: 8px">
					<div class="col-sm-2">
						<label class="pull-right">Abogado:</label>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							@if (Session::get('idAbogado') < 0)
								@if (Session::get('perfilUsuario') == 2)
										{{ Form::select('abogado', array('0' => 'Todos los abogados') + $lista_abogados, 
											Session::get('idAbogado'), array('class' => 'form-control transparente', 'id'=>'abogado', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}	
								@else
									Usted no está registrado como abogado								
								@endif								
							@else
								@if (Session::get('perfilUsuario') == 2)
									{{ Form::select('abogado', array('0' => 'Todos los abogados') + $lista_abogados, 
										0, array('class' => 'form-control transparente', 'id'=>'abogado', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}	
								@else
									<select class="form-control transparente" id="abogado" style="width:100%">
										<option value="{{Session::get('idAbogado')}}">{{Session::get('nombresUsuario')}}</option>
									</select>
								@endif
							@endif
						</div>
					</div>		
					<div class="col-sm-2">
						<label class="pull-right">Vigencia:</label>
					</div>
					<div class="col-sm-4">              
						{{''; $vigenciaActual = date("Y");}}  
						<select class="form-control select2 select2-hidden-accessible transparente" id="vigencia" multiple autocomplete="new-password" style="width:100%; margin:4px 0;">
                            <option value='0' selected>Todas las vigencias</option>
                            <?php 
                                for ($i=2014; $i<=$vigenciaActual; $i++) 
                                {
                                  echo "<option value='$i'>$i</option>";
                                }  
                                ?>
                        </select>
					</div>					
				</div>
				
				<div class="row" style="margin-bottom: 8px">
					<div class="col-sm-2">
						<label class="pull-right">Etapa:</label>
					</div>
					<div class="col-sm-4">              
						{{ Form::select('etapa', array('0' => 'Todas las etapas') + $lista_etapas, 
							0, array('class' => 'form-control select2 select2-hidden-accessible transparente', 'multiple' => 'multiple', 'autocomplete' => 'new-password', 'id'=>'etapa', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
					</div>
					<div class="col-sm-2">
						<label class="pull-right">Estado:</label>
					</div>
					<div class="col-xs-4">
						<select class="form-control transparente" id="estado" onchange="vigenciaHistorico(this.value);" style="width:100%; margin:4px 0;">
							<option value='1'>Procesos en estado Activo</option>
							<option value='2'>Todos los estados</option>
							<option value='0'>Procesos en estado Finalizado</option>
					    </select>
					</div>		
				</div>

				<div class="row">
					<div class="col-sm-2">
						<label class="pull-right">Dependencia:</label>
					</div>
					<div class="col-sm-4">
							{{ Form::select('dependenciaReporte', array('0' => 'Todas las dependencias') + $lista_dependencias, 
					0, array('class' => 'form-control transparente select2 select2-hidden-accessible', 'multiple' => 'multiple', 'autocomplete' => 'new-password', 'id'=>'dependenciaReporte', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
					</div>
					<div class="col-sm-2">
						<label class="pull-right">Falta:</label>
					</div>
					<div class="col-sm-4">
							{{ Form::select('faltas', array('0' => 'Todas las faltas') + $lista_faltas, 
					0, array('class' => 'form-control transparente select2 select2-hidden-accessible', 'multiple' => 'multiple', 'autocomplete' => 'new-password', 'id'=>'faltas', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
					</div>
				</div>
					<div class="row" style="margin-top:10px">
						<div class="col-sm-12">
							@if (Session::get('idAbogado') < 0)
								@if (Session::get('perfilUsuario') == 2)
									<button class="btn btn-info pull-right" onclick="ejecutarReporte(1)">
										<i class="fa fa-bolt"></i> Ejecutar Consulta
									</button>
								@else
									<button class="btn btn-info pull-right" disabled>
										<i class="fa fa-bolt"></i> Ejecutar Consulta
									</button>								
								@endif
							@else
								<button class="btn btn-info pull-right" onclick="ejecutarReporte(1)">
									<i class="fa fa-bolt"></i> Ejecutar Consulta
								</button>
							@endif
						</div>
				</div>
			</fieldset>
			<br>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box box-gray">
			<div class="box-header dark-theme">
				<div id="ajax-resumen"><!--ajax--></div>
			</div>
			<div class="box-body dark-theme">
				<input type="hidden" id="inicio" value="0"/>
				<input type="hidden" id="limite" value="10"/>
				<div class="table-box" id="tabla-reporte" style="display:none">
			        <div class="table-responsive">
        				<table class="table-dark table-striped">
							<thead id="headerReporte">
								<tr>
									<th scope="col" style="width:3%">Queja</th>
									<th scope="col" style="width:3%">Prescripción</th>
									<th scope="col" style="width:10%">Quejoso</th>
									<th scope="col" style="width:10%">Presuntos Hechos</th>
									<th scope="col" style="width:29%">Estado Queja</th>
									<th scope="col" style="width:5%">Etapa Actual</th>
									<th scope="col" style="width:7%">Fechas</th>
									<th scope="col" style="width:10%">Oficio</th>
									<th scope="col" style="width:15%">Presunto Responsable</th>
									<th scope="col" style="width:3%">Dependencia</th>
									<th scope="col" style="width:5%">Falta</th>
									<th scope="col" style="width:12%">Observaciones</th>
								</tr>
							</thead>
							<tbody id="ajax-reporte">
								<!-- carga ajax -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="jsScroll" class="scroll" onclick="scrollToTop();">
	<i class="fa fa-angle-up"></i>
</div>

@stop

<!--scriptsFin-->
@section('scriptsFin') 
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js" integrity="sha512-Lii3WMtgA0C0qmmkdCpsG0Gjr6M0ajRyQRQSbTF6BsrVh/nhZdHpVZ76iMIPvQwz1eoXC3DmAg9K51qT5/dEVg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.js"></script>
<script src="{{asset('js/procesos/reportes.js?v='.rand(0,1000))}}"></script>

<script type="text/javascript">

$(document).ready(function(){
	ejecutarReporte(1)
	$('.sidebar-toggle').click()
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'new-password')
    })
    $("input.select2-input").attr('autocomplete','new-password')
});

$(function () { 
	//Initialize Select2 Elements
	$(".select2").select2(
		{
			'columnNum': 2
		}
	);
/*
	$("#divReporte").stickme({
		top: 48
	});
*/
	//$("#tablaReporte").freezeTable();




});
</script>
@stop
<!--# scriptsFin -->