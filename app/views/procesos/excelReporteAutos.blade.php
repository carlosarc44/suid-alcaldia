<html>
    <head>
        <title>SUID</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <table>
            <tr>
                <td rowspan="2"><img src="../public/img/logo-2024-n.png" height="80"/></td> 
            </tr>
        </table>
        <table>			              
            <tr>
                <th width="5" style="background-color:#05529F; color:#FFF; text-align:center;">Fila</th>
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Número Auto</th>
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Fecha Auto</th>
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Vigencia Auto</th>
                <th width="35" style="background-color:#05529F; color:#FFF; text-align:center;">Etapa Auto</th>
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Tipo</th>
                <th width="10" style="background-color:#05529F; color:#FFF; text-align:center;">Queja</th>
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Proceso</th>
                <th width="80" style="background-color:#05529F; color:#FFF; text-align:center;">Presuntos Hechos</th>
                <th width="50" style="background-color:#05529F; color:#FFF; text-align:center;">Quejoso</th>
                <th width="30" style="background-color:#05529F; color:#FFF; text-align:center;">Estado Queja</th>                
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Fecha Reparto</th>
                <th width="40" style="background-color:#05529F; color:#FFF; text-align:center;">Abogado asignado</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Fecha de la Queja</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Fecha de Recepción</th>
                <th width="40" style="background-color:#05529F; color:#FFF; text-align:center;">Oficio</th>
                <th width="50" style="background-color:#05529F; color:#FFF; text-align:center;">Presunto Responsable</th>
                <th width="100" style="background-color:#05529F; color:#FFF; text-align:center;">Presuntos hechos</th>
                <th width="60" style="background-color:#05529F; color:#FFF; text-align:center;">Presunto lugar</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Dependencia</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Falta</th>
                <th width="30" style="background-color:#05529F; color:#FFF; text-align:center;">Etapa Actual</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Valoración</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Indagación Previa</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Investigación Disciplinaria</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Prórroga Inv. Disc.</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Evaluación</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Pliego de Cargos</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Descargos</th>
                <th width="25" style="background-color:#059f19; color:#FFF; text-align:center;">Prueba de Descargos</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Alegatos de Conclusión</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Fallo</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Archivo</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Inhibitorio</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Prescripción</th>
                <th width="20" style="background-color:#059f19; color:#FFF; text-align:center;">Caducidad</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Variación Pliego de Cargos</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Acumulado a otro Proceso</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Remitido por Competencia</th>
                <th width="30" style="background-color:#059f19; color:#FFF; text-align:center;">Cancelación</th>
            </tr>
            @if(count($quejas) > 0)	
                {{""; $contador = 1; }}	 		      	
                @foreach ($quejas as $queja)	
                    {{""; 
                        $datosQueja = Util::traerDatosQueja($queja->vigencia, $queja->idRadicado);
                    }}		
                    @if (count($datosQueja) > 0)				
                        <tr>
                            <td><strong>{{$contador}}</strong></td>
                            <td>{{$queja->idAuto}}</td>
                            <td>{{$queja->fechaAuto}}</td>
                            <td>{{$queja->vigenciaAuto }}</td>
                            <td>{{$queja->etapaAuto }}</td>
                            <td>{{$datosQueja->nombreOrigenQueja}}</td>
                            <td><strong>{{$datosQueja->idQueja }}</strong></td>
                            <td>
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
                                <strong>{{ $abogado->Radicado_vigencia."-".$abogado->Radicado_idRadicado }}</strong>
                            </td>
                            <td>
                                {{ $datosQueja->presuntosHechos}}
                            </td>
                            <td>
                                {{""; 
                                    $quejosos = Util::traerQuejososPorQueja($datosQueja->idQueja);
                                }}
                                @if(count($quejosos) > 0)
                                    @foreach($quejosos as $quejoso)
                                        {{$quejoso->nombre." ".$quejoso->documentoPersona." "}}
                                    @endforeach
                                @else
                                    @if ($datosQueja->anonimo == 1)
                                        Anónimo
                                    @endif
                                @endif
                            </td>								
                            <td>
                                @if($datosQueja->EstadoQueja_idEstadoQueja == 6 || $datosQueja->EstadoQueja_idEstadoQueja == 7 )
                                    {{ $datosQueja->descEstadoQueja }}
                                @endif
                            </td>
                            <td>
                                {{ date_format(date_create($queja->fechaAsignacion),"d/m/Y") }}
                            </td>
                            <td>
                                {{ $abogado->nombre; }}
                            </td>                        
                            <td>
                                {{ date_format(date_create($datosQueja->fechaQueja),"d/m/Y") }}
                            </td>   
                            <td>
                                {{ date_format(date_create($datosQueja->fechaRecepcionQueja),"d/m/Y") }}
                            </td>         
                            <td>{{ $datosQueja->numeroOficio }}</td>								
                            <td>
                                {{""; 					
                                    $presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($datosQueja->idQueja);							
                                }}
                                @if(count($presuntosresponsables) > 0)
                                    @foreach($presuntosresponsables as $presuntoresponsable)
                                        {{$presuntoresponsable->nombre." ".$presuntoresponsable->documentoPersona." "}}
                                    @endforeach
                                @else
                                    @if ($datosQueja->porDeterminar == 1)
                                        Por determinar
                                    @endif
                                @endif
                            </td>
                            <td>{{ $datosQueja->presuntosHechos}}</td>
                            <td>{{ $datosQueja->presuntoLugar}}</td>
                            <td>{{ $datosQueja->nombreDependencia}}</td>
                            <td>{{ $queja->falta}}</td>
                            <td>
                                <strong>{{ $queja->etapaProceso}}</strong>
                            </td>
                            @if (count($abogado) > 0)
                                <td>
                                    {{''; $etapas = Util::traerEtapasExcel($abogado->Radicado_vigencia, $abogado->Radicado_idRadicado);

                                        //Declara las fechas de las etapas vacías
                                        $fechaEtapa1 = '';
                                        $fechaEtapa2 = '';
                                        $fechaEtapa3 = '';
                                        $fechaEtapa4 = '';
                                        $fechaEtapa5 = '';
                                        $fechaEtapa6 = '';
                                        $fechaEtapa7 = '';
                                        $fechaEtapa8 = '';
                                        $fechaEtapa9 = '';
                                        $fechaEtapa10 = '';
                                        $fechaEtapa11 = '';
                                        $fechaEtapa12 = '';
                                        $fechaEtapa13 = '';
                                        $fechaEtapa15 = '';
                                        $fechaEtapa16 = '';
                                        $fechaEtapa17 = '';
                                        $fechaEtapa18 = '';
                                        $fechaEtapa19 = '';

                                        if(count($etapas) > 0)
                                        {
                                            foreach ($etapas as $etapa) 
                                            {
                                                switch ($etapa->Etapa_idEtapa) {
                                                    case '1':
                                                        $fechaEtapa1 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                        break;
                                                    case '2':
                                                        $fechaEtapa2 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '3':
                                                        $fechaEtapa3 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '4':
                                                        $fechaEtapa4 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '5':
                                                        $fechaEtapa5 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '6':
                                                        $fechaEtapa6 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '7':
                                                        $fechaEtapa7 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '8':
                                                        $fechaEtapa8 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '9':
                                                        $fechaEtapa9 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '10':
                                                        $fechaEtapa10 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '11':
                                                        $fechaEtapa11 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '12':
                                                        $fechaEtapa12 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '13':
                                                        $fechaEtapa13 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;                                                
                                                    case '15':
                                                        $fechaEtapa15 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '16':
                                                        $fechaEtapa16 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '17':
                                                        $fechaEtapa17 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '18':
                                                        $fechaEtapa18 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                    case '19':
                                                        $fechaEtapa14 = date_format(date_create($etapa->fechaEtapa),"d/m/Y");
                                                    break;
                                                }
                                            }
                                        }
                                    }}

                                    {{$fechaEtapa11}}
                                </td>
                                <td>{{$fechaEtapa1}}</td>
                                <td>{{$fechaEtapa2}}</td>
                                <td>{{$fechaEtapa3}}</td>
                                <td>{{$fechaEtapa4}}</td>
                                <td>{{$fechaEtapa5}}</td>
                                <td>{{$fechaEtapa13}}</td>
                                <td>{{$fechaEtapa6}}</td>
                                <td>{{$fechaEtapa7}}</td>
                                <td>{{$fechaEtapa8}}</td>
                                <td>{{$fechaEtapa10}}</td>
                                <td>{{$fechaEtapa9}}</td>
                                <td>{{$fechaEtapa15}}</td>
                                <td>{{$fechaEtapa16}}</td>
                                <td>{{$fechaEtapa17}}</td>
                                <td>{{$fechaEtapa18}}</td>
                                <td>{{$fechaEtapa19}}</td>
                                <td>{{$fechaEtapa12}}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            <td></td>
                        </tr>
                    @else
                        <tr>
                            <td><strong>{{$contador}}</strong></td>
                            <td>{{$queja->idAuto}}</td>
                            <td>{{$queja->vigenciaAuto }}</td>
                            <td>{{$queja->etapaAuto }}</td>
                            <td style="color:#ff0000;" colspan="35">
                                {{ "<strong>Auto: ".$queja->idAuto." de ".$queja->vigenciaAuto." de ".$queja->etapaAuto.".</strong> ".$queja->observacionAuto; }}
                            </td>
                        </tr>
                    @endif
                    {{""; $contador++; }}
                @endforeach  
            @endif
        </table>
    </body>
</html>