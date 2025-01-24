@if (count($archivos) > 0)
	<span class="pull-right-container">
	    <small class="label pull-right bg-red">
	    	@if (count($archivos) == 1)
	    		{{count($archivos)}} archivo pendiente por subir
	    	@else
	    		{{count($archivos)}} archivos pendientes por subir
	    	@endif    	
	    </small>
	</span>

	<table id="tablaArchivosGenerados" class="table table-bordered table-hover table-striped" style="font-size:0.9em; margin-top:20px;">
	    <thead>
	        <tr>
	        	<th>Nº</th>
	         	<th width="100">Tipo</th>
				<th>Nombre del documento</th>
				<th width="150">Fecha generado</th>
				<th width="30"></th>
				<th width="30"></th>
	        </tr>
	    </thead>
	    <tbody>
	   		@foreach ($archivos as $archivo)							
				<tr>
					<td><b>{{ $archivo->idArchivoGenerado }}</b></td>
					<td style="vertical-align:middle;"><b>{{ $archivo->descTipoArchivo }}</b></td>
					<td>{{ $archivo->nombreArchivoGenerado }}</td>
					<td>{{ Util::formatearFechaCorta($archivo->fechaArchivoGenerado)." ".$archivo->horaArchivoGenerado }}</td>
					<td>
						<button type="button" class="btn btn-info btn-sm pull-right" onclick="mostrarSeleccionarArchivo('{{$archivo->idArchivoGenerado}}');"><i class="fa fa-external-link"></i> Agregar</button>
					</td>
					<td>
						<button type="button" class="btn btn-danger btn-sm pull-right" onclick="borrarArchivo('{{$archivo->idArchivoGenerado}}');"><i class="fa fa-trash"></i> </button>
					</td>
				</tr>
			@endforeach  
	    </tbody>
	</table>
@else
	<div class="alert alert-white alert-dismissible" style="margin:20px;">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron archivos pendientes por subir
    </div>
@endif