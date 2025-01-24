@if(count($quejas) > 0)	
	<div class="row">
		<div class="col-xs-12">
			<small class="label pull-right bg-blue">{{count($quejas)}} 
				@if(count($quejas)==1)
					queja
				@else
					quejas
				@endif
			</small>
			<form name="f1" id="f1">
		      	<table id="tablaQuejas" class="table table-bordered table-hover table-striped">
		            <thead>
		                <tr>
		                 	<th width="80">Queja</th>
		                 	<th>Tipo</th>
		                 	<th width="150">Estado Queja</th>
		                 	<th width="80">Proceso</th>
		                 	<th width="250" colspan="2">Abogado</th>
							<th width="110">Asignado</th>
							<th>Quejoso / Informante</th>
							<th>Presunto Responsable</th>
		                </tr>
		            </thead>
		            <tbody>
		           		@foreach ($quejas as $queja)							
							<tr>
								<td class="text-center">
									<a class="label-rdo" style="cursor: pointer;" onclick="verQueja({{$queja->idQueja}})">{{$queja->idQueja }}</a>
								</td>
								<td>{{ $queja->nombreOrigenQueja}}</td>
								<td>{{ $queja->descEstadoQueja }}</td>
								<td class="text-center" style="vertical-align:middle;">
									<a href="{{asset('/procesos/ver/'.$queja->Radicado_vigencia."/".$queja->Radicado_idRadicado)}}">
										<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
											{{ $queja->Radicado_vigencia."-".$queja->Radicado_idRadicado }}
										</span>
									</a>
								</td>
								<td style="padding: 0;">
			{{''; $nombre_fichero = '../public/img/fotos/'.$queja->documentoPersona.'.jpg' }}
            @if(file_exists($nombre_fichero))
            	<img src="{{ asset('img/fotos/'.$queja->documentoPersona.'.jpg')}}" title="{{ $queja->nombre; }}" class="img-responsive img-circle" style="width:40px; height:45px;">                	
            @else			                           
                @if(Util::traerGenero($queja->documentoPersona) == 'Femenino')
                    <img src="{{ asset('img/ella.png')}}" title="{{ $queja->nombre; }}" class="img-responsive img-circle" style="width:40px; height:45px;">
                @else
                    <img src="{{ asset('img/el.png')}}" title="{{ $queja->nombre; }}" class="img-responsive img-circle" style="width:40px; height:45px;">
    			@endif
            @endif
								</td>
								<td>{{ $queja->nombre }}</td>
								<td>{{ ($queja->fechaAsignacion) }}</td>
								<td>
									@if($queja->OrigenQueja_idOrigenQueja == 1)		
										{{""; 
											$quejosos = Util::traerQuejososPorQueja($queja->idQueja);							
										}}

										@if(count($quejosos) > 0)
											@foreach($quejosos as $quejoso)
												<li>{{ $quejoso->nombre }}</li>
											@endforeach
										@else
											<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
								                Anónimo / desconocido.
								            </div>
										@endif
									@else
										{{""; $informantes = DB::table('informante')
															->join('entidad', 'informante.Entidad_idEntidad', '=', 'entidad.idEntidad')
															->where('Queja_idQueja', '=', $queja->idQueja)
															->get(); 
										}}

										@if(count($informantes) > 0)
											@foreach($informantes as $informante)
												<li>{{ $informante->nombreEntidad }}</li>
											@endforeach
										@endif
									@endif
								</td>
								<td>
									{{""; 
										$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);
									}}

									@if(count($presuntos) > 0)
										@foreach($presuntos as $presunto)
											<li>{{ $presunto->nombre }}</li>
										@endforeach
									@else
										<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
							                Por determinar
							            </div>
									@endif
								</td>
							</tr>
						@endforeach  
		            </tbody>
		      	</table>
		    </form>	
		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron quejas en esta vigencia.
    </div>
@endif