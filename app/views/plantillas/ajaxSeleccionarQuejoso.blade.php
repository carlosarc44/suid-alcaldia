<div class="row">
	<div class="col-xs-12">
      	<table id="tablaPersonas" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                 	<th>Nombre</th>
					<th>Documento</th>
					<th>Dirección</th>						
					<th>Teléfono</th>
					<th width="50"></th>
                </tr>
            </thead>
            <tbody>
           		@foreach ($personas as $persona)							
					<tr>
						<td>{{ $persona->nombre }}</td>
						<td>{{ $persona->documentoPersona }}</td>
						<td>{{ $persona->direccionResidencia }}</td>
						<td>{{ $persona->telefono }}</td>
						<td style="padding:0;">
							<button type="button" onclick="seleccionadoQuejoso('{{$persona->nombre}}', '{{$persona->documentoPersona}}', '{{$persona->direccionResidencia}}');" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-check-square-o"></i></button>
						</td>
					</tr>
				@endforeach   
            </tbody>
      	</table>
	</div>
</div>