<ul class="bs-glyphicons">
	@if (count($plantillas) > 0)
		@foreach ($plantillas as $plantilla)		
		  	<a style="cursor:pointer;" onclick="plantilla('{{$plantilla->idPlantilla}}', '{{$plantilla->TipoPlantilla_idTipoPlantilla}}', '{{$vigencia}}', '{{$idRadicado}}');">
		      	<li>
		        	<img src="{{ asset('img/word-icon.png')}}" style="height: 64px;">
		        	<span class="glyphicon-class">
		        		{{$plantilla->nombrePlantilla}}
		        	</span>
		      	</li>
		    </a>         
		@endforeach
	@else
		<div class="alert alert-white alert-dismissible" style="margin:20px;">
            <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
            No se encontraron plantillas en esta categoría.
        </div>
    @endif
</ul>