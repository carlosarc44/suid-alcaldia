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
								<a href="javascript:void(0)" onclick="fijarDestinatarioOfGral('{{ $persona->documentoPersona }}')" style="font-weight:600">
									{{ $persona->nombre }}
								</a>
								<br>
								<span style="font-size:0.95em;color:#676767;">
									Documento: {{ $persona->documentoPersona }}
								</span>
							</td>
							<td>
								<span style="font-size:0.95em;color:#676767;">
									Dirección: {{ $persona->direccionResidencia }}
								</span>
								<br/>
								<span style="font-size:0.95em;color:#676767;">
									Teléfono: {{ $persona->telefono }}
								</span>

								<br/>
								<span style="font-size:0.95em;color:#676767;">
									Email: {{ $persona->email }}
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
								<button type="button" onclick="editarPersona('{{$persona->documentoPersona}}');" class="btn btn-block btn-danger btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-edit"></i> Editar</button>
							</td>
						</tr>
					@endforeach   
				</tbody>
			</table>
		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible" style="margin:10px;">
		No se encontraron resultados para su búsqueda
	</div>
@endif