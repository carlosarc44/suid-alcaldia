<div class="table-responsive mailbox-messages">
	@if (count($autos)>0)												
		<table class="table table-hover table-striped" style="font-size:0.9em;">
			<tbody>
				@foreach ($autos as $auto)
					<tr>
						<td class="text-center" width="20">
							<a href="{{asset('/procesos/ver/'.$auto->Radicado_vigencia."/".$auto->Radicado_idRadicado)}}">
								<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
									{{ $auto->Radicado_vigencia."-".$auto->Radicado_idRadicado }}
								</span>
							</a>
						</td>
						<td class="mailbox-subject" width="190">
							<b>{{$auto->nombreEtapa}}</b>
						</td>
						<td class="mailbox-subject">{{$auto->observaciones}}</td>
						<td class="mailbox-name" width="170">
							{{$auto->nombre}}
						</td>
						<td class="mailbox-date" width="70">
							{{
								date_format(date_create($auto->fechaSolicitudAuto),"d/m/Y h:i a");
							}}
						</td>
						<td width="80">
							<button type="button" class="btn btn-info btn-sm pull-right btn-block" onclick="asignarNumero('{{$auto->idEtapa}}', '{{$auto->Radicado_vigencia}}', '{{$auto->Radicado_idRadicado}}', '{{$auto->idSolicitudAuto}}')"><i class="fa fa-undo"></i> Asignar</button>
							<button type="button" class="btn btn-default btn-sm pull-right btn-block" onclick="eliminarSolicitud('{{$auto->idSolicitudAuto}}')"><i class="fa fa-trash"></i> Eliminar</button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<!-- /.table -->
	@else
		<div class="alert alert-white alert-dismissible" style="margin:20px;">
            <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
            No se encontraron solicitudes de números de auto.
        </div>
	@endif
</div>