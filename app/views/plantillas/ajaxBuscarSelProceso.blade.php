@if(count($quejas) > 0)	
	<div class="row">
		<div class="col-xs-12">
			<small class="label pull-right bg-blue">{{count($quejas)}} 
				@if(count($quejas)==1)
					proceso
				@else
					procesos
				@endif
			</small>
			<form name="f1" id="f1">
		      	<table id="tablaProcesos" class="table table-bordered table-hover table-striped">
		            <thead>
		                <tr>		                 	
		                 	<th width="80">Proceso</th>
		                 	<th width="40"></th>
		                 	<th width="250">Abogado</th>
							<th width="110">Asignado</th>
							<th>Quejoso</th>
							<th>Presunto Responsable</th>
							<th width="80">Queja</th>
							<th width="60"></th>
		                </tr>
		            </thead>
		            <tbody>
		           		@foreach ($quejas as $queja)							
							<tr>
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
								<td>
									@if($queja->fechaAsignacion == "Inf. 2014")
										Anterior a 2014
									@else
										{{ date_format(date_create($queja->fechaAsignacion),"d/m/Y") }}
									@endif
								</td>
								<td>
									{{""; 
										$quejosos = Util::traerQuejososPorQueja($queja->idQueja);
									}}

									@if(count($quejosos) > 0)
										@foreach($quejosos as $quejoso)
											<li>{{ $quejoso->nombre }}</li>
										@endforeach
									@else
										<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
							                Anónimo o Desconocido
							            </div>
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
								<td class="text-center">
									<a href="javascript: void(0)" onclick="verQueja({{$queja->idQueja}})">{{ $queja->idQueja }}</a>
								</td>
								<td>
									<button type="button" onclick="seleccionadoProceso('{{ $queja->Radicado_vigencia."-".$queja->Radicado_idRadicado }}');" class="btn btn-block btn-info btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-check-square-o"></i></button>
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