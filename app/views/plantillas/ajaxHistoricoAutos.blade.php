<span style="margin-left:20px;font-weight:bold;font-size:1.1em;color:red">
	{{count($numerosAuto)}} Autos
</span>
<hr>
@if (count($numerosAuto) > 0)
  	<table id="tablaHistoricoAutos" class="table table-bordered table-hover table-striped" style="font-size:0.9em;">
        <thead>
            <tr>	                	
             	<th style="width: 5%">Auto</th>
				<th style="width: 10%">Vigencia</th>
				<th style="width: 15%">Fecha Auto</th>
				<th style="width: 15%">Fase</th>
				<th style="width: 20%">Etapa</th>
				<th style="width: 10%">Proceso</th>				
				<th style="width: 25%">Abogado</th>
            </tr>
        </thead>
        <tbody>
       		@foreach ($numerosAuto as $auto)							
				<tr>
					<td style="vertical-align:middle;"><b>{{ $auto->idAuto }}</b></td>
					<td style="vertical-align:middle;">{{ $auto->vigenciaAuto }}</td>
					<td>{{ Util::formatearFechaCorta($auto->fechaAuto) }}</td>
					<td><b>{{$auto->tipoEtapa}}</b></td>
					<td><b>{{ $auto->nombreEtapa }}</b></td>
					<td class="text-center">
						<a href="{{asset('/procesos/ver/'.$auto->Radicado_vigencia."/".$auto->Radicado_idRadicado)}}">
							<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
								{{ $auto->Radicado_vigencia."-".$auto->Radicado_idRadicado }}
							</span>
						</a>
					</td>
					<td>{{Util::traerNombreAbogado($auto->Radicado_vigencia, $auto->Radicado_idRadicado)}}</td>
				</tr>
			@endforeach  
        </tbody>
  	</table>
@else
	<div class="alert alert-white alert-dismissible" style="margin:20px;">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron números de auto.
    </div>
@endif
