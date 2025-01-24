@if(count($tareas) > 0)
	<table id="tablaExpediente" class="table table-bordered table-hover table-striped" style="font-size:0.9em;">
	    <thead>
	        <tr>
	         	<th>Asunto</th>
				<th>Descripción</th>
				<th>Lugar</th>
				<th>Fecha</th>
				<th>Hora</th>
	        </tr>
	    </thead>
	    <tbody>
	   		@foreach ($tareas as $tarea)							
				<tr>
					<td style="vertical-align:middle;"><b>{{ $tarea->asuntoTarea }}</b></td>
					<td>{{ $tarea->descripcionTarea }}</td>
					<td><b>{{ $tarea->lugarTarea }}</b></td>
					<td>{{ Util::formatearFechaCorta($tarea->fechaInicioTarea)}}</td>
					<td>{{"<b>".date("g:i a", strtotime($tarea->fechaInicioTarea))."</b> a <b>".date("g:i a", strtotime($tarea->fechaFinTarea))."</b>"}}</td>
				</tr>
			@endforeach  
	    </tbody>
	</table>
@else
	<div class="alert alert-white alert-dismissible" style="margin:20px;">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron tareas pendientes
    </div>
@endif