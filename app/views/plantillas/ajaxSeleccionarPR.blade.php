<div class="row">
	<div class="col-xs-12">
      	<table id="tablaFuncionarios" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                 	<th>Nombre</th>
					<th>Documento</th>													
					<th>Cargo</th>
					<th>Dependencia</th>
					<th width="50"></th>
                </tr>
            </thead>
            <tbody>
           		@foreach ($funcionarios as $funcionario)							
					<tr>
						<td>{{ $funcionario->nombre }}</td>
						<td>{{ $funcionario->documentoPersona }}</td>
						<td>{{ $funcionario->nombreDependencia }}</td>
						<td>{{ $funcionario->nombreCargo }}</td>
						<td style="padding:0;">
							<button type="button" onclick="seleccionadoPR('{{$funcionario->idFuncionario}}','{{$funcionario->nombre}}', '{{$funcionario->documentoPersona}}', '{{$funcionario->nombreCargo}}', '{{$funcionario->nombreDependencia}}');" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-check-square-o"></i></button>
						</td>
					</tr>
				@endforeach  
            </tbody>
      	</table>
	</div>
</div>