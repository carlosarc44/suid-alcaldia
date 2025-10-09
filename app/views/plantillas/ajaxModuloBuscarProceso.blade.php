<div style="background:#ecf0f5;padding:20px;border-radius:4px">
    @if (count($proceso) > 0)
        @if ($fase != '')
            <!-- box -->
            <div class="box">
                <div class="box-body" style="display: block; padding: 0px !important;min-height:440px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="{{ $fase == 1 ? 'active' : '' }}"><a href="#tab_instruccion"
                                            data-toggle="tab"
                                            onclick="traerFase({{ '1, ' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ', 0' }});">Fase
                                            de Instrucción</a></li>
                                    <li class="{{ $fase == 2 ? 'active' : '' }}"><a href="#tab_juicio" data-toggle="tab"
                                            onclick="traerFase({{ '2, ' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ', 0' }});">Fase
                                            de Juzgamiento</a></li>
                                    <li class="{{ $fase == 3 ? 'active' : '' }}"><a href="#tab_segunda" data-toggle="tab"
                                            onclick="traerFase({{ '3, ' . $proceso[0]->vigencia . ', ' . $proceso[0]->idRadicado . ', 0' }});">Segunda
                                            Instancia</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_instruccion" style="background: #fff">
                                        <div id="ajax-fase"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- # box -->
        @endif

        {{ '';
        $numerosQuejas = Util::traerQuejasProceso($proceso[0]->vigencia, $proceso[0]->idRadicado);
        $display = '' }}

        <!-- row -->
        <div class="row" style="margin-top:130px; padding:0">
            <div class="row">
                <div class="col-md-4">
                    {{ Util::traerWidgetPrescripcion($proceso[0]->vigencia, $proceso[0]->idRadicado, 0) }}
                </div>

                <div class="col-md-4">

                    <!--  info-box -->
                    {{ Util::traerWidgetProceso($proceso[0]->vigencia, $proceso[0]->idRadicado) }}
                    <!--  # info-box -->
                    <div id="ajax-widgetFaltas">
                        {{ Util::traerWidgetFaltas($proceso[0]->vigencia, $proceso[0]->idRadicado, 0) }}
                    </div>

                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <br>
                            <h3 class="profile-username text-center" style="font-size:16px;">
                                {{""; $nombreAbogado = Util::traerNombreAbogado($proceso[0]->vigencia, $proceso[0]->idRadicado);}}
                                {{$nombreAbogado != "" ? $nombreAbogado : 'No se encontró un abogado a cargo aún'}}
                            </h3>
                            <p class="text-muted text-center">Profesional a cargo</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- box -->
                    <div class="box box-info">
                        <div class="box-body box-profile" style="text-align:center">
                            <h3 class="profile-username text-center" style="font-size:19px">
                                {{ $proceso[0]->vigencia . '-' . $proceso[0]->idRadicado }}</h3>
                            <p class="text-muted text-center" style="font-size: 14px">
                                @if (count($numerosQuejas) > 0)
                                    Queja:
                                    @foreach ($numerosQuejas as $numeroQueja)
                                        <strong>{{ $numeroQueja . ' ' }}</strong>
                                    @endforeach
                                @endif
                            </p>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- # box -->

                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <div style="padding: 10px">
                                @if ($proceso[0]->activoProceso == 1)
                                    <label style="color:#0dce0d"><i
                                            class="fa fa-clock-o faa-flash animated faa-slow"></i> Proceso
                                        Activo</label>
                                @else
                                    <label style="color:#a9a9a9"><i
                                            class="fa fa-times-circle faa-flash animated faa-fast"
                                            style="color:#dd4b39"></i> Proceso Inactivo</label>
                                @endif
                            </div>
                            @if ($idEtapa != 14)
                                @if ($documentoAbogado == Session::get('documentoUsuario') || Session::get('perfilUsuario') == 2)
                                    @if ($proceso[0]->activoProceso == 1)
                                        <a href="{{ asset('/procesos/actuaciones/' . $proceso[0]->vigencia . '/' . $proceso[0]->idRadicado) }}"
                                            class="btn btn-success btn-block"><b>Trabajar en este proceso</b></a>
                                    @else
                                        <a href="javascript: void(0)" data-toggle="tooltip"
                                            title="Este proceso se encuentra inactivo. Contacte al administrador"
                                            class="btn btn-success btn-block" disabled><b>Trabajar en este proceso</b></a>
                                    @endif
                                @else
                                {{""; $nombreAbogado = Util::traerNombreAbogado($proceso[0]->vigencia, $proceso[0]->idRadicado);}}
                                    <a href="javascript: void(0)" data-toggle="tooltip"
                                        title="{{$nombreAbogado != "" ? 'Sólo '.$nombreAbogado.' tiene permiso para trabajar en este proceso' : 'No se encontró un abogado a cargo aún'}}"
                                        class="btn btn-success btn-block" style="cursor: not-allowed;" disabled><b><i class="fa fa-wrench"></i> Trabajar en
                                            este proceso</b>
                                    </a>
                                @endif
                            @endif                  
                            <br>
                            <a class="btn btn-app" onclick="portada('{{ $proceso[0]->vigencia }}', '{{ $proceso[0]->idRadicado }}');" style="padding:10px 5px 20px 5px">
                                <i class="fa fa-file-word-o" style="font-size:28px"></i> Generar Portada del Proceso
                            </a>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @if (count($numerosQuejas) > 0)
                        @foreach ($numerosQuejas as $numeroQueja)
                            <!--  box-->
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Queja {{ $numeroQueja }}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <!-- row -->
                                    <div class="row">
                                        <!-- col-md-5 -->
                                        <div class="col-md-5">
                                            <!-- presuntos responsables-->
                                            <div class="box box-danger">
                                                <div class="box-body">
                                                    {{ '';
                                                    $presuntos = Util::traerPresuntosResponsablesPorQueja($numeroQueja) }}
                                                    <strong><i class="fa fa-user margin-r-5"></i>
                                                        @if (count($presuntos) == 1)
                                                            Presunto Responsable
                                                        @else
                                                            Presuntos Responsables
                                                        @endif
                                                    </strong>
                                                    @if (count($presuntos) > 0)
                                                        <ul class="users-list clearfix">
                                                            @foreach ($presuntos as $presunto)
                                                                <li>
                                                                    {{ '';$nombre_fichero = '../public/img/fotos/' . $presunto->documentoPersona . '.jpg' }}
                                                                    @if (file_exists($nombre_fichero))
                                                                        <img src="{{ asset('img/fotos/' . $presunto->documentoPersona . '.jpg') }}"
                                                                            title="{{ $presunto->nombre }}"
                                                                            style="width:76px; max-height:80px;">
                                                                    @else
                                                                        @if (Util::traerGenero($presunto->documentoPersona) == 'Femenino')
                                                                            <img src="{{ asset('img/ella.png') }}"
                                                                                title="{{ $presunto->nombre }}"
                                                                                style="width:76px; max-height:80px;">
                                                                        @else
                                                                            <img src="{{ asset('img/el.png') }}"
                                                                                title="{{ $presunto->nombre }}"
                                                                                style="width:76px; max-height:80px;">
                                                                        @endif
                                                                    @endif
                                                                    <a class="users-list-name"
                                                                        href="javascript: void(0)">{{ $presunto->nombre }}</a>
                                                                    <span
                                                                        class="users-list-date">{{ $presunto->nombreDependencia }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <!-- /.users-list -->
                                                    @else
                                                        @if (Util::esPorDeterminar($numeroQueja) == 1)
                                                            <ul class="list-group list-group-unbordered">
                                                                <li class="list-group-item" style="padding:6px">
                                                                    <b>POR DETERMINAR</b>
                                                                    <br>
                                                                    <span
                                                                        style="color:#888787;font-size:0.95em;">Presunto
                                                                        Responsable</span>
                                                                </li>
                                                            </ul>
                                                        @else
                                                            <br>
                                                            <div class="alert alert-white alert-dismissible"
                                                                style="padding:4px; margin-top:20px; text-align: center;">
                                                                No se ha indicado si es por determinar
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <!-- # presuntos responsables-->
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                            <!-- # presuntos responsables-->
                                        </div>
                                        <!-- col-md-5 -->

                                        <!-- col-md-5 -->
                                        <div class="col-md-5">
                                            <!-- quejosos-->
                                            <div class="box box-success">
                                                <div class="box-body">
                                                    {{ '';
                                                    $quejosos = Util::traerQuejososPorQueja($numeroQueja) }}
                                                    <strong><i class="fa fa-user margin-r-5"></i>
                                                        @if (count($quejosos) == 1)
                                                            Quejoso
                                                        @else
                                                            Quejosos
                                                        @endif
                                                    </strong>
                                                    @if (count($quejosos) > 0)
                                                        <ul class="users-list clearfix">
                                                            @foreach ($quejosos as $quejoso)
                                                                <li>
                                                                    {{ '';$nombre_fichero = '../public/img/fotos/' . $quejoso->documentoPersona . '.jpg' }}
                                                                    @if (file_exists($nombre_fichero))
                                                                        <img src="{{ asset('img/fotos/' . $quejoso->documentoPersona . '.jpg') }}"
                                                                            title="{{ $quejoso->nombre }}"
                                                                            style="width:76px; max-height:80px;">
                                                                    @else
                                                                        @if (Util::traerGenero($quejoso->documentoPersona) == 'Femenino')
                                                                            <img src="{{ asset('img/ella.png') }}"
                                                                                title="{{ $quejoso->nombre }}"
                                                                                style="width:76px; max-height:80px;">
                                                                        @else
                                                                            <img src="{{ asset('img/el.png') }}"
                                                                                title="{{ $quejoso->nombre }}"
                                                                                style="width:76px; max-height:80px;">
                                                                        @endif
                                                                    @endif
                                                                    <a class="users-list-name"
                                                                        href="javascript: void(0)">{{ $quejoso->nombre }}</a>
                                                                    <span
                                                                        class="users-list-date">{{ $quejoso->documentoPersona }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <!-- /.users-list -->
                                                    @else
                                                        @if (Util::esAnonimo($numeroQueja) == 1)
                                                            <ul class="list-group list-group-unbordered">
                                                                <li class="list-group-item" style="padding:6px">
                                                                    <b>ANÓNIMO</b>
                                                                    <br>
                                                                    <span
                                                                        style="color:#888787;font-size:0.95em;">Quejoso</span>
                                                                </li>
                                                            </ul>
                                                        @else
                                                            <br>
                                                            <div class="alert alert-white alert-dismissible"
                                                                style="padding:4px; margin-top:20px; text-align: center;">
                                                                No se ha indicado si es anónimo
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                            <!-- # quejosos-->
                                        </div>
                                        <!-- col-md-5 -->

                                        <div class="col-md-2">
                                            <a class="btn btn-app" href="javascript: void(0)" onclick="caratula({{$numeroQueja}});">
                                                <i class="fa  fa-file-word-o"></i> Carátula
                                            </a>
                                        </div>
                                    </div>
                                    <!-- # row -->

                                    <!-- queja datos básicos -->
                                    {{ '';
                                    $queja = DB::table('queja')->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')->join('tiporecepcionqueja', 'queja.TipoRecepcionQueja_idTipoQueja', '=', 'tiporecepcionqueja.idTipoRecepcionQueja')->where('queja.idQueja', '=', $numeroQueja)->orderBy('queja.idQueja', 'desc')->get() }}

                                    <!-- box -->
                                    <div class="box">
                                        <div class="box-body no-padding">
                                            <table class="table table-condensed table-hover">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-calendar margin-r-5"></i> Origen</b>
                                                        </th>
                                                        <td>{{ $queja[0]->nombreOrigenQueja }}</td>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-calendar margin-r-5"></i>
                                                                Dependencia</b></th>
                                                        <td>{{ $queja[0]->nombreDependencia }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-calendar margin-r-5"></i> Fecha
                                                                Queja</b></th>
                                                        <td>{{ Util::formatearFechaCorta($queja[0]->fechaQueja) }}</td>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-calendar margin-r-5"></i> Fecha
                                                                Recepción</b></th>
                                                        <td>{{ Util::formatearFechaCorta($queja[0]->fechaRecepcionQueja) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-envelope margin-r-5"></i> Tipo
                                                                Recepción</b></th>
                                                        <td>{{ $queja[0]->descTipoRecepcionQueja }}</td>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-file-o margin-r-5"></i> Número de
                                                                Oficio</b></th>
                                                        <td>{{ $queja[0]->numeroOficio }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-calendar margin-r-5"></i>Fecha
                                                                Hechos</b></th>
                                                        <td>
                                                            @if ($proceso[0]->fechaHechos == null)
                                                                Desconocida
                                                            @else
                                                                {{ Util::formatearFechaCorta($proceso[0]->fechaHechos) }}
                                                            @endif
                                                        </td>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-map-marker margin-r-5"></i> Presunto
                                                                Lugar</b></th>
                                                        <td>{{ $queja[0]->presuntoLugar }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 150px"><b><i
                                                                    class="fa fa-file-text-o margin-r-5"></i> Presuntos
                                                                Hechos</b></th>
                                                        <td colspan="3">{{ $queja[0]->presuntosHechos }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- # box -->
                                </div>
                            </div>
                            <!-- # box-->
                            {{ '';$display = 'collapsed-box' }}
                        @endforeach
                    @else
                        <div class="alert alert-white alert-dismissible" style="margin:20px;">
                            <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                            No se encontraron quejas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- # row -->




        <!-- row -->
        <div class="row">
            <!-- # col-sm-12 -->
            <div class="col-md-12">
                <!-- nav-tabs-custom-->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Línea de Tiempo</a></li>
                        <li><a href="#tab_2" data-toggle="tab"
                                onclick="verExpediente({{$proceso[0]->vigencia.', '.$proceso[0]->idRadicado}});">Expediente Digital</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Bitácora</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="tab-pane active" id="timeline">
                                <!-- The timeline -->
                                <ul class="timeline timeline-inverse">
                                    @if (count($observaciones) > 0)
                                        {{ '';$i = 0 }}
                                        @foreach ($observaciones as $observacion)
                                            @if ($i == 0)
                                                <!-- Almacena la fecha inicial -->
                                                {{ '';$fechaEstado = $observacion->fechaObservacion }}
                                                <!-- timeline time label -->
                                                <li class="time-label">
                                                    <span class="bg-green">
                                                        <i class="fa fa-calendar"></i>
                                                        {{ Util::formatearFechaCorta($observacion->fechaObservacion) }}
                                                    </span>
                                                </li>
                                                <!-- /.timeline-label -->
                                            @else
                                                @if ($fechaEstado != $observacion->fechaObservacion)
                                                    <!-- Almacena la fecha inicial -->
                                                    {{ '';$fechaEstado = $observacion->fechaObservacion }}
                                                    <!-- timeline time label -->
                                                    <li class="time-label">
                                                        <span class="bg-green">
                                                            <i class="fa fa-calendar"></i>
                                                            {{ Util::formatearFechaCorta($observacion->fechaObservacion) }}
                                                        </span>
                                                    </li>
                                                    <!-- /.timeline-label -->
                                                @endif
                                            @endif

                                            {{ '';
                                            switch ($observacion->EstadoRadicado_idEstadoRadicado) {
                                                case '1':
                                                    $icono = '<i class="fa fa-hashtag bg-blue"></i>';
                                                    break;
                                                case '2':
                                                    $icono = '<i class="fa fa-clone bg-blue"></i>';
                                                    break;
                                                case '3':
                                                    $icono = '<i class="fa fa-archive bg-yellow"></i>';
                                                    break;
                                                case '4':
                                                    $icono = '<i class="fa fa-external-link bg-yellow"></i>';
                                                    break;
                                                case '5':
                                                    $icono = '<i class="fa fa-user-plus bg-blue"></i>';
                                                    break;
                                                case '6':
                                                    $icono = '<i class="fa fa-hashtag bg-blue"></i>';
                                                    break;
                                                case '7':
                                                    $icono = '<i class="fa fa-flag-o bg-yellow"></i>';
                                                    break;
                                                case '8':
                                                    $icono = '<i class="fa fa-flag-o bg-yellow"></i>';
                                                    break;
                                                case '13':
                                                    $icono = '<i class="fa fa-flag-checkered bg-yellow"></i>';
                                                    break;
                                                case '29':
                                                    $icono = '<i class="fa fa-comment-o bg-blue"></i>';
                                                    break;
                                                case '30':
                                                    $icono = '<i class="fa fa-commenting bg-blue"></i>';
                                                    break;
                                                case '32':
                                                    $icono = '<i class="fa fa-commenting bg-blue"></i>';
                                                    break;
                                                case '33':
                                                    $icono = '<i class="fa fa-comment bg-blue"></i>';
                                                    break;
                                                case '36':
                                                    $icono = '<i class="fa fa-user-times bg-blue"></i>';
                                                    break;
                                                case '41':
                                                    $icono = '<i class="fa fa-cloud-upload bg-blue"></i>';
                                                    break;
                                                case '46':
                                                    $icono = '<i class="fa fa-clone bg-blue"></i>';
                                                    break;
                                                case '49':
                                                    $icono = '<i class="fa fa-clone bg-blue"></i>';
                                                    break;
                                                case '50':
                                                    $icono = '<i class="fa fa-flag-o bg-yellow"></i>';
                                                    break;
                                                case '51':
                                                    $icono = '<i class="fa fa-flag-o bg-blue"></i>';
                                                    break;
                                                case '55':
                                                    $icono = '<i class="fa fa-flag-o bg-red"></i>';
                                                    break;
                                                case '63':
                                                    $icono = '<i class="fa fa-flag-checkered bg-green"></i>';
                                                    break;
                                                default:
                                                    $icono = '<i class="fa fa-comments bg-blue"></i>';
                                                    break;
                                            } }}
                                            <!-- timeline item -->
                                            <li>
                                                {{ $icono }}
                                                <div class="timeline-item">
                                                    <span class="time"><i class="fa fa-clock-o"></i>
                                                        {{ $observacion->horaObservacion }}</span>
                                                    <h3 class="timeline-header">{{ $observacion->descEstadoRadicado }}
                                                        <span>{{ $observacion->nombre }}</span>
                                                    </h3>
                                                    <div class="timeline-body">{{ $observacion->observacion }}</div>
                                                </div>
                                            </li>
                                            <!-- END timeline item -->
                                            {{ '';$i++ }}
                                        @endforeach
                                    @endif

                                    <li>
                                        <i class="fa fa-clock-o bg-gray"></i>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_2">
                            @if ($documentoAbogado == Session::get('documentoUsuario') || Session::get('perfilUsuario') == 2 || Session::get('perfilUsuario') == 5)
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row"
                                            style="background:#f0f0f0; border:1px dotted #c4c4c4; margin:0 0 10px 0; padding: 4px;">
                                            <div class="col-xs-2">
                                                <label class="pull-right" style="margin-top:10px;">Expediente sólo
                                                    de:</label>
                                            </div>
                                            <div class="col-xs-4" style="padding:0px;">
                                                {{ Form::select('etapas', ['0' => 'Todo el expediente..', '-1' => 'Sólo documentos externos..'] + $lista_etapas, 0, ['class' => 'form-control', 'id' => 'etapaExpediente', 'style' => 'width:100%;', 'tabindex' => '-1', 'aria-hidden' => 'true', 'onchange' => 'verExpediente('.$proceso[0]->vigencia.", ".$proceso[0]->idRadicado.')']) }}
                                            </div>
                                        </div>

                                        <div id="resultadoExpediente">
                                            <!-- CARGA AJAX -->
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-white alert-dismissible" style="margin:20px;">
                                    <h4><i class="icon fa fa-info"></i><b>Confidencial</b></h4>
                                    Este expediente es confidencial. Sólo el profesional responsable y el director
                                    administrativo lo pueden visualizar.
                                </div>
                            @endif
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_3">
                            <table id="tablaBitacora" class="table table-bordered table-hover table-striped"
                                style="font-size:0.9em;">
                                <thead>
                                    <tr>
                                        <th width="250">Novedad</th>
                                        <th>Observación</th>
                                        <th width="130">Fecha</th>
                                        <th width="200">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bitacoras as $bitacora)
                                        <tr>
                                            <td style="vertical-align:middle;">
                                                <b>{{ $bitacora->descEstadoRadicado }}</b></td>
                                            <td>{{ $bitacora->observacion }}</td>
                                            <td>{{ $bitacora->fechaObservacion . ' ' . $bitacora->horaObservacion }}</td>
                                            <td style="vertical-align:middle;"><b>{{ $bitacora->nombre }}</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- # nav-tabs-custom-->
            </div>
            <!-- # col-sm-9 -->
        </div>
        <!-- # row -->
    @else
        <!-- box -->
        <div class="box box-default">
            <div class="box-body" style="display: block;">
                <div class="alert alert-white alert-dismissible" style="margin:20px;">
                    <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                    Este proceso no está registrado en la base de datos.
                </div>
            </div>
        </div>
    @endif
</div>
