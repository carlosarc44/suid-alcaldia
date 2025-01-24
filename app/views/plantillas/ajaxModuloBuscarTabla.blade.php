@if(count($quejas) > 0)		
    <div class="row">
        <div class="col-sm-10">
            <span style="color:#00a65a">
                {{ 'Se encontró: <b>'.$criterio.'</b> en '.count($quejas).' procesos'}}
            </span>
        </div>
    </div>	      	
    <div class="table-box" style="color:#fff">
        <div class="table-responsive">
            <table class="table-dark table-striped">
                <thead id="headerReporte">
                    <tr>
                        <th scope="col" style="width:3%">Queja</th>
                        <th scope="col" style="width:3%">Prescripción</th>
                        <th scope="col" style="width:10%">Quejoso</th>
                        <th scope="col" style="width:10%">Presuntos Hechos</th>
                        <th scope="col" style="width:29%">Estado Queja</th>
                        <th scope="col" style="width:5%">Etapa Actual</th>
                        <th scope="col" style="width:7%">Fechas</th>
                        <th scope="col" style="width:10%">Oficio</th>
                        <th scope="col" style="width:15%">Presunto Responsable</th>
                        <th scope="col" style="width:3%">Dependencia</th>
                        <th scope="col" style="width:5%">Falta</th>
                        <th scope="col" style="width:12%">Observaciones</th>
                    </tr>
                </thead>
                <tbody>                 
                    @foreach ($quejas as $queja)							
                        <tr>
                            <td class="text-center">
                                <div class="date strong">
                                    {{$queja->idQueja }}
                                </div>
                                <div class="time">
                                    {{ $queja->nombreOrigenQueja}}
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
                                            ->where('queja.idQueja', '=', $queja->idQueja)
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
                                <div class="mini-progress-circle" data-progress="{{round(Util::porcentajePrescripcion($abogado->Radicado_vigencia, $abogado->Radicado_idRadicado));}}"></div>            
                            </td>
                            <td>
                                {{""; 
                                    $quejosos = Util::traerQuejososPorQueja($queja->idQueja);
                                }}
                                <div class="ajax-listaQuejosos_4_{{$queja->idQueja}}">
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
                                        <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
                                    @else
                                        @if ($queja->anonimo == 1)
                                            <div class="strong outdated">
                                                Anónimo
                                            </div>
                                            <div class="time">
                                                Quejoso
                                            </div>
                                            <br>
                                            <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-xs btn-block transparente" style="margin-top:6px" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
                                            <br>
                                            <button type="button" class="btn btn-default btn-xs btn-block transparente" onclick="anonimo('{{$queja->idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>												
                                        @endif
                                    @endif
                                </div>
                            </td>		
                            <td>
                                {{ $queja->presuntosHechos}}
                            </td>						
                            <td>
                                @if($queja->EstadoQueja_idEstadoQueja == 6)
                                    {{''; 	$color = "assigned"; }}
                                @else
                                    {{''; $color = "rejected"; }}
                                @endif
                            
                                @if($queja->EstadoQueja_idEstadoQueja == 6 || $queja->EstadoQueja_idEstadoQueja == 7 )
                                    <div class="strong {{$color}}">
                                        {{ $queja->descEstadoQueja }}
                                    </div>
                                    
                                    <div class="time">
                                        el {{ date_format(date_create($queja->fechaAsignacion),"d/m/Y") }} a
                                    </div>
                                    
                                    <div class="strong">
                                        {{ $abogado->nombre; }}
                                    </div>
                                @else
                                    <div class="strong {{$color}}">
                                        {{ $queja->descEstadoQueja }}
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
                                    {{ date_format(date_create($queja->fechaQueja),"d/m/Y") }}
                                </div>
                                <div class="date strong">
                                    Recibida:
                                </div>
                                <div class="time">
                                    {{ date_format(date_create($queja->fechaRecepcionQueja),"d/m/Y") }}
                                </div>
                            </td>            
                            <td>{{ $queja->numeroOficio }}</td>								
                            <td>
                                {{""; 					
                                    $presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);														
                                }}
                                <div class="ajax-listaPresuntosResponsables_4_{{$queja->idQueja}}">
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
                                        <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Pre. Resp.</button>
                                    @else
                                        @if ($queja->porDeterminar == 1)
                                            <div class="strong outdated">
                                                Por Determinar
                                            </div>
                                            <div class="time">
                                                Presunto Responsable
                                            </div>
                                            <br>
                                            <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Pre. Resp.</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-xs btn-block transparente" style="margin-top:6px" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Agregar Pre. Resp.</button>
                                            <br>
                                            <button type="button" class="btn btn-default btn-xs btn-block transparente" onclick="porDeterminar('{{$queja->idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>{{ $queja->nombreDependencia}}</td>
                            <td>{{ $queja->falta}}</td>
                            <td>
                                {{""; $obs = DB::table('observacionesqueja')
                                                    ->where('Queja_idQueja', '=', $queja->idQueja)
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
                    @endforeach  
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="box box-default">
        <div class="box-body" style="display: block;">
            <div class="alert alert-white alert-dismissible" style="margin:20px;">
                <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                No se encontraton procesos
            </div>
        </div>
    </div>
@endif