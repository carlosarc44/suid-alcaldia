<?php
$perfilUsuario = Session::get('perfilUsuario');
$idUsuario = Session::get('idUsuario');
$documentoUsuario = Session::get('documentoUsuario');
$nombresUsuario = Session::get('nombresUsuario');
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="base_url" content="{{ URL::to('/') }}">
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @section('tituloPagina')SUID © :: Control Disciplinario@show
        </title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome-animation.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">

        <!-- daterange picker -->
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css?v=3') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('css/AdminLTE.css?v=5') }}">
        <link href="{{ asset('css/mini-gauge.css?v=2') }}" rel="stylesheet">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.css') }}">
        <link href="{{ asset('css/morris-0.4.3.min.css') }}" rel="stylesheet" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
        <!-- favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <style>
            .global {
                height: 150px;
                width: 300px;
                border: 1px solid #ddd;
                background: #fff;
                overflow-y: scroll;
            }
            .mensajes {
                height: auto;
            }
            .texto {
                padding:4px;
                background:#fff;
            }
        </style>
    @section('cabecera')
    @show
</head>

<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->

<body class="hold-transition skin-blue fixed sidebar-mini sidebar-collapse">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- HEADER -->
        <header class="main-header">
            <!-- Logo -->
            <a href="{{ asset('/inicio') }}" class="logo">

                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img src="{{ asset('img\SUID_transp2.png') }}" height="46"></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src="{{ asset('img\logoSuidWeb.png') }}" height="46"
                        id="logo"></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="javascript: void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="javascript: void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                {{ '';$nombre_fichero = '../public/img/fotos/' . $documentoUsuario . '.jpg' }}
                                @if (file_exists($nombre_fichero))
                                    <img src="{{ asset('img/fotos/' . $documentoUsuario . '.jpg') }}" class="user-image"
                                        title="{{ $nombresUsuario }}">
                                @else
                                    @if (Util::traerGenero($documentoUsuario) == 'Masculino')
                                        <img src="{{ asset('img/el.png') }}" class="user-image"
                                            title="{{ $nombresUsuario }}">
                                    @else
                                        <img src="{{ asset('img/ella.png') }}" class="user-image"
                                            title="{{ $nombresUsuario }}">
                                    @endif
                                @endif
                                <span class="hidden-xs">{{ explode(' ', $nombresUsuario)[0] }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    @if (file_exists($nombre_fichero))
                                        <img src="{{ asset('img/fotos/' . $documentoUsuario . '.jpg') }}"
                                            class="img-circle" title="{{ $nombresUsuario }}">
                                    @else
                                        @if (Util::traerGenero($documentoUsuario) == 'Masculino')
                                            <img src="{{ asset('img/el.png') }}" class="img-circle"
                                                title="{{ $nombresUsuario }}">
                                        @else
                                            <img src="{{ asset('img/ella.png') }}" class="img-circle"
                                                title="{{ $nombresUsuario }}">
                                        @endif
                                    @endif
                                    <p>
                                        {{ $nombresUsuario }}
                                        <small>{{ Util::traerNombrePerfil($perfilUsuario) }}</small>
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="javascript: void(0)" class="btn btn-info btn-flat"
                                            onclick="cambiarPassword()">Cambiar Clave</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ asset('shutdown') }}" class="btn btn-success btn-flat">Cerrar
                                            Sesión</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="javascript: void(0)" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- # HEADER -->

        <!-- LATERAL IZQUIERDO -->
        @yield('menuLateral')
        <!-- # LATERAL IZQUIERDO -->

        <!-- PRINCIPAL -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <!-- MIGA DE PAN -->
                @yield('migaPan')
                <!-- #MIGA DE PAN -->
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- CONTENIDO -->
                @yield('contenido')
                <!-- # CONTENIDO -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- # PRINCIPAL -->

        <!-- FOOTER -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0.0
            </div>
            <strong>SUID &copy; {{ date('Y') }}</strong> Uso exclusivo Control Disciplinario Interno.
        </footer>
        <!-- # FOOTER -->

        <!-- LATERAL DERECHO -->
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar control-sidebar-light">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Add the sidebar's background. This div must be placed
immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
        <!-- # LATERAL DERECHO -->

        <!-- MODAL -->
        <div class="modal fade in" id="modalAgregarQuejoso">
            <div class="modal-dialog" style="width:90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Gestionar Quejosos</h4>
                    </div>
                    <div class="modal-body">
                        <!-- ajax-agregarQuejoso -->
                        <div id="ajax-agregarQuejoso">
                            <!-- CONTENIDO AJAX -->
                        </div>
                        <!-- # ajax-agregarQuejoso -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- modalCambiarPassword -->
	<div class="modal fade in" id="modalCambiarPassword">
		<div class="modal-dialog sm" style="width:35%;">
			<div class="modal-content">    
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title"><i class="fa fa-key"></i> Cambiar Clave</h4>
				</div>

				<div class="modal-body">
					<fieldset>
						<div class="row">
							<div class="col-xs-12">

								<div class="form-group row" style="padding:10px;">
									<div class="row" style="padding-bottom:8px;">
										<label class="col-lg-12 control-label">Contraseña Actual:</label>
									</div>

									<div class="row" style="padding-bottom:8px;">
										<div class="col-lg-12">
											<input type="password" class="form-control" id="passwordAnterior" placeholder="Contraseña Actual" autofocus>
										</div>
									</div>

									<div class="row" style="padding-bottom:8px;">  
										<label class="col-lg-12 control-label">Contraseña Nueva:</label>
									</div>

									<div class="row" style="padding-bottom:8px;">    
										<div class="col-lg-12">
											<input type="password" autocomplete="off" class="form-control" id="passwordNuevo" placeholder="Contraseña Nueva">     
										</div>
									</div>

									<div class="row" style="padding-bottom:8px;">    
										<div class="col-lg-12">
											<input type="password" autocomplete="off" class="form-control" id="passwordNuevoR" placeholder="Repita Contraseña">     
										</div>
									</div>
								</div> 
							</div>
						</div>   
					</fieldset>                        
				</div> 

				<div class="modal-footer">
		      		<button type="button" class="btn btn-info btn-sm pull-right" onclick="validarCambiarPassword();"><i class="fa fa-external-link"></i> Cambiar</button>
		        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
		      	</div>

			</div>
		</div>
	</div>
	<!-- # modalCambiarPassword -->

        <div class="modal fade in" id="modalOficioGeneral">
            <div class="modal-dialog sm" style="width: 96%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Generar Oficio General</h4>
                    </div>
                    <div class="modal-body" style="background: #f0f0f0;">
                        <!-- resultadoOficioGeneral -->
                        <div id="resultadoOficioGeneral">
                            <!-- CARGA AJAX -->
                        </div>
                        <!-- # resultadoOficioGeneral -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- MODAL -->
        <div class="modal fade in" id="modalAgregarPresuntoResponsable">
            <div class="modal-dialog" style="width:90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Gestionar Presuntos Responsables</h4>
                    </div>
                    <div class="modal-body">
                        <!-- ajax-agregarPresuntoResponsable -->
                        <div id="ajax-agregarPresuntoResponsable">
                            <!-- CONTENIDO AJAX -->
                        </div>
                        <!-- # ajax-agregarPresuntoResponsable -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- MODAL -->
        <div class="modal fade in" id="modalAgregarPR">
            <div class="modal-dialog" style="width:90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Seleccionar Funcionario</h4>
                    </div>
                    <div class="modal-body">
                        <!-- resultadoAgregarPR -->
                        <div id="resultadoAgregarPR">
                            <!-- CONTENIDO AJAX -->
                        </div>
                        <!-- # resultadoAgregarPR -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- # MODAL -->

        <!-- MODAL -->
        <div class="modal fade in" id="modalVerQueja">
            <div class="modal-dialog" style="width:96%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Ver Queja</h4>
                    </div>
                    <div class="modal-body" style="background:#f0f0f0;">
                        <!-- ajax-verQueja -->
                        <div id="ajax-verQueja">
                            <!-- CONTENIDO AJAX -->
                        </div>
                        <!-- # ajax-verQueja -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- modalBuscarProceso -->
        <div class="modal fade in" id="modalBuscarProceso">
            <div class="modal-dialog sm" style="width:94%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title"><i class="fa fa-search"></i> Procesos</h4>
                    </div>

                    <div class="modal-body">
                        <fieldset>
                            {{ '';$vigenciaActual = date('Y') }}
                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="pull-right" style="margin-top:10px;">Vigencia:</label>
                                </div>
                                <div class="col-xs-1" style="padding:0px;">
                                    <select class="form-control" onchange="buscarProcesoVigencia(this.value);"
                                        style="width:110%;">
                                        <option value='{{ $vigenciaActual }}'>{{ $vigenciaActual }}</option>
                                        <?php
                                        for ($i = 2014; $i <= $vigenciaActual; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <!-- resultadoBuscarProceso -->
                                <div class="col-xs-12" id="resultadoBuscarProceso">
                                    <!-- CARGA AJAX -->
                                </div>
                                <!-- # resultadoBuscarProceso -->
                            </div>
                        </fieldset>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left"
                            data-dismiss="modal">Cancelar</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- # modalBuscarProceso -->
        <script type="text/javascript">
            /* PLAY SOUND FUNCTION */
            function playAudio(file) {
                if (file === 'alert')
                    document.getElementById('audio-alert').play();

                if (file === 'fail')
                    document.getElementById('audio-fail').play();
            }
            /* END PLAY SOUND FUNCTION */
        </script>
        <!-- START PRELOADS -->
        <audio id="audio-alert" src="{{ asset('audio/alert.mp3') }}" preload="auto"></audio>
        <audio id="audio-fail" src="{{ asset('audio/fail.mp3') }}" preload="auto"></audio>
        <!-- END PRELOADS -->

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>


        <!-- InputMask -->
        <script src="{{ asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
        <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
        <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
        <!-- date-range-picker -->
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
        <!-- bootstrap color picker -->
        <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
        <!-- bootstrap time picker -->
        <script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
        <!-- SlimScroll -->
        <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <!-- iCheck 1.0.1 -->
        <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('js/app.min.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('js/demo.js') }}"></script>
        <!-- ChartJS 1.0.1 -->
        <script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>

        <!-- ALERTIFY -->
        <script src="{{ asset('js/alertify.min.js') }}"></script>
        <!-- CSS -->
        <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}" />
        <!-- Bootstrap theme -->
        <link rel="stylesheet" href="{{ asset('css/alertify-bootstrap.min.css') }}" />
        <!-- # ALERTIFY -->
        <link rel="stylesheet" href="{{ asset('css/fileinput.css') }}">
        <!-- Morris.js charts -->
        <script src="{{ asset('js/raphael-min.js') }}"></script>
        <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>

        <link href="{{ asset('css/jquery.datepick.css') }}" rel="stylesheet">
        <script src="{{ asset('js/jquery.plugin.js') }}"></script>
        <script src="{{ asset('js/jquery.datepick.js') }}"></script>
        <!-- DataTables -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/natural.js') }}"></script>
        <!-- SCRIPTS FIN -->
        @yield('scriptsFin')
        <!-- # SCRIPTS FIN -->

        <!-- Socket io
        <script src="http://127.0.0.1:3000/socket.io/socket.io.js"></script>-->
        <script src="https://suid.manizales.gov.co/socket.io/socket.io.js"></script>
        <!-- #Socket io -->
        <script src="{{ asset('realtime/listens_realtime.js') }}"></script>
        <script src="{{asset('js/quejas/comun.js?v=').rand(1,1000)}}"></script>
</body>

</html>
