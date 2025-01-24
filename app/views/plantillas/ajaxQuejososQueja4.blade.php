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
    <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarQuejoso('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
@else
    @if (Util::esAnonimo($idQueja) == 1)
        <div class="strong outdated">
            Anónimo
        </div>
        <div class="time">
            Quejoso
        </div>
        <br>
        <button type="button" class="btn btn-success btn-xs btn-block transparente" onclick="agregarQuejoso('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
    @else
        <button type="button" class="btn btn-danger btn-xs btn-block transparente" style="margin-top:6px" onclick="agregarQuejoso('{{$idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
        <br>
        <button type="button" class="btn btn-default btn-xs btn-block transparente" onclick="anonimo('{{$idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>												
    @endif
@endif