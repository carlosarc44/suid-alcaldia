
@if (count($proceso) > 0)
    {{''; $numerosQuejas = Util::traerQuejasProceso($proceso[0]->vigencia, $proceso[0]->idRadicado);}}

    <!-- row -->
    <div class="row" style="margin-top:10px; padding:12px;background:#e7eaef">
        <div class="col-md-4" id="ajax-widgetPrescripcion">
            {{Util::traerWidgetPrescripcion($proceso[0]->vigencia, $proceso[0]->idRadicado, 0)}}
        </div>	
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-info">
                        <div class="box-body box-profile">  
                            <h3 class="profile-username text-center">{{$proceso[0]->vigencia."-".$proceso[0]->idRadicado}}</h3>
                            <p class="text-muted text-center">
                                @if (count($numerosQuejas) > 0)
                                    Queja: 
                                    @foreach ($numerosQuejas as $numeroQueja)
                                        <strong>{{$numeroQueja." "}}</strong>
                                    @endforeach
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <h3 class="profile-username text-center" style="font-size:16px;">{{$proceso[0]->nombre}}</h3>
                            <p class="text-muted text-center">Profesional a cargo</p>
                        </div>
                    </div>

                    <div id="ajax-widgetProceso">   
                        {{Util::traerWidgetProceso($proceso[0]->vigencia, $proceso[0]->idRadicado)}}
                        <!-- small box -->
                        <div id="ajax-widgetFaltas">
                            {{Util::traerWidgetFaltas($proceso[0]->vigencia, $proceso[0]->idRadicado, 0)}}				
                        </div>
                    </div>
                </div>		
                <div class="col-md-6" style="background: #fff; padding:12px;border: 1px solid #ddd;border-radius: 4px;">
                    {{''; $numerosQuejas = Util::traerQuejasProceso($proceso[0]->vigencia, $proceso[0]->idRadicado); }}
                    @if (count($numerosQuejas) > 0)	
                        @foreach ($numerosQuejas as $numeroQueja)
                            {{"";   $presuntos = Util::traerPresuntosResponsablesPorQueja($numeroQueja);}}				
                            <strong><i class="fa fa-user margin-r-5"></i>                            
                                @if (count($presuntos) == 1)
                                    Presunto Responsable
                                @else
                                    Presuntos Responsables
                                @endif						 	
                                Queja {{$numeroQueja}}
                            </strong>
                            @if (count($presuntos) > 0)
                                <ul class="users-list clearfix">
                                    @foreach ($presuntos as $presunto)
                                        <li>
                                            {{''; $nombre_fichero = '../public/img/fotos/'.$presunto->documentoPersona.'.jpg' }}
                                            @if(file_exists($nombre_fichero))
                                                <img src="{{ asset('img/fotos/'.$presunto->documentoPersona.'.jpg')}}" title="{{ $presunto->nombre; }}" style="width:76px; max-height:80px;">
                                            @else			                           
                                                @if(Util::traerGenero($presunto->documentoPersona) == 'Femenino')
                                                    <img src="{{ asset('img/ella.png')}}" title="{{ $presunto->nombre; }}" style="width:76px; max-height:80px;">
                                                @else
                                                    <img src="{{ asset('img/el.png')}}" title="{{ $presunto->nombre; }}" style="width:76px; max-height:80px;">
                                                @endif
                                            @endif
                                        <a class="users-list-name" href="javascript: void(0)">{{$presunto->nombre}}</a>
                                        <span class="users-list-date">{{$presunto->nombreDependencia}}</span>
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
                                            <span style="color:#888787;font-size:0.95em;">Presunto Responsable</span>
                                        </li>
                                    </ul>
                                @else
                                    <br>
                                    <div class="alert alert-white alert-dismissible" style="padding:4px; margin-top:20px; text-align: center;">
                                        No se ha indicado si es por determinar
                                    </div>										
                                @endif 
                            @endif
                            <!-- # presuntos responsables-->
                        @endforeach
                    @endif
                </div>
            </div>
        </div>    
    </div>
    <!-- # row -->
    <br>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Plantillas de <u>{{Util::traerNombreEtapa($proceso[0]->vigencia, $proceso[0]->idRadicado, 1)}}</u>.  Proceso <span style="font-size: 1.5em"> {{$proceso[0]->vigencia."-".$proceso[0]->idRadicado}}</span></a></li>
        </ul>
        <div class="tab-content">
            <!-- Plantillas -->
            <div class="tab-pane my-tabs active" id="tab_1">	
                <div class="box-body table-responsive no-padding">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:100px;">
                                <div class="btn-group-vertical">
                                    @foreach ($tiposPlantillas as $tipoPlantilla)
                                        <button type="button" class="btn btn-default" onclick="cargarPlantillas('{{$tipoPlantilla->idTipoPlantilla}}', '{{$idEtapa}}', '{{$proceso[0]->vigencia}}', '{{$proceso[0]->idRadicado}}')" style="font-weight: 600">
                                            {{$tipoPlantilla->nombreTipoPlantilla}}
                                        </button>
                                    @endforeach
                                </div>
                            </td>								
                            <td style="border:1px solid #ddd; padding:8px;">							
                                <!-- resultadoPlantillas -->
                                <div id="resultadoPlantillas" style="height: 550px; overflow: scroll;">	
                                    <div id="textoPlantillas">
                                        <br>
                                        <h4>PLANTILLAS {{Util::traerNombreEtapa($proceso[0]->vigencia, $proceso[0]->idRadicado, 1)}}
                                        </h4>
                                        <img src="{{ asset('img/SUID_transp2.png')}}" class="desaturada">
                                        <br>
                                        <br>
                                        <span> <i class="fa fa-arrow-left"></i> Seleccione un tipo de plantilla de la izquierda</span>
                                    </div>
                                </div>
                                <!-- # resultadoPlantillas -->
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-white alert-dismissible" style="margin:20px;">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontró un proceso con el número ingresado.
    </div>
@endif