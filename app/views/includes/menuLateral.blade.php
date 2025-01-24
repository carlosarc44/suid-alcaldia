{{'';
  	$perfilUsuario = Session::get('perfilUsuario');
  	$idUsuario = Session::get('idUsuario');  
  	$documentoUsuario = Session::get('documentoUsuario');
  	$nombresUsuario = Session::get('nombresUsuario');

	//
	$quejasRepartidas = DB::table('queja')
				->leftJoin('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				->leftJoin('abogadoasignado', function($join)
					{
						$join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
								->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
								->where('abogadoasignado.actual', '=', 'SI');
					})
				->where(DB::raw('substr(abogadoasignado.fechaAsignacion, -10, 4)'), '=', date('Y'))
				->get();
	
	$repartidas = count($quejasRepartidas);				

	//Quejas para enviar
	$quejasEnviar = DB::table('queja')
						->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
						->where('queja.EstadoQueja_idEstadoQueja', '=', 1)//1 Queja radicada
						->orderBy('queja.idQueja', 'desc')
						->get();

	$enviar = count($quejasEnviar);

	//Todas las Quejas
	$quejasTodas = DB::table('observacionesqueja')
					 ->where('observacionesqueja.EstadoQueja_idEstadoQueja', '=', 1)//1 Queja Radicada
					 ->get();

	$todas = count($quejasTodas);

	//Quejas para reparto
	$quejasReparto = DB::table('queja')
					->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->where('queja.EstadoQueja_idEstadoQueja', '=', 5)//5 Queja enviada a reparto
					->orderBy('queja.idQueja', 'desc')
					->get();

	$reparto = count($quejasReparto);
}}
<!-- LATERAL IZQUIERDO -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				{{''; $nombre_fichero = '../public/img/fotos/'.$documentoUsuario.'.jpg' }}
                @if(file_exists($nombre_fichero))
                    <img src="{{ asset('img/fotos/'.$documentoUsuario.'.jpg')}}" class="img-round" title="{{ $nombresUsuario; }}">
                @else 
                    @if(Util::traerGenero($documentoUsuario) == 'Masculino')                              
                        <img src="{{ asset('img/el.png')}}" class="img-round" title="{{ $nombresUsuario; }}">
                    @else
                        <img src="{{ asset('img/ella.png')}}" class="img-round" title="{{ $nombresUsuario; }}">
                    @endif
                @endif 
			</div>
			<div class="pull-left info">
				<p>{{ explode(" ", $nombresUsuario)[0]; }}</p>
				<a href="javascript: void(0)"><i class="fa fa-circle" style="color:#23da3c;"></i> En línea</a>
			</div>
		</div>
		
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li class="{{ ($menuActivo == "inicio") ? 'active' : '' }}" style="display:none">
				<a href="{{asset('/inicio')}}">
					<i class="fa fa-home"></i> <span>Inicio</span>
				</a>
			</li>

			@if($perfilUsuario != 3)						
				<li class="{{ ($menuActivo == "activos") ? 'active' : '' }}">
					<a href="{{asset('/procesos/activos')}}">
						<i class="fa fa-flag-o"></i>
						<span>Mis Procesos Activos <span class="pull-right" style="color:#9dff00;font-size:0.82em; margin-right:6px">Nuevo!</span> </span></span>
					</a>
				</li>				
				<li class="{{ ($menuActivo == "valoracion") ? 'active' : '' }}">
					<a href="{{asset('/procesos/valorar')}}">
						<i class="fa fa-random"></i> <span>Mis Procesos para valorar</span> 
						<span class="pull-right-container">
							<small class="label pull-right bg-orange"></small>
						</span>
					</a>
				</li>
				<li class="{{ ($menuActivo == "finalizados") ? 'active' : '' }}" style="display: none">
					<a href="{{URL::to('procesos/finalizados')}}">
						<i class="fa fa-flag-checkered"></i>
						<span>Mis Procesos Finalizados</span>
					</a>				
				</li>
			@endif

			<li class="treeview {{ ($menuActivo == "reportes") ? 'active' : '' }}">
				<a href="{{asset('/procesos/reportes')}}">
					<i class="fa fa-bars"></i> <span>Reportes</span>
				</a>
			</li>

			<li class="treeview {{ ($menuActivo == "buscar") ? 'active' : '' }}">
				<a href="{{asset('/procesos/buscar')}}">
					<i class="fa fa-search"></i> <span>Buscar Proceso</span>
				</a>
			</li>			

			<li class="treeview">
				<a href="{{asset('/procesos/plantillas')}}">
					<i class="fa fa-file-word-o"></i> <span>Plantillas Actuaciones
				</a>
			</li>

			<!--
			if (menuActivo == "agenda") 
				<li class="treeview active">
			else
				<li class="treeview">
			endif
				<a href="asset('/agenda')">
					<i class="fa fa-calendar-check-o"></i> <span>Agenda</span>
				</a>
			</li>
			-->

			@if(3 == 2)	
				<li class="treeview {{ ($menuActivo == "cuadro") ? 'active' : '' }}">				
					<a href="{{asset('/procesos/cuadro-control')}}">
						<i class="fa fa-table"></i> <span>Cuadro de Control</span>
					</a>
				</li>
			@endif

			<!-- DIRECTOR -->
			@if (Util::verificarPermiso(25, Session::get('perfilUsuario'))) {{-- 25 Opciones Director CDI --}}
				<li class="treeview {{$menuActivo == 'director' ? 'active' : ''}}">
					<a href="javascript: void(0)">
						<i class="fa fa-lock"></i> <span>Director</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">		
						<li class="{{ ($menuActivo == "activos") ? 'active' : '' }}">
							<a href="{{asset('/quejas/remisiones-por-competencia')}}">
								<i class="fa fa-undo"></i>
								<span>Remisiones por comp. <span class="pull-right" style="color:#9dff00;font-size:0.82em; margin-right:6px">Nuevo!</span> </span></span>
							</a>
						</li>								
						<li>
							<a href="{{asset('/quejas/estados-quejas')}}">
								<i class="fa fa-tachometer"></i>
								<span>Estado de Quejas</span> <span class="pull-right" style="color:#9dff00;font-size:0.82em; margin-right:6px">Nuevo!</span>
							</a>
						</li>
						<li>
							<a href="{{asset('/procesos/graficas')}}">
								<i class="fa fa-bar-chart"></i>
								<span>Gráficos</span>
							</a>
						</li>
						<li>
							<a href="{{asset('/quejas/quejasReparto')}}">
								<i class="fa fa-circle-o"></i>
								<span>Quejas para Reparto</span>
								<span class="pull-right-container">
									<small class="label pull-right bg-orange">{{$reparto}}</small>
								</span>
							</a>
						</li>
						<li>
							<a href="{{asset('/quejas/quejasTodas')}}">
								<i class="fa fa-circle-o"></i>
								<span>Todas las Quejas</span>
								<span class="pull-right-container">
									<small class="label pull-right bg-green">{{$todas}}</small>
								</span>
							</a>
						</li>
						<li style="display: none">
							<a href="{{asset('/quejas/quejasConProceso')}}">
								<i class="fa fa-circle-o"></i>
								<span>Quejas Repartidas</span>
								<span class="pull-right-container">
									<small class="label pull-right bg-green">{{$repartidas}}</small>
								</span>
							</a>
						</li>
						<li>
							<a href="{{asset('/procesos/autos')}}"><i class="fa fa-circle-o"></i> Números de Auto</a>
						</li>
						<li>
							<a href="{{asset('/procesos/reportes-autos')}}"><i class="fa fa-circle-o"></i> Reportes Autos</a>
						</li>
						<li>
							<a href="{{asset('/procesos/traslados')}}"><i class="fa fa-circle-o"></i> Trasladar Procesos</a>
						</li>
						<li>
							<a href="{{asset('/procesos/acumular-proceso-a-proceso')}}"><i class="fa fa-circle-o"></i> Acumular Proceso a Proceso</a>
						</li>
					</ul>
				</li>
			@endif
			<!-- # DIRECTOR -->

			<!-- LÍDER FASE DE JUZGAMIENTO -->
			@if (Util::verificarPermiso(26, Session::get('perfilUsuario'))) {{-- 26 Opciones Líder Juzgamiento --}}
				<li class="treeview {{$menuActivo == 'liderJuzgamiento' ? 'active' : ''}}">
					<a href="javascript: void(0)">
						<i class="fa fa-gavel"></i> <span>Líder Juzgamiento</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">	
						@if (Util::verificarPermiso(24, Session::get('perfilUsuario'))) {{-- 24 Juzgamiento - Realizar reparto --}}
							<li class="treeview">
								<a href="{{asset('/procesos/reparto-juzgamiento')}}">
									<i class="fa fa-envelope"></i> <span>Reparto Juzgamiento</span>
								</a>
							</li>
						@endif							
						<li>
							<a href="{{asset('/procesos/autos')}}"><i class="fa fa-circle-o"></i> Números de Auto</a>
						</li>
					</ul>
				</li>
			@endif
			<!-- # LÍDER FASE DE JUZGAMIENTO -->

			@if($perfilUsuario == 3 || $perfilUsuario == 2)	
				<!-- QUEJAS -->
				<li class="treeview {{ ($menuActivo == "quejas") ? 'active' : '' }}">
					<a href="javascript: void(0)">
						<i class="fa fa-commenting"></i> <span>Quejas</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="{{asset('/quejas/radicarQueja')}}"><i class="fa fa-circle-o"></i> Radicar Queja o Informe</a></li>
						<li>
							<a href="{{asset('/quejas/quejasEnviar')}}">
								<i class="fa fa-circle-o"></i>
								<span>Quejas para Enviar</span>
								<span class="pull-right-container">
									<small class="label pull-right bg-orange">{{$enviar}}</small>
								</span>
							</a>
						</li>
						<li>
							<a href="{{asset('/quejas/quejasTodas')}}">
								<i class="fa fa-circle-o"></i>
								<span>Todas las Quejas</span>
								<span class="pull-right-container">
									<small class="label pull-right bg-green">{{$todas}}</small>
								</span>
							</a>
						</li>
						<li>
							<a href="{{asset('/quejas/quejasConProceso')}}">
								<i class="fa fa-circle-o"></i>
								<span>Quejas Repartidas</span>
								<span class="pull-right-container">
									<small class="label pull-right bg-green">{{$repartidas}}</small>
								</span>
							</a>
						</li>
					</ul>
				</li>
				<!-- #QUEJAS -->
				
				<li class="{{ ($menuActivo == "subir") ? 'active' : '' }}">
					<a href="{{asset('/procesos/subir')}}">
						<i class="fa fa-cloud-upload"></i> <span>Subir archivos externos</span>
						<span class="pull-right-container">
							<small class="label pull-right bg-orange"></small>
						</span>
					</a>
				</li>

				<li class="{{ ($menuActivo == "subirquejas") ? 'active' : '' }}">
					<a href="{{asset('/procesos/subir-quejas')}}">
						<i class="fa fa-cloud-upload"></i> <span>Subir archivos quejas</span>
						<span class="pull-right-container">
							<small class="label pull-right bg-orange"></small>
						</span>
					</a>
				</li>
			@endif
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
<!-- #  LATERAL IZQUIERDO -->