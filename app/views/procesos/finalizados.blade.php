@extends('plantillas.layout')
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
<h1>Finalizados</h1>
<!--  MIGA DE PAN -->
<ol class="breadcrumb">
	<li><a href="javascript: void(0)"><i class="fa fa-home"></i> Inicio</a></li>
	<li class="active">Procesos Finalizados</li>
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
		<h3 class="box-title">Procesos Finalizados</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">
				<i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body" style="display: block;" id="resultadoQuejas">
		@if(count($procesos) > 0)	
			<div class="row">
				<div class="col-xs-12">
					<small class="label pull-right bg-blue">{{count($procesos)}} 
						@if(count($procesos)==1)
							proceso
						@else
							procesos
						@endif
					</small>
					<form name="f1" id="f1">
				      	<table id="tablaActivos" class="table table-bordered table-hover table-striped">
				            <thead>
				                <tr>
				                 	<th width="80">Proceso</th>
									<th width="110">Queja</th>	
									<th>Quejoso</th>
									<th>Presunto Responsable</th>
									<th>Etapa Final</th>
									<th>Finalización</th>
				                </tr>
				            </thead>
				            <tbody>
				           		@foreach ($procesos as $proceso)							
									<tr>
										<td class="text-center" style="vertical-align:middle;">
											<a href="{{asset('/procesos/ver/'.$proceso->vigencia."/".$proceso->idRadicado)}}">
												<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
													{{ $proceso->vigencia."-".$proceso->idRadicado }}
												</span>
											</a>
										</td>
										<td>
											<ul style="list-style-type:circle;">
												{{Util::traerNumeroQueja($proceso->vigencia, $proceso->idRadicado)}}</td>
											</ul>
										<td>{{Util::traerQuejosos($proceso->vigencia, $proceso->idRadicado)}}</td>
										<td>{{Util::traerPresuntosResponsables($proceso->vigencia, $proceso->idRadicado)}}</td>
										<td>{{$proceso->nombreEtapa}}</td>
										<td>{{ Util::formatearFechaCorta($proceso->fechaObservacion)." ".$proceso->horaObservacion}}</td>
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
                No se encontraron procesos finalizados
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