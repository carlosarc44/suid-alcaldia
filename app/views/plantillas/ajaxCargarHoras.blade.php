<table class="table table-bordered table-hover">
    <thead>
	    <tr>
	      	<th style="width: 90px">Hora</th>
	      	<th>Tarea</th>
	      	<th style="width: 60px"></th>
	    </tr>
	</thead> 
	<tbody>   
	{{""; 
		$hora = "07:00:00";
		$idUsuario = Session::get('documentoUsuario');
	}}
		@for ($i=0; $i < 22; $i++)				
			<tr>    	
		      	<td style="background: #f0f0f0;">
		      		<li class="fa fa-clock-o"></li>
		      		{{date("g:i a", strtotime($hora))}}
		      	</td>
		      	<td>
		      		{{""; $tareas = Util::verificarHora($idUsuario, $fechaTarea, $hora)}}
				
		      		@if (count($tareas) > 0)
		      			<table style="width: 100%;">
			      			@foreach ($tareas as $tarea)
			      				<tr style="border:1px dotted #ddd;">
									<td style="border:1px dotted #ddd; text-align: center; width: 100px;">
										<a href="{{asset('/procesos/ver/'.$tarea->Radicado_vigencia."/".$tarea->Radicado_idRadicado)}}">
											<span class="label label-info" style="min-width:100px !important; font-size:0.9em; margin-right:5px;">
												{{ $tarea->Radicado_vigencia."-".$tarea->Radicado_idRadicado }}
											</span>
										</a>
									</td>
									<td style="border:1px dotted #ddd; padding-left: 5px;">
										<b>{{$tarea->asuntoTarea}}:</b>
										{{$tarea->descripcionTarea}}
									</td>
									<td style="border:1px dotted #ddd; width: 100px; text-align: center;">
										{{date("g:i a", strtotime($tarea->fechaInicioTarea))."<br> a <br>".date("g:i a", strtotime($tarea->fechaFinTarea))}}
									</td>
								</tr>		      				
			      			@endforeach
			      		</table>
		      		@else
		      			<p align="center" style="color:#ddd;">No hay tareas programadas</p>
		      		@endif

		      	</td>
		      	<td><button type="button" class="btn btn-info btn-sm pull-right" onclick="nuevaTarea('{{$fechaTarea}}', '{{$hora}}');"><i class="fa fa-clock-o"></i> Programar</button></td>
		    </tr>
		    {{""; 
				$hora = date("H:i:s", strtotime($hora) + 1800); //1800 = 30*60 (En este caso 30 minutos)
			}}
		@endfor
  	</tbody>
</table>