@if (count($quejosos) > 0)
    <ul class="users-list clearfix">
        @foreach ($quejosos as $quejoso)
            <li>
                <a class="users-list-name" href="javascript: void(0)" onclick="verQuejoso('{{$quejoso->documentoPersona}}')">
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
                </a>
                @if ($fijarOficio == 1)
                    <a class="users-list-name" href="javascript: void(0)" onclick="fijarDestinatario('{{ $quejoso->documentoPersona }}')">{{$quejoso->nombre}}</a>
                @else
                    <a class="users-list-name" href="javascript: void(0)" onclick="verQuejoso('{{$quejoso->documentoPersona}}')">{{$quejoso->nombre}}</a>                    
                @endif
                <span class="users-list-date">{{$quejoso->documentoPersona}}</span>
                <span class="users-list-date">{{$quejoso->direccionCorrespondencia}}</span>
				<button type="button" onclick="editarPersona('{{$quejoso->documentoPersona}}', '{{$idQueja}}');" class="btn btn-block btn-default btn-xs pull-right" style="margin-top:7px;"><i class="fa fa-edit"></i> Editar</button>
            </li>
        @endforeach
    </ul>			            
@else
    <div class="alert alert-white alert-dismissible" style="margin:10px;">
        La queja no tiene quejosos agregados
    </div>
@endif