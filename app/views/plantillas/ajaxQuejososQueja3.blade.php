@if (count($quejosos) > 0)
    <ul class="users-list clearfix">
        @foreach ($quejosos as $quejoso)
            <li>
                {{''; $nombre_fichero = '../public/img/fotos/'.$quejoso->documentoPersona.'.jpg' }}
                @if(file_exists($nombre_fichero))
                    <img src="{{ asset('img/fotos/'.$quejoso->documentoPersona.'.jpg')}}" title="{{ $quejoso->nombre; }}" style="width:76px; max-height:80px;">
                @else			                           
                    @if(Util::traerGenero($quejoso->documentoPersona) == 'Femenino')
                        <img src="{{ asset('img/ella.png')}}" title="{{ $quejoso->nombre; }}" style="width:76px; max-height:80px;">
                    @else
                        <img src="{{ asset('img/el.png')}}" title="{{ $quejoso->nombre; }}" style="width:76px; max-height:80px;">
                    @endif
                @endif
                <a class="users-list-name" href="javascript: void(0)">{{$quejoso->nombre}}</a>
                <span class="users-list-date">{{$quejoso->documentoPersona}}</span>
            </li>
        @endforeach
    </ul>			            
    <button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarQuejoso('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
@else
    @if (Util::esAnonimo($idQueja) == 1)
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item" style="padding:6px">
                <b>ANÓNIMO</b>
                <br>
                <span style="color:#888787;font-size:0.95em;">Quejoso</span>
            </li>
        </ul>

        <button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarQuejoso('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
    @else
        <button type="button" class="btn btn-danger btn-xs btn-block" style="margin-top:6px" onclick="agregarQuejoso('{{$idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
        <br>
        <button type="button" class="btn btn-default btn-xs btn-block" onclick="anonimo('{{$idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>											
    @endif 

@endif