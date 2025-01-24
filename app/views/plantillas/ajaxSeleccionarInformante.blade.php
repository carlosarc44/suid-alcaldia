<div class="row">
	<div class="col-xs-12">
      	<table id="tablaEntidades" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                 	<th>Nombre</th>
					<th>Dirección</th>						
					<th>Teléfono</th>
					<th width="50"></th>
                </tr>
            </thead>
            <tbody>
           		@foreach ($entidades as $entidad)							
					<tr>
						<td>{{ $entidad->nombreEntidad }}</td>
						<td>{{ $entidad->direccionEntidad }}</td>
						<td>{{ $entidad->telefonoEntidad }}</td>
						<td style="padding:0;">
							<button type="button" onclick="seleccionadoInformante('{{$entidad->idEntidad}}', '{{$entidad->nombreEntidad}}', '{{$entidad->direccionEntidad}}', '{{$entidad->telefonoEntidad}}');" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-check-square-o"></i></button>
						</td>
					</tr>
				@endforeach   
            </tbody>
      	</table>
	</div>
</div>