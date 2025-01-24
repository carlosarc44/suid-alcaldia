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
                
                @if ($fijarOficio == 1)
                    <a class="users-list-name" href="javascript: void(0)" onclick="fijarDestinatario('{{ $presuntoresponsable->documentoPersona }}')">{{$presuntoresponsable->nombre}}</a>
                @else
                    <a class="users-list-name" href="javascript: void(0)" onclick="verPresuntoResponsable('{{$presuntoresponsable->documentoPersona}}')">{{$presuntoresponsable->nombre}}</a>
                @endif

                <span class="users-list-date">{{$presuntoresponsable->nombreDependencia}}</span>
                <span class="users-list-date">{{$presuntoresponsable->direccionCorrespondencia}}</span>
                <button type="button" onclick="editarPersona('{{$presuntoresponsable->documentoPersona}}', '{{$idQueja}}');" class="btn btn-block btn-default btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-edit"></i> Editar</button>
            </li>
        @endforeach
    </ul>			            
@else
    <div class="alert alert-white alert-dismissible" style="margin:10px;">
        La queja no tiene presuntos responsables agregados
    </div>
@endif