@if(count($quejas) > 0)		
    {{""; $contador = 1; }}	      	
    @foreach ($quejas as $queja)	
        {{""; 
            $datosQueja = Util::traerDatosQueja($queja->vigencia, $queja->idRadicado);
            if (count($datosQueja) == 0) 
            {
                
                //return;
            } 
        }}		

        @if (count($datosQueja) > 0)
            <tr>            
                <td><strong>{{$inicio+$contador}}</strong></td>
                <td class="text-center">                
                    <br>
                    <div class="strong rejected">
                        {{$queja->idAuto." de ".$queja->vigenciaAuto }}
                    </div>
                   del {{ date_format(date_create($queja->fechaAuto),"d/m/Y") }}
                    <br> <br>
                    <div class="strong assigned">
                        {{$queja->etapaAuto }}
                    </div>
                    Etapa Auto
                    <br><br>
                </td>
                <td class="text-center">            
                    <div class="date strong">
                        {{$datosQueja->idQueja }}
                    </div>
                    <div class="time">
                        {{ $datosQueja->nombreOrigenQueja}}
                    </div>
                    <br>
                        {{'';
                            $abogado = DB::table('queja')
                                ->leftJoin('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
                                ->leftJoin('abogadoasignado', function($join)
                                    {
                                        $join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
                                            ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
                                            ->where('abogadoasignado.actual', '=', 'SI');
                                    })
                                ->leftJoin('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
                                ->leftJoin('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
                                ->where('queja.idQueja', '=', $datosQueja->idQueja)
                                ->first();
                        }}
                        
                        <div class="date strong" style="padding-bottom: 6px">
                            <a href="{{asset('/procesos/ver/'.$abogado->Radicado_vigencia."/".$abogado->Radicado_idRadicado)}}" target="_blank">
                                <span class="btn btn-info btn-xs btn-block" style="min-width:100px !important;">
                                    {{ $abogado->Radicado_vigencia."-".$abogado->Radicado_idRadicado }}
                                </span>
                            </a> 
                        </div>
                        <div class="time">
                            PROCESO
                        </div>                                       
                </td>
                <td style="text-align:center">
                    @if ($abogado->Radicado_vigencia != '')
                        <div class="mini-progress-circle" data-progress="{{round(Util::porcentajePrescripcion($abogado->Radicado_vigencia, $abogado->Radicado_idRadicado));}}"></div>                                    
                    @endif
                </td>
                <td>
                    {{""; 
                        $quejosos = Util::traerQuejososPorQueja($datosQueja->idQueja);
                    }}
                    <div class="ajax-listaQuejosos_4_{{$datosQueja->idQueja}}">
                        @if(count($quejosos) > 0)
                            @foreach($quejosos as $quejoso)
                                <div class="strong outdated">
                                    {{$quejoso->nombre}}
                                </div>
                                <div class="time">
                                    {{$quejoso->documentoPersona}}
                                </div>
                            @endforeach
                                <br>
                            <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarQuejoso('{{$datosQueja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
                        @else
                            @if ($datosQueja->anonimo == 1)
                                <div class="strong outdated">
                                    Anónimo
                                </div>
                                <div class="time">
                                    Quejoso
                                </div>
                                <br>
                                <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarQuejoso('{{$datosQueja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
                            @else
                                <button type="button" class="btn btn-danger btn-xs btn-block transparente" style="margin-top:6px" onclick="agregarQuejoso('{{$datosQueja->idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
                                <br>
                                <button type="button" class="btn btn-default btn-xs btn-block transparente" onclick="anonimo('{{$datosQueja->idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>												
                            @endif
                        @endif
                    </div>
                </td>		
                <td>
                    {{ $datosQueja->presuntosHechos}}
                </td>						
                <td>
                    @if($datosQueja->EstadoQueja_idEstadoQueja == 6)
                        {{''; 	$color = "assigned"; }}
                    @else
                        {{''; $color = "rejected"; }}
                    @endif
                
                    @if($datosQueja->EstadoQueja_idEstadoQueja == 6 || $datosQueja->EstadoQueja_idEstadoQueja == 7 )
                        <div class="strong {{$color}}">
                            {{ $datosQueja->descEstadoQueja }}
                        </div>
                        
                        <div class="time">
                            el {{ date_format(date_create($queja->fechaAsignacion),"d/m/Y") }} a
                        </div>
                        
                        <div class="strong">
                            {{ $abogado->nombre; }}
                        </div>
                    @else
                        <div class="strong {{$color}}">
                            {{ $datosQueja->descEstadoQueja }}
                        </div>
                    @endif
                </td>
                <td>  
                    <div class="loader" id="ajax-vencimientos_{{$queja->vigencia.'-'.$queja->idRadicado}}">
                        {{ Util::traerVencimientoProceso($queja->vigencia, $queja->idRadicado) }}
                    </div>
                </td>
                <td>
                    <div class="date strong">
                        Queja:
                    </div>
                    <div class="time">
                        {{ date_format(date_create($datosQueja->fechaQueja),"d/m/Y") }}
                    </div>
                    <div class="date strong">
                        Recibida:
                    </div>
                    <div class="time">
                        {{ date_format(date_create($datosQueja->fechaRecepcionQueja),"d/m/Y") }}
                    </div>
                </td>            
                <td>{{ $datosQueja->numeroOficio }}</td>								
                <td>
                    {{""; 					
                        $presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($datosQueja->idQueja);														
                    }}
                    <div class="ajax-listaPresuntosResponsables_4_{{$datosQueja->idQueja}}">
                        @if(count($presuntosresponsables) > 0)
                            @foreach($presuntosresponsables as $presuntoresponsable)
                                <div class="strong outdated">
                                    {{$presuntoresponsable->nombre}}
                                </div>
                                <div class="time">
                                    {{$presuntoresponsable->documentoPersona}}
                                </div>
                            @endforeach
                            <br>
                            <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarPresuntoResponsable('{{$datosQueja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Pre. Resp.</button>
                        @else
                            @if ($datosQueja->porDeterminar == 1)
                                <div class="strong outdated">
                                    Por Determinar
                                </div>
                                <div class="time">
                                    Presunto Responsable
                                </div>
                                <br>
                                <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarPresuntoResponsable('{{$datosQueja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Pre. Resp.</button>
                            @else
                                <button type="button" class="btn btn-danger btn-xs btn-block transparente" style="margin-top:6px" onclick="agregarPresuntoResponsable('{{$datosQueja->idQueja}}');"><i class="fa fa-user"></i> Agregar Pre. Resp.</button>
                                <br>
                                <button type="button" class="btn btn-default btn-xs btn-block transparente" onclick="porDeterminar('{{$datosQueja->idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>
                            @endif
                        @endif
                    </div>
                </td>
                <td>{{ $datosQueja->nombreDependencia}}</td>
                <td>{{ $queja->falta}}</td>
                <td>
                    {{""; $obs = DB::table('observacionesqueja')
                                        ->where('Queja_idQueja', '=', $datosQueja->idQueja)
                                        ->get(); 
                    }}

                    @if(count($obs) > 0)
                        @foreach($obs as $ob)
                            <div class="date strong">
                                {{ date_format(date_create($ob->fechaObservacion),"d/m/Y") }}:
                            </div>
                            <div class="time">
                                {{ $ob->observacion }}
                            </div>
                        @endforeach
                    @else
                        Sin observaciones
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <td><strong>{{$inicio+$contador}}</strong></td>
                <td class="text-center">                
                    <br>
                    <div class="strong rejected">
                        {{$queja->idAuto." de ".$queja->vigenciaAuto }}
                    </div>
                    Número Auto
                    <br> <br>
                    <div class="strong assigned">
                        {{$queja->etapaAuto }}
                    </div>
                    Etapa Auto
                    <br><br>
                </td>
                <td style="color:#f13468;" colspan="12">
                    {{ "<strong>Auto: ".$queja->idAuto." de ".$queja->vigenciaAuto." de ".$queja->etapaAuto.".</strong> ".$queja->observacionAuto; }}
                </td>
            </tr>
        @endif
        {{""; $contador++; }}
    @endforeach  
@endif