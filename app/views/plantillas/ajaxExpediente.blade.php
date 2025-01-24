@if(count($archivos) > 0)
	<table id="tablaExpediente" class="table table-bordered table-hover table-striped" style="font-size:0.8em;">
	    <thead>
	        <tr>
	        	<th width="20"></th>
	         	<th width="160">Tipo</th>
				<th>Nombre del documento</th>
				<th width="150">Fecha aportado</th>
				<th width="170">Etapa</th>
	        </tr>
	    </thead>
	    <tbody>
	   		@foreach ($archivos as $archivo)							
				<tr>
					<td style="padding:0; vertical-align:middle;"><a href="javascript: void(0)" onclick="verArchivo('{{$archivo->idArchivo}}');"><img src="{{ asset('img/pdf.png')}}"></a></td>
					<td style="vertical-align:middle;"><b>{{ $archivo->descTipoArchivo }}</b></td>
					<td><a href="javascript: void(0)" onclick="verArchivo('{{$archivo->idArchivo}}');">{{ $archivo->nombreArchivo }}</a></td>
					<td>{{ Util::formatearFechaCorta($archivo->fechaSubido)." ".$archivo->horaSubido }}</td>
					<td><b>{{ $archivo->nombreEtapa }}</b></td>
				</tr>
			@endforeach  
	    </tbody>
	</table>
@else
	<div class="alert alert-white alert-dismissible" style="margin:20px;">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron archivos en el expediente de esta etapa
    </div>
@endif