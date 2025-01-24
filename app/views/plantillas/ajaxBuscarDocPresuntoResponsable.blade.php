@if (count($personas) > 0)
	<label>Elija el o los presuntos responsables</label>
	<div class="row">
		<div class="col-xs-12">
			<table id="tablaPersonas" class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th>Nombre / Documento</th>
						<th>Datos de Contacto</th>						
                        <th>Dependencia / Cargo</th>
						<th>Pr. Resp. en otras Quejas</th>
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
								{{ $persona->nombreDependencia }}
								<br/>
                            	{{ $persona->nombreCargo }}
							</td>
							<td>
								{{''; 
									$quejas = Util::traerPresuntosResponsablesPorDocumento($persona->documentoPersona); 
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
								<button type="button" onclick="seleccionadoPresuntoResponsable('{{$persona->documentoPersona}}');" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-check-square-o"></i></button>
							</td>
						</tr>
					@endforeach   
				</tbody>
			</table>
		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible" style="margin:10px;">
		No se encontró una persona con el documento: <strong>{{$docPresuntoResponsable}}</strong>
		<button type="button" onclick="nuevoPresuntoResponsable();" class="btn btn-success btn-sm" style="margin-left:17px;"><i class="fa fa-user-plus"></i> Crear Presunto Responsable</button>
	</div>
@endif