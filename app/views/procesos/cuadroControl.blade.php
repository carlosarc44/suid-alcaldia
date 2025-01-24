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
</style>
{{ HTML::script('js/ajax.js') }}
@stop
<!--includes de la cabecera-->

<!--miga de pan-->
@section('migaPan')   

<!--menu lateral izquierdo-->
@section('menuLateral') 
  @include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cuadro de Control</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body" style="overflow-x: scroll; height: 500px; padding-right: 15px !important;" id="resultadoQuejas">
		@if(count($quejas) > 0)	
	<div class="row">
		<div class="col-xs-12">
			<small class="label pull-right bg-blue">{{count($quejas)}} 
				@if(count($quejas)==1)
					proceso
				@else
					procesos
				@endif
			</small>
			<form name="f1" id="f1">
		      	<table id="tablaProcesos" class="table table-bordered table-hover table-striped" style="width: 3000px;">
		            <thead>
		                <tr>		                 	
		                 	<th width="80">Proceso</th>
		                 	<th>Radicación</th>							
							<th>Quejoso</th>
							<th>Reparto</th>
							<th>Prioridad</th>
							<th>Fecha Hechos</th>
							<th>Prescipción</th>
							<th>Situación Fáctica</th>
							<th>Etapa Actual</th>
							<th>Inicio</th>
							<th>Vencimiento</th>
							<th>Sujeto Procesal</th>
							<th>Dependencia</th>
							<th>Cargo</th>
							<th>Notificación última etapa</th>
							<th width="80">Queja</th>
		                </tr>
		            </thead>
		            <tbody>
		           		@foreach ($quejas as $queja)							
							<tr>
								<td class="text-center" style="vertical-align:middle;">
									<a href="{{asset('/procesos/ver/'.$queja->Radicado_vigencia."/".$queja->Radicado_idRadicado)}}">
										<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
											{{ $queja->Radicado_vigencia."-".$queja->Radicado_idRadicado }}
										</span>
									</a>
								</td>	
								<td>{{ date_format(date_create($queja->fechaAcumula),"d/m/Y") }}</td>
								<td>
									{{""; 
										$quejosos = Util::traerQuejososPorQueja($queja->idQueja); 
									}}

									@if(count($quejosos) > 0)
										@foreach($quejosos as $quejoso)
											<li>{{ $quejoso->nombre }}</li>
										@endforeach
									@else
										<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
							                Anónimo o Desconocido
							            </div>
									@endif
								</td>
								<td>
									@if($queja->fechaAsignacion == "Inf. 2014")
										Anterior a 2014
									@else
										{{ date_format(date_create($queja->fechaAsignacion),"d/m/Y") }}
									@endif
								</td>
								<td>Prioridad</td>
								<td>{{ date_format(date_create($queja->presuntaFecha),"d/m/Y") }}</td>
								<td>Prescripcion</td>
								<td>{{$queja->presuntosHechos}}</td>
								<td>
									{{'';
										$etapa = DB::table('etapasproceso')
							  				->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
											->where('Radicado_idRadicado', '=', $queja->Radicado_idRadicado)
											->where('Radicado_vigencia', '=', $queja->Radicado_vigencia)
											->where('actual', '=', 1)
							                ->get();										  	
									}}
									{{$etapa[0]->nombreEtapa}}
								</td>
								<td>{{$etapa[0]->fechaEtapa}}</td>
								<td>{{$etapa[0]->fechaFinalEtapa}}</td>
								<td>
									{{""; 
										$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);
									}}

									@if(count($presuntos) > 0)
										@foreach($presuntos as $presunto)
											<li>{{ $presunto->nombre }}</li>
										@endforeach
									@else
										<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
							                Por determinar
							            </div>
									@endif
								</td>
								<td>
									@if(count($presuntos) > 0)
										@foreach($presuntos as $presunto)
											<li>{{ $presunto->nombreDependencia }}</li>
										@endforeach
									@else
										<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
							                Por determinar
							            </div>
									@endif
								</td>
								<td>
									@if(count($presuntos) > 0)
										@foreach($presuntos as $presunto)
											<li>{{ $presunto->nombreCargo }}</li>
										@endforeach
									@else
										<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
							                Por determinar
							            </div>
									@endif
								</td>
								<td></td>
								<td class="text-center">
									<a href="javascript: void(0)" style="color:#000;" onclick="verQueja({{$queja->idQueja}})">{{ $queja->idQueja }}</a>
								</td>
							</tr>
						@endforeach  
		            </tbody>
		      	</table>
		    </form>	
		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron quejas en esta vigencia.
    </div>
@endif
	</div>
</div>

<!-- MODAL -->
<div class="modal fade in" id="modalVerQueja">
  <div class="modal-dialog" style="width:96%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Ver Queja</h4>
      </div>
      <div class="modal-body" style="background:#f0f0f0;">
        <!-- resultadoVerQueja -->
		<div id="resultadoVerQueja">
			<!-- CONTENIDO AJAX --> 
		</div>
		<!-- # resultadoVerQueja -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@stop

<!--scriptsFin-->
@section('scriptsFin') 
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
$(window).load(function() {
    $('#tablaActivos').DataTable({
	    	'iDisplayLength': 50,
	    	'order': [[0, "asc"]]
	});   
});
</script>
@stop