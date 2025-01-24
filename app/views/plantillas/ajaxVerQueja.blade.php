<fieldset>
	<div class="row">
		<div class="col-md-6">
			<!-- box -->
			<div class="box box-info">
				<div class="box-body box-profile">
					<h3 class="profile-username text-center">Queja {{$queja->idQueja}}</h3>

					<p class="text-muted text-center">Recibida {{$queja->descTipoRecepcionQueja}}</p>

					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b><i class="fa fa-arrow-right margin-r-5"></i> Recepción</b> <span class="pull-right">{{$queja->descTipoRecepcionQueja}}</span>
						</li>
						<li class="list-group-item">
							<b><i class="fa fa-calendar margin-r-5"></i> Fecha Queja</b> <span class="pull-right">{{$queja->fechaQueja}}</span>
						</li>
						<li class="list-group-item">
							<b><i class="fa fa-calendar margin-r-5"></i> Fecha Recepción</b> <span class="pull-right">{{$queja->fechaRecepcionQueja}}</span>
						</li>
						<li class="list-group-item">
							<b><i class="fa fa-file-o margin-r-5"></i> Número de Oficio</b> <span class="pull-right">{{$queja->numeroOficio}}</span>
						</li>
						<li class="list-group-item">
							<b><i class="fa fa-file-o margin-r-5"></i> Dependencia</b> <span class="pull-right">{{$queja->nombreDependencia}}</span>
						</li>
					</ul>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->

			<!-- About Me Box -->
			<div class="box box-info">
				<div class="box-body box-profile">
					<h3 class="profile-username text-center">Hechos</h3>
					<!-- /.box-body -->
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b><i class="fa fa-map-marker margin-r-5"></i> Presunto Lugar</b>
							<span class="pull-right">{{$queja->presuntoLugar}}</span>
						</li>
						<strong><i class="fa fa-file-text-o margin-r-5"></i> Presuntos Hechos</strong>
						<p>{{$queja->presuntosHechos}}</p>
					</ul>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
			
		<div class="col-md-6">
			<!-- PRESUNTOS RESPONSABLES -->
			<div class="box box-danger">
                <div class="box-header with-border">
                	@if(count($presuntos)==1)
						{{''; $textoFuncionario = "Presunto Responsable"; }}
					@else
						{{''; $textoFuncionario = "Presuntos Responsables"; }}
					@endif
                  	<h3 class="box-title">{{$textoFuncionario}}</h3>
                  	<div class="box-tools pull-right">
	                    <span class="label label-info">{{count($presuntos)." ".$textoFuncionario}}</span>
                    	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    	</button>
                  	</div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding ajax-listaPresuntosResponsables_3_{{$queja->idQueja}}">
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
						
						<div class="row">
							<div class="col-sm-6">
								<button type="button" class="btn btn-success btn-sm" style="margin:6px" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
							</div>
						</div>
					@else					
						@if ($queja->porDeterminar == 1)
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item" style="padding:6px">
									<b>POR DETERMINAR</b>
									<br>
									<span style="color:#888787;font-size:0.95em;">Presunto Responsable</span>
								</li>
							</ul>

							<div class="row">
								<div class="col-sm-6">
									<button type="button" style="margin:6px" class="btn btn-success btn-sm" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Presuntos Responsables</button>
								</div>
							</div>							
						@else
						<div class="row">
							<div class="col-sm-6">
								<button type="button" class="btn btn-danger btn-sm" style="margin:6px" onclick="agregarPresuntoResponsable('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Agregar Presunto Responsable</button>
							</div>
							<div class="col-sm-6">
								<button type="button" class="btn btn-default btn-sm" style="margin:6px" onclick="porDeterminar('{{$queja->idQueja}}');"><i class="fa fa-question-circle"></i> Por Determinar</button>
							</div>
						</div>
						@endif 
					@endif
                </div>
                <!-- /.box-body -->    
            </div>
            <!-- /.box-danger -->

            <!-- QUEJOSOS -->
            <div class="box box-success">
                <div class="box-header with-border">
                	@if(count($quejosos)==1)
						{{''; $textoQuejoso = "Quejoso"; }}
					@else
						{{''; $textoQuejoso = "Quejosos"; }}
					@endif
                  	<h3 class="box-title">{{$textoQuejoso}}</h3>
                  	<div class="box-tools pull-right">
	                    <span class="label label-info">{{count($quejosos)." ".$textoQuejoso}} 
							
						</span>
                    	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    	</button>
                  	</div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding ajax-listaQuejosos_3_{{$queja->idQueja}}">
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

						<div class="row">
							<div class="col-sm-6">
								<button type="button" class="btn btn-success btn-sm" style="margin:6px" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
							</div>
						</div>
					@else
						@if ($queja->anonimo == 1)
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item" style="padding:6px">
									<b>ANÓNIMO</b>
									<br>
									<span style="color:#888787;font-size:0.95em;">Quejoso</span>
								</li>
							</ul>

							<div class="row">
								<div class="col-sm-6">
									<button type="button" style="margin:6px" class="btn btn-success btn-sm" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Gestionar Quejosos</button>
								</div>
							</div>
						@else
							<div class="row">
								<div class="col-sm-6">
									<button type="button" class="btn btn-danger btn-sm" style="margin:6px" onclick="agregarQuejoso('{{$queja->idQueja}}');"><i class="fa fa-user"></i> Agregar Quejoso</button>
								</div>
								<div class="col-sm-6">
									<button type="button" class="btn btn-default btn-sm" style="margin:6px" onclick="anonimo('{{$queja->idQueja}}');"><i class="fa fa-user-times"></i> Quejoso Anónimo</button>	
								</div>
							</div>
						@endif
					@endif
                </div>
                <!-- /.box-body -->    
            </div>
            <!-- /.box-danger -->

            <div class="box">
	            <div class="box-header">
	              <h3 class="box-title">Opciones</h3>
	            </div>
	            <div class="box-body">
	              <a class="btn btn-app" href="javascript: void(0)" onclick="caratula({{$queja->idQueja}});">
	                <i class="fa  fa-file-word-o"></i> Carátula
	              </a>
	               <a href="javascript: void(0)" class="btn btn-app" onclick="editarQueja({{$queja->idQueja}}, {{$multiples}});">
	                <i class="fa fa-edit"></i> Editar
	              </a>
	              <a href="javascript: void(0)" class="btn btn-app" style="display: none">
	                <i class="fa fa-trash"></i> Eliminar
	              </a>
	              <a href="{{asset('quejas/remitirQueja/').'/'.$queja->idQueja}}" class="btn btn-app">
	                <i class="fa fa-repeat"></i> Remitir por competencia
	              </a>
	              <a href="{{asset('quejas/acumularQueja/').'/'.$queja->idQueja}}" class="btn btn-app">
	                <i class="fa fa-reply-all"></i> Acumular la queja <strong>{{$queja->idQueja}}</strong> a otro proceso
	              </a>
				  <br>
				  <span style="color:red; font-size:0.9em">Si la queja ya fue repartida (Tiene un número de proceso), no realice la acumulación de Queja a Proceso; solicite al Director que acumule Proceso a Proceso.</span>
	            </div>
	            <!-- /.box-body -->
	        </div>
		</div>
		<!-- /.col -->
	</div>
</fieldset>