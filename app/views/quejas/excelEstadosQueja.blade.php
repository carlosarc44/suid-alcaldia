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
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Tipo</th>
                <th width="10" style="background-color:#05529F; color:#FFF; text-align:center;">Queja</th>
                <th width="15" style="background-color:#05529F; color:#FFF; text-align:center;">Proceso</th>
                <th width="50" style="background-color:#05529F; color:#FFF; text-align:center;">Estado Queja</th>                
                <th width="80" style="background-color:#05529F; color:#FFF; text-align:center;">Presuntos Hechos</th>
                <th width="60" style="background-color:#05529F; color:#FFF; text-align:center;">Quejoso</th>
                <th width="40" style="background-color:#05529F; color:#FFF; text-align:center;">Abogado asignado</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Fecha de la Queja</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Fecha de Recepción</th>
                <th width="40" style="background-color:#05529F; color:#FFF; text-align:center;">Oficio</th>
                <th width="50" style="background-color:#05529F; color:#FFF; text-align:center;">Presunto Responsable</th>
                <th width="100" style="background-color:#05529F; color:#FFF; text-align:center;">Presuntos hechos</th>
                <th width="60" style="background-color:#05529F; color:#FFF; text-align:center;">Presunto lugar</th>
                <th width="20" style="background-color:#05529F; color:#FFF; text-align:center;">Dependencia</th>
                <th width="70" style="background-color:#059f19; color:#FFF; text-align:center;">Falta</th>
            </tr>
            @if(count($quejas) > 0)			      	
                @foreach ($quejas as $queja)							
                    <tr>
                        <td>{{ $queja->nombreOrigenQueja}}</td>
                        <td><strong>{{$queja->idQueja }}</strong></td>
                        <td>
                            {{'';
                                $radicado = DB::table('queja')
                                    ->join('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
                                    ->join('abogadoasignado', function($join)
                                        {
                                            $join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
                                                ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
                                                ->where('abogadoasignado.actual', '=', 'SI');
                                        })
                                    ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
                                    ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
                                    ->where('queja.idQueja', '=', $queja->idQueja)
                                    ->first();
                            }}

                            @if (count($radicado) > 0)
                                {{ $radicado->Radicado_vigencia."-".$radicado->Radicado_idRadicado }}
                            @endif
                        </td>
                        <td>
                            {{ $queja->descEstadoQueja }}
                        </td> 
                        <td>
                            {{ $queja->presuntosHechos}}
                        </td>
                        <td>
                            {{""; 
                                $quejosos = Util::traerQuejososPorQueja($queja->idQueja);
                            }}
                            @if(count($quejosos) > 0)
                                @foreach($quejosos as $quejoso)
                                    {{$quejoso->nombre." ".$quejoso->documentoPersona." "}}
                                @endforeach
                            @else
                                @if ($queja->anonimo == 1)
                                    Anónimo
                                @endif
                            @endif
                        </td>								                                               
                        <td>
                            @if (count($radicado) > 0)
                                {{ $radicado->nombre; }}
                            @endif
                        </td>                        
                        <td>
                            {{ date_format(date_create($queja->fechaQueja),"d/m/Y") }}
                        </td>   
                        <td>
                            {{ date_format(date_create($queja->fechaRecepcionQueja),"d/m/Y") }}
                        </td>         
                        <td>{{ $queja->numeroOficio }}</td>								
                        <td>
                            {{""; 					
                                $presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);							
                            }}
                            @if(count($presuntosresponsables) > 0)
                                @foreach($presuntosresponsables as $presuntoresponsable)
                                    {{$presuntoresponsable->nombre." ".$presuntoresponsable->documentoPersona." "}}
                                @endforeach
                            @else
                                @if ($queja->porDeterminar == 1)
                                    Por determinar
                                @endif
                            @endif
                        </td>
                        <td>{{ $queja->presuntosHechos}}</td>
                        <td>{{ $queja->presuntoLugar}}</td>
                        <td>{{ $queja->nombreDependencia}}</td>
                        <td>
                            @if (count($radicado) > 0)
                                {{Util::actionTraerNombreFalta($radicado->Radicado_vigencia, $radicado->Radicado_idRadicado)}}                                
                            @endif
                        </td>
                    </tr>
                @endforeach  
            @endif
        </table>
    </body>
</html>