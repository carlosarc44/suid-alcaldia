<h4 style="font-weight: 500;margin: 10px;color:#23252b;"> <i class="fa fa-tags"></i> Proceso {{$vigencia."-".$idRadicado}}  <span style="text-decoration: underline;font-weight:600">{{$titulo}}</span></h4>
<br>
<ul class="timelineProgress" id="timelineProgress" style="margin: 0px !important">
    @if ($fase == 1)
        <li class="li complete">
            <div style="position:relative">
                <div class="timestamp" style="margin-top:31px">
                    <span class="date">
                        {{""; $fechaRadicacion = Util::traerFechaProceso($vigencia, $idRadicado)}}
                        @if ($fechaRadicacion != '')
                            <i class="fa fa-calendar"></i> {{date_format(date_create($fechaRadicacion), "d/m/Y")}}
                        @endif
                    </span>
                </div>
                <span class="check">
                    <i class="fa fa-check"></i>
                </span>
                <div class="status" style="height: 88px">
                    <h4>Radicación</h4>
                </div>
            </div>
        </li>
    @endif

    @if (count($etapasFaseLinea) > 0)
        @foreach ($etapasFaseLinea as $etapa)
            <li class="li {{$etapa->actual == 1 ? 'actual' : 'complete'}}">
                <div style="position:relative">
                    <div class="timestamp">
                        <span class="date">
                            @if ($etapa->fechaEtapa != '')
                                <i class="fa fa-calendar"></i>  {{date_format(date_create($etapa->fechaEtapa), "d/m/Y")}}
                            @endif
                        </span>
                    </div>
                    <span class="check">
                        <i class="fa {{$etapa->actual == 0 ? 'fa-check' : 'fa-clock-o faa-flash animated faa-slow'}}"></i>
                    </span>
                    <div class="status">
                        <h4>{{$etapa->nombreCorto}}</h4>
                    </div>
                    @if ($etapa->fechaEtapa != '')
                        <button class="btn btn-success btn-xs" onclick="modalCambiarFecha({{$etapa->idEtapa.', '.$fase.', '.$vigencia.', '.$idRadicado.', '.$actuacion}})" style="margin-bottom:5px;display: {{$actuacion == 1 ? "block" : "none"}}"><i class="fa fa-random"></i> Cambiar Fecha</button>
                    @endif
                </div>
            </li>
        @endforeach   
    @else
        <div class="alert alert-white alert-dismissible" style="margin-top: 20px">            
            <h5><i class="icon fa fa-info"></i><b>El proceso {{$vigencia."-".$idRadicado}} no ha pasado aún a {{$titulo}}</b></h5>            
        </div>
    @endif
</ul> 
<iframe src="{{asset('/procesos/diagrama/'.$vigencia.'/'.$idRadicado.'/'.$fase)}}" style="width:100%; height:350px;border:1px solid #ddd"></iframe>
<fieldset style="background:#e3e3e3; margin:4px 0; display: {{$actuacion == 1 ? "block" : "none"}}">
    <div class="row" style="padding-top:4px">
        <div class="col-sm-2">
            <label class="pull-right">Etapa Actual:</label>
        </div>
        <div class="col-sm-2">
            <strong>{{$etapaActual}}</strong>
        </div>
        <div class="col-sm-2">
            <label class="pull-right">Etapa Siguiente:</label>
        </div>
        <div class="col-sm-3">   
            {{ 
                Form::select('etapaSiguiente', ['' => 'Seleccione la siguiente etapa'] + $etapas_siguiente, 0, ['class' => 'form-control', 'id' => 'etapaSiguiente', 'style' => 'width:100%;']) 
            }}
        </div>
        <div class="col-sm-2">       
            <button type="button" style="font-weight:bold" class="btn btn-success btn-block" onclick="terminarEtapa({{$vigencia.', '.$idRadicado.', '.$idEtapa}})"><i class="fa fa-flag-checkered"></i> Confirmar Etapa Siguiente</button>								
        </div>
        </div>
</fieldset>

