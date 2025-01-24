@if (count($presuntosresponsables) > 0)
    <ul class="users-list clearfix">
        @foreach ($presuntosresponsables as $presuntoresponsable)
            <li>
                <a class="users-list-name" href="javascript: void(0)" onclick="verPresuntoResponsable('{{$presuntoresponsable->documentoPersona}}')">
                    {{''; $nombre_fichero = '../public/img/fotos/'.$presuntoresponsable->documentoPersona.'.jpg' }}
                    @if(file_exists($nombre_fichero))
                        <img src="{{ asset('img/fotos/'.$presuntoresponsable->documentoPersona.'.jpg')}}" title="{{ $presuntoresponsable->nombre; }}" style="width:76px; max-height:80px;">
                    @else			                           
                        @if(Util::traerGenero($presuntoresponsable->documentoPersona) == 'Femenino')
                            <img src="{{ asset('img/ella.png')}}" title="{{ $presuntoresponsable->nombre; }}" style="width:76px; max-height:80px;">
                        @else
                            <img src="{{ asset('img/el.png')}}" title="{{ $presuntoresponsable->nombre; }}" style="width:76px; max-height:80px;">
                        @endif
                    @endif
                </a>
                <a class="users-list-name" href="javascript: void(0)" onclick="verPresuntoResponsable('{{$presuntoresponsable->documentoPersona}}')">
                    {{$presuntoresponsable->nombre}}
                </a>
                <span class="users-list-date">{{$presuntoresponsable->documentoPersona}}</span>
            </li>
        @endforeach
    </ul>			            
    <button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarPresuntoResponsable('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
@else
    @if (Util::esPorDeterminar($idQueja) == 1)
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item" style="padding:6px">
                <b>POR DETERMINAR</b>
                <br>
                <span style="color:#888787;font-size:0.95em;">Presunto Responsable</span>
            </li>
        </ul>

        <button type="button" class="btn btn-success btn-xs btn-block" onclick="agregarPresuntoResponsable('{{$idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
    @else
        <button type="button" class="btn btn-danger btn-xs btn-block" style="margin-top:6px" onclick="agregarPresuntoResponsable('{{$idQueja}}');"><i class="fa fa-user"></i> Agregar Presunto Responsable</button>
        <br>
        <button type="button" class="btn btn-default btn-xs btn-block" onclick="porDeterminar('{{$idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>											
    @endif   
@endif