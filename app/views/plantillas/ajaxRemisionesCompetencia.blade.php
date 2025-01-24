@if(count($remisiones) > 0)	
	<div class="row">
		<div class="col-xs-12">
			<small class="label pull-right bg-blue">{{count($remisiones)}} 
				@if(count($remisiones)==1)
					remisión
				@else
					remisiones
				@endif
			</small>
			<div>
		      	<table id="tablaQuejas" cellspacing="0" width="100%" class="table table-bordered table-hover table-striped" style="font-size:0.85em">
		            <thead>
		                <tr>
                            <th width="80">Queja</th>
                            <th width="90">Tipo</th>
		                 	<th width="110">N° RXC</th>
		                 	<th width="200">Tipo Remisión</th>
		                 	<th width="280">Motivo</th>
		                 	<th width="90">Fecha Remisión</th>
		                 	<th width="180">Oficio Remisión</th>
							<th>Quejoso / Informante</th>
		                 	<th width="160">Estado Queja</th>
		                 	<th width="100">Fecha Queja</th>
		                 	<th width="100">Recepción</th>
							<th width="120">Oficio</th>
							<th>Presunto Responsable</th>
							<th width="130">Dependencia</th>
							<th>Presuntos Hechos</th>
							<th>Presunto Lugar</th>
							<th>Observaciones</th>
		                </tr>
		            </thead>
		            <tbody>
		           		@foreach ($remisiones as $remision)							
							<tr>
								<td class="text-center">
									<a class="label-rdo" style="cursor: pointer;" onclick="verQueja({{$remision->idQueja}})">{{$remision->idQueja }}</a>
								</td>
								<td>{{ $remision->nombreOrigenQueja}}</td>
								<td>RXC {{ $remision->idRemisionCompetencia."/".$remision->vigenciaRemision}}</td>
								<td>{{ $remision->tipoRemision}}</td>
								<td>{{ $remision->motivoRemisionCompetencia}}</td>
								<td>{{ $remision->fechaRemisionCompetencia}}</td>
								<td>{{ $remision->oficioRemisionCompetencia}}</td>
								<td>
									{{""; 
										$quejosos = Util::traerQuejososPorQueja($remision->idQueja);
									}}
									<div class="ajax-listaQuejosos_2_{{$remision->idQueja}}">
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

										   <button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarQuejoso('{{$remision->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>

										@else
											@if ($remision->anonimo == 1)
												<ul class="list-group list-group-unbordered">
													<li class="list-group-item" style="padding:6px">
														<b>ANÓNIMO</b>
														<br>
														<span style="color:#888787;font-size:0.95em;">Quejoso</span>
													</li>
												</ul>

												<button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarQuejoso('{{$remision->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
											@else
												<button type="button" class="btn btn-danger btn-xs btn-block" style="margin-top:6px" onclick="agregarQuejoso('{{$remision->idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
												<br>
												<button type="button" class="btn btn-default btn-xs btn-block" onclick="anonimo('{{$remision->idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>												
											@endif
										@endif
										<!--ajax-->
									</div>
								</td>								
								<td style="text-align: center">
                                    {{'';
                                        $abogado = DB::table('queja')
                                            ->join('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
                                            ->join('abogadoasignado', function($join)
                                                {
                                                    $join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
                                                        ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
                                                        ->where('abogadoasignado.actual', '=', 'SI');
                                                })
                                            ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
                                            ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
                                            ->where('queja.idQueja', '=', $remision->idQueja)
                                            ->first();
                                    }}
                            
                                    <strong>{{ $remision->descEstadoQueja }}</strong> 
                                    @if (count($abogado) > 0)
                                        <br>
                                        <span style="font-size:0.72em; font-weight:bold; color:#999">{{ $abogado->nombre; }}</span>
                                        <br>
                                        <a href="{{asset('/procesos/ver/'.$abogado->Radicado_vigencia."/".$abogado->Radicado_idRadicado)}}" style="margin-top:12px">
                                            <span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
                                                {{ $abogado->Radicado_vigencia."-".$abogado->Radicado_idRadicado }}
                                            </span>
                                        </a>
                                    @endif
                                </td>
								<td>{{ date_format(date_create($remision->fechaQueja),"d/m/Y") }}</td>
								<td>{{ date_format(date_create($remision->fechaRecepcionQueja),"d/m/Y") }}</td>
								<td>{{ $remision->numeroOficio }}</td>								
								<td>
									{{""; 					
										$presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($remision->idQueja);														
									}}
									<div class="ajax-listaPresuntosResponsables_2_{{$remision->idQueja}}">
										@if(count($presuntosresponsables) > 0)
											<ul class="list-group list-group-unbordered">
												@foreach($presuntosresponsables as $presuntoresponsable)
													<li class="list-group-item" style="padding:6px">
														<b>{{$presuntoresponsable->nombre}}</b>
														<br>
														<span style="color:#888787;font-size:0.95em;">{{$presuntoresponsable->documentoPersona}}</span>
													</li>
												@endforeach
											</ul>
											<button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarPresuntoResponsable('{{$remision->idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
										@else
											@if ($remision->porDeterminar == 1)
												<ul class="list-group list-group-unbordered">
													<li class="list-group-item" style="padding:6px">
														<b>POR DETERMINAR</b>
														<br>
														<span style="color:#888787;font-size:0.95em;">Presunto Responsable</span>
													</li>
												</ul>

												<button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarPresuntoResponsable('{{$remision->idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
											@else
												<button type="button" class="btn btn-danger btn-xs btn-block" style="margin-top:6px" onclick="agregarPresuntoResponsable('{{$remision->idQueja}}');"><i class="fa fa-user"></i> Agregar Presunto Responsable</button>
												<br>
												<button type="button" class="btn btn-default btn-xs btn-block" onclick="porDeterminar('{{$remision->idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>											
											@endif
										@endif
									</div>
								</td>
								<td>{{ $remision->nombreDependencia}}</td>
								<td>{{ $remision->presuntosHechos }}</td>
								<td>{{ $remision->presuntoLugar }}</td>
								<td>
                                    {{""; $obs = DB::table('observacionesqueja')
                                                   ->where('Queja_idQueja', '=', $remision->idQueja)
                                                     ->get(); 
                                    }}
                                    <div class="global">
                                        <div class="mensajes">
                                            <div class="texto">
                                                @if(count($obs) > 0)
                                                    <table>											
                                                        @foreach($obs as $ob)
                                                            <tr>
                                                                <td style ="padding:4px;"><span style="font-weight:bold">{{ date_format(date_create($ob->fechaObservacion),"d/m/Y") }}</span>
                                                                    <br>
                                                                    {{ $ob->observacion }}
                                                                </td>
                                                            </tr>	      
                                                        @endforeach
                                                    </table>
                                                @else
                                                    <li>Sin observaciones</li>
                                                @endif
                                            </div>                                          
                                        </div>
                                    </div>
								</td>
							</tr>
						@endforeach  
		            </tbody>
		      	</table>
		    </div>	
		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron quejas en esta vigencia.
    </div>
@endif