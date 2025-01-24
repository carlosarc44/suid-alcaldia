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
            	<img src="{{ asset('img/fotos/'.$queja->documentoPersona.'.jpg')}}" title="{{ $queja->nombre; }}" class="img-responsive" style="width:40px; height:45px;">                	
            @else			                           
                @if(Util::traerGenero($queja->documentoPersona) == 'Femenino')
                    <img src="{{ asset('img/ella.png')}}" title="{{ $queja->nombre; }}" class="img-responsive" style="width:40px; height:45px;">
                @else
                    <img src="{{ asset('img/el.png')}}" title="{{ $queja->nombre; }}" class="img-responsive" style="width:40px; height:45px;">
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
										<ul class="list-group list-group-unbordered">
											@foreach($quejosos as $quejoso)
												<li class="list-group-item" style="padding:6px">
													<b>{{$quejoso->nombre}}</b>
													<br>
													<span style="color:#888787;font-size:0.95em;">{{$quejoso->documentoPersona}}</span>
												</li>
											@endforeach
										</ul>
									@else
										@if ($queja->anonimo == 1)
											<ul class="list-group list-group-unbordered">
												<li class="list-group-item" style="padding:6px">
													<b>ANÓNIMO</b>
													<br>
													<span style="color:#888787;font-size:0.95em;">Quejoso</span>
												</li>
											</ul>
										@else
											<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
												No se ha indicado si es anónimo
											</div>											
										@endif
									@endif
								</td>
								<td>
									{{""; 
										$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);			
									}}
									@if(count($presuntos) > 0)
										<ul class="list-group list-group-unbordered">
											@foreach($presuntos as $presunto)
												<li class="list-group-item" style="padding:6px">
													<b>{{$presunto->nombre}}</b>
													<br>
													<span style="color:#888787;font-size:0.95em;">{{$presunto->documentoPersona}}</span>
												</li>
											@endforeach
										</ul>
									@else
										@if (Util::esPorDeterminar($queja->idQueja) == 1)
											<ul class="list-group list-group-unbordered">
												<li class="list-group-item" style="padding:6px">
													<b>POR DETERMINAR</b>
													<br>
													<span style="color:#888787;font-size:0.95em;">Presunto Responsable</span>
												</li>
											</ul>
										@else
											<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
												No se ha indicado si es por determinar
											</div>										
										@endif 
									@endif
								</td>
								<td class="text-center">
									<a href="javascript: void(0)" style="color:#000;" onclick="verQueja({{$queja->idQueja}})">{{ $queja->idQueja }}</a>
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