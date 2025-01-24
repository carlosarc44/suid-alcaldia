@if (count($personas) > 0)
	<label>Elija el o los quejosos</label>
	<div class="row">
		<div class="col-xs-12">
			<table id="tablaPersonas" class="table table-bordered table-hover table-striped" style="font-size:0.9em">
				<thead>
					<tr>
						<th>Nombre / Documento</th>
						<th>Datos de Contacto</th>						
						<th>Quejoso en otras Quejas</th>
						<th width="50"></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($personas as $persona)							
						<tr>
							<td>
								{{ $persona->nombre }}
								<br>
								<span style="font-size:0.9em;color:#676767;font-weight:800">
									Documento: {{ $persona->documentoPersona }}
								</span>
							</td>
							<td>
								{{ $persona->direccionResidencia }}
								<br/>
								<span style="font-size:0.9em;color:#676767;font-weight:800">
									Teléfono: {{ $persona->telefono }}
								</span>
							</td>
							<td>
								{{''; 
									$quejas = Util::traerQuejasPorDocumento($persona->documentoPersona); 
									$cadena = '';							
								}}

								@if(count($quejas) > 0)
									@foreach ($quejas as $queja)
										{{''; $cadena = $cadena.'<div class="alert alert-white alert-dismissible" style="padding:4px; margin:3px; text-align: center;">Queja: '.$queja->Queja_idQueja.'</div>'; }}
									@endforeach
								@else
									{{'';
										$cadena = $cadena.'<div class="alert alert-white alert-dismissible" style="padding:4px; margin:3px; text-align: center;">Sin quejas asociadas</div>';
									}}
								@endif
								<span style="font-weight: 600;">
									{{$cadena}}
								</span>
							</td>
							<td style="padding:0;">
								<button type="button" onclick="seleccionadoQuejoso('{{$persona->documentoPersona}}');" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-check-square-o"></i> Elegir</button>
							</td>
						</tr>
					@endforeach   
				</tbody>
			</table>
		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible" style="margin:10px;">
		No se encontraron resultados para su búsqueda: <strong>{{$docQuejoso}}</strong>
		<button type="button" onclick="nuevoQuejoso();" class="btn btn-success btn-sm" style="margin-left:17px;"><i class="fa fa-user-plus"></i> Crear Nuevo Quejoso</button>
	</div>
@endif