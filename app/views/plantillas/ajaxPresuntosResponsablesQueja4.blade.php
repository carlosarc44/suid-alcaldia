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
    <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarPresuntoResponsable('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Pre. Resp.</button>
@else
    @if (Util::esPorDeterminar($idQueja) == 1)
        <div class="strong outdated">
            Por Determinar
        </div>
        <div class="time">
            Presunto Responsable
        </div>
        <br>
        <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarPresuntoResponsable('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Pre. Resp.</button>
    @else
        <button type="button" class="btn btn-danger btn-xs btn-block transparente" style="margin-top:6px" onclick="agregarPresuntoResponsable('{{$idQueja}}');"><i class="fa fa-user"></i> Agregar Pre. Resp.</button>
        <br>
        <button type="button" class="btn btn-default btn-xs btn-block transparente" onclick="porDeterminar('{{$idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>
    @endif
@endif