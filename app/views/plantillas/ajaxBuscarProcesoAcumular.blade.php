@if (count($proceso) > 0)
    @if ($tipo == 1)
        <input type="hidden" id="tipo1" value="{{$tipo.'_'.$proceso->vigencia.'-'.$proceso->idRadicado}}">    
    @else
        <input type="hidden" id="tipo2" value="{{$tipo.'_'.$proceso->vigencia.'-'.$proceso->idRadicado}}">    
    @endif
    <div class="box box-info">
        <div class="box-body box-profile" style="text-align:center">  
            <h3 class="profile-username text-center" style="font-size:19px">{{$proceso->vigencia."-".$proceso->idRadicado}}</h3>
            <p class="text-muted text-center" style="font-size: 14px">
                @if (count($numerosQuejas) > 0)
                    Queja: 
                    @foreach ($numerosQuejas as $numeroQueja)
                        <strong>{{$numeroQueja." "}}</strong>
                    @endforeach
                @endif
            </p>						
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box box-primary">
        <div class="box-body box-profile">
            <h3 class="profile-username text-center" style="font-size:16px;">{{$proceso->nombre}}</h3>
            <p class="text-muted text-center">Profesional a cargo</p>
        </div>
    </div>
    {{Util::traerWidgetProceso($proceso->vigencia, $proceso->idRadicado)}}
@else
    <div class="box box-default">
        <div class="box-body" style="display: block;">
            <div class="alert alert-white alert-dismissible" style="margin:20px;">
                <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
                Este proceso no está registrado en la base de datos.
            </div>
        </div>
    </div>
@endif