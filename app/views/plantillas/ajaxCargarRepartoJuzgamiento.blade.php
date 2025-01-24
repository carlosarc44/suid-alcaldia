<style>
    .mini-progress-circle:before {
        background-color: #fff !important;
        color: #222 !important;
    }
</style>
<h4 style="color:#444">Encontramos <strong>{{count($procesos)}}</strong> {{count($procesos) == 1 ? 'proceso ' : 'procesos '}} para reparto en la etapa: <strong>Avoca Conocimiento (Juzgamiento)</strong></h4>
<hr>
<div class="table-responsive mailbox-messages">
    <table class="table table-hover table-striped">
        <tbody>
            @if (count($procesos) > 0)
                <tr>
                    <th>Proceso</th>
                    <th>Prescripción</th>
                    <th>Quejoso</th>
                    <th>Presuntos Hechos</th>
                    <th>Estado Queja</th>
                    <th>Fechas</th>
                    <th>Oficio</th>
                    <th>Presunto Responsable</th>
                    <th>Dependencia</th>
                    <th>Falta</th>
                </tr>
                @foreach ($procesos as $proceso)
                    <tr>
                        <td class="text-center">
                            <div class="date strong">
                                {{ $proceso->idQueja }}
                            </div>
                            <div class="time">
                                {{ $proceso->nombreOrigenQueja }}
                            </div>
                            <br>
                            <div class="date strong" style="padding-bottom: 6px">
                                <a href="{{ asset('/procesos/ver/' . $proceso->vigencia . '/' . $proceso->idRadicado) }}"
                                    target="_blank">
                                    <span class="btn btn-info btn-xs btn-block" style="min-width:100px !important;">
                                        {{ $proceso->vigencia . '-' . $proceso->idRadicado }}
                                    </span>
                                </a>
                            </div>
                            <div class="time">
                                PROCESO
                            </div>
                        </td>
                        <td style="text-align:center">
                            <div class="mini-progress-circle"
                                data-progress="{{ round(Util::porcentajePrescripcion($proceso->vigencia, $proceso->idRadicado)) }}">
                            </div>
                        </td>
                        <td>
                            {{ '';
                            $quejosos = Util::traerQuejososPorQueja($proceso->idQueja) }}
                            <div class="ajax-listaQuejosos_4_{{ $proceso->idQueja }}">
                                @if (count($quejosos) > 0)
                                    @foreach ($quejosos as $quejoso)
                                        <div class="strong outdated">
                                            {{ $quejoso->nombre }}
                                        </div>
                                        <div class="time">
                                            {{ $quejoso->documentoPersona }}
                                        </div>
                                    @endforeach
                                @else
                                    @if ($proceso->anonimo == 1)
                                        <div class="strong outdated">
                                            Anónimo
                                        </div>
                                        <div class="time">
                                            Quejoso
                                        </div>
                                    @else
                                        No se encontraron Quejosos
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td>{{ $proceso->presuntosHechos }}</td>
                        <td>
                            @if ($proceso->EstadoQueja_idEstadoQueja == 6 || $proceso->EstadoQueja_idEstadoQueja == 7 || $proceso->EstadoQueja_idEstadoQueja == 8 || $proceso->EstadoQueja_idEstadoQueja == 9)
                                <div style="font-weight:600;color:{{ $proceso->EstadoQueja_idEstadoQueja == 6 ? '#00ff00' : '#ff0000' }}">
                                    {{ $proceso->descEstadoQueja }}
                                </div>
                                <div class="time">
                                    {{"";
                                        $datosAbogado = Util::traerDatosAbogadoActual($proceso->vigencia, $proceso->idRadicado);
                                    }}
                                    {{ count($datosAbogado) > 0 ? 'el '.date_format(date_create($datosAbogado->fechaAsignacion), 'd/m/Y').' a ' : '<br>El proceso no tiene aún un abogado' }} 
                                </div>
                                <div class="strong">{{ count($datosAbogado) > 0 && $datosAbogado->nombre  }}</div>
                            @else
                                <div style="font-weight:600;color:{{ $proceso->EstadoQueja_idEstadoQueja == 6 ? '#00ff00' : '#ff0000' }}">
                                    {{ $proceso->descEstadoQueja }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="date strong">
                                Queja:
                            </div>
                            <div class="time">
                                {{ date_format(date_create($proceso->fechaQueja), 'd/m/Y') }}
                            </div>
                            <div class="date strong">
                                Recibida:
                            </div>
                            <div class="time">
                                {{ date_format(date_create($proceso->fechaRecepcionQueja), 'd/m/Y') }}
                            </div>
                        </td>
                        <td>{{ $proceso->numeroOficio }}</td>
                        <td>
                            {{ '';
                            $presuntosresponsables = Util::traerPresuntosResponsablesPorQueja($proceso->idQueja) }}
                            <div class="ajax-listaPresuntosResponsables_4_{{ $proceso->idQueja }}">
                                @if (count($presuntosresponsables) > 0)
                                    @foreach ($presuntosresponsables as $presuntoresponsable)
                                        <div class="strong outdated">
                                            {{ $presuntoresponsable->nombre }}
                                        </div>
                                        <div class="time">
                                            {{ $presuntoresponsable->documentoPersona }}
                                        </div>
                                    @endforeach
                                @else
                                    @if ($proceso->porDeterminar == 1)
                                        <div class="strong outdated">
                                            Por Determinar
                                        </div>
                                        <div class="time">
                                            Presunto Responsable
                                        </div>
                                    @else
                                        No se encontraron presuntos responsables
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td>{{ $proceso->nombreDependencia }}</td>
                        <td>{{ $proceso->falta }}</td>
                    </tr>
                @endforeach
            @else
                <div class="row">
                    <div class="col-sm-12">
                    <div class="widget-user-header bg-gray-light" style="text-align: center;padding: 50px 10px">
                        <div class="widget-user-image">
                        <br>
                        <img src="{{ asset('img/logo-2024-n.png')}}" height="100">
                        </div>
                        <br>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">No se encontraron nuevos procesos para reparto </h3>
                        <h5 class="widget-user-desc">Cuando se envíe un proceso a esta fase de Juzgamiento se le notificará vía correo electrónico</h5>
                    </div>
                    </div>
                </div>
            @endif
        </tbody>
    </table>
</div>
