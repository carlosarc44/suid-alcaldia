<?php
date_default_timezone_set('America/Bogota');
ini_set("session.cookie_lifetime","14400");
ini_set("session.gc_maxlifetime","14400");
setlocale(LC_ALL,"es_ES");
//ini_set('memory_limit', '2048M');
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/moment', function()
{
	//https://github.com/fightbulc/moment.php
	$m = new \Moment\Moment('now', 'America/Bogota'); // default is "now" UTC
	\Moment\Moment::setLocale('es_ES');

	echo $m->format(); // e.g. 2012-10-03T10:00:00+0000
	echo "<br>";
	echo $m->format('l'); // e.g. Weekday: Wednesday
	echo "<br>";
	echo $m->format('l, dS F Y / H:i (e)'); // Wednesday, 25th April 2012 / 03:00 (Europe/Berlin)
	echo "<br>";
	echo $m->format('LLLL', new \Moment\CustomFormats\MomentJs()); // Wednesday, April 25th 2012 3:00 AM
	echo "<br>";
	echo $m->addHours(2)->format(); // 2012-05-15T14:30:00+0200


	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";

	$m = new \Moment\Moment('2013-02-01T07:00:00');
	$momentFromVo = $m->fromNow();


	// or from a specific moment
	$m = new \Moment\Moment('2020-07-31T07:00:00');
	$momentFromVo = $m->from('2020-07-29T07:00:00');

	// result comes as a value object class
	echo $momentFromVo->getDirection()."<br>";  // "future"
	echo $momentFromVo->getSeconds()."<br>";    // -42411600
	echo $momentFromVo->getMinutes()."<br>";    // -706860
	echo $momentFromVo->getHours()."<br>";      // -11781
	echo $momentFromVo->getDays()."<br>";       // -490.88
	echo $momentFromVo->getWeeks()."<br>";      // -70.13
	echo $momentFromVo->getMonths()."<br>";     // -17.53
	echo $momentFromVo->getYears()."<br>";      // -1.42
	echo $momentFromVo->getRelative()."<br>";   // in a year

	echo (new \Moment\Moment('2020-07-31T16:58:00', 'CET'))->addDays(1)->calendar(); // tomorrow


});


App::missing(function($exception)
{
  //Almacena la sesión temporal para mostrar el mensaje
  Session::flash('messageURL','No es posible acceder. Hay un error en la URL a la que intenta acceder.');
  //Retorna la vista denegado     
  return Response::view('includes.urlError');
});

Route::get('/', function()
{
	return View::make('login');
});


Route::get('/prueba', function()
{
    Artisan::call('enviar:correos');
});
	
Route::get('/inicio', 'ProcesoController@actionProcesosActivos');
Route::get('/inicio_old', ['before' => 'auth', function()
{
	$idUsuario = Session::get('documentoUsuario');
	$fechaHoy = date("Y-m-d");
	
	$tareas = Tarea::where('Persona_documentoPersona', '=', $idUsuario)
	->where(DB::raw('substr(fechaInicioTarea, -19, 10)'), '=', $fechaHoy)
	->orderBy('fechaInicioTarea', 'asc')
	->get();
	
	$cumplidas = Tarea::where('Persona_documentoPersona', '=', $idUsuario)
	->where(DB::raw('substr(fechaInicioTarea, -19, 10)'), '=', $fechaHoy)
	->where('finalizadaTarea', '=', 1)
	->get();
	
	if(count($cumplidas) > 0) 
	{
		$porcentaje = (count($cumplidas) / count($tareas)) * 100;
	}
	else
	{
		$porcentaje = 0;
	}
	
	$autos = DB::table('solicitudauto')
	->join('etapa', 'solicitudauto.Etapa_idEtapa', '=', 'etapa.idEtapa')
	->join('persona', 'solicitudauto.Persona_documentoPersona', '=', 'persona.documentoPersona')
	->where('solicitudauto.Persona_documentoPersona', '=', $idUsuario)
	->take(10)
	->orderBy('idSolicitudAuto', 'desc')
	->get();

	$autosAsig = DB::table('solicitudauto')
					->where('solicitudauto.Persona_documentoPersona', '=', $idUsuario)
					->where('solicitudauto.asignado', '=', 1)
					->take(10)
					->get();

	return View::make('inicio')
			   ->with('menuActivo', 'inicio')
	           ->with('tareas', $tareas)
	           ->with('autos', $autos)
	           ->with('porcentaje', $porcentaje)
	           ->with('cantAutos', count($autosAsig));
}]);

//LOGIN
Route::get('login', 'AuthController@showLogin'); // Mostrar login
Route::post('login', 'AuthController@postLogin'); // Verificar datos
Route::get('logout', 'AuthController@logOut'); // Finalizar sesión

// Quejas---
Route::group(array('before' => 'auth'), function()
{
	Route::get('/quejas/radicarQueja/', 'QuejaController@actionMostrarRadicarQueja');
	Route::get('/quejas/radicarInforme/', 'QuejaController@actionMostrarRadicarInforme');
	Route::post('/quejas/agregarQuejoso', 'QuejaController@actionAgregarQuejoso');
	Route::get('/quejas/showSelecInformante', 'QuejaController@actionMostrarAgregarInformante');
	Route::get('/quejas/showSelecPR', 'QuejaController@actionMostrarAgregarPR');
	Route::post('/quejas/guardarQueja', 'QuejaController@actionGuardarQueja');
	Route::post('/quejas/guardarInforme', 'QuejaController@actionGuardarInforme');
	Route::get('/quejas/quejasEnviar', 'QuejaController@actionQuejasEnviar');
	Route::post('/quejas/enviarSeleccionadas', 'QuejaController@actionEnviarSeleccionadas');
	Route::post('/quejas/verQueja', 'QuejaController@actionVerQueja');
	Route::get('/quejas/caratula/{idQueja}', 'QuejaController@actionCaratula');
	Route::post('/quejas/editarQueja', 'QuejaController@actionEditarQueja');
	Route::get('/quejas/acumularQueja/{idQueja}', 'QuejaController@actionAcumularQueja');
	Route::get('/quejas/remitirQueja/{idQueja}', 'QuejaController@actionRemitirQueja');
	Route::post('/quejas/guardarAcumularQueja', 'QuejaController@actionGuardarAcumularQueja');
	Route::get('/quejas/quejasReparto', 'QuejaController@actionQuejasReparto');
	Route::post('/quejas/guardarEditarQueja', 'QuejaController@actionEditarQueja');
	Route::post('/quejas/guardarEditarInforme', 'QuejaController@actionEditarInforme');
	Route::post('/quejas/guardarReparto', 'QuejaController@actionGuardarReparto');
	Route::get('/quejas/quejasConProceso/', 'QuejaController@actionMostrarConProceso');
	Route::get('/quejas/traerQuejasConProceso/{vigencia}', 'QuejaController@actionTraerQuejasConProceso');
	Route::get('/quejas/quejasTodas/', 'QuejaController@actionMostrarTodas');
	Route::get('/quejas/traerQuejasTodas/{vigencia}', 'QuejaController@actionTraerQuejasTodas');
	Route::post('/quejas/misQuejas', 'QuejaController@actionMisQuejas');
	Route::post('/quejas/buscarDocQuejoso', 'QuejaController@actionBuscarDocQuejoso');
	Route::post('/quejas/seleccionadoQuejoso', 'QuejaController@actionSeleccionadoQuejoso');
	Route::post('/quejas/quejososQueja', 'QuejaController@actionQuejososQueja');
	Route::post('/quejas/verQuejoso', 'QuejaController@actionVerQuejoso');
	Route::post('/quejas/modificarQuejoso', 'QuejaController@actionModificarQuejoso');
	Route::post('/quejas/quitarQuejoso', 'QuejaController@actionQuitarQuejoso');
	Route::post('/quejas/nuevoQuejoso', 'QuejaController@actionNuevoQuejoso');
	Route::post('/quejas/agregarPresuntoResponsable', 'QuejaController@actionAgregarPresuntoResponsable');
	Route::post('/quejas/presuntosResponsablesQueja', 'QuejaController@actionPresuntosResponsablesQueja');
	Route::post('/quejas/buscarDocPresuntoResponsable', 'QuejaController@actionBuscarDocPresuntoResponsable');
	Route::post('/quejas/seleccionadoPresuntoResponsable', 'QuejaController@actionSeleccionadoPresuntoResponsable');
	Route::post('/quejas/verPresuntoResponsable', 'QuejaController@actionVerPresuntoResponsable');
	Route::post('/quejas/modificarPresuntoResponsable', 'QuejaController@actionModificarPresuntoResponsable');
	Route::post('/quejas/quitarPresuntoResponsable', 'QuejaController@actionQuitarPresuntoResponsable');
	Route::post('/quejas/nuevoPresuntoResponsable', 'QuejaController@actionNuevoPresuntoResponsable');
	Route::post('/quejas/validarEditarQueja', 'QuejaController@actionValidarEditarQueja');
	Route::post('/quejas/buscarDocPersona', 'QuejaController@actionBuscarDocPersona');
	Route::post('/quejas/anonimo', 'QuejaController@actionAnonimo');
	Route::post('/quejas/porDeterminar', 'QuejaController@actionPorDeterminar');	
	Route::post('/quejas/editarPersona', 'QuejaController@actionEditarPersona');
	Route::post('/quejas/modificarPersona', 'QuejaController@actionModificarPersona');
	Route::get('/quejas/estados-quejas', 'QuejaController@actionEstadosQuejas');
	Route::post('/quejas/consultar-estados-queja', 'QuejaController@actionConsultarEstadosQueja');
	Route::get('/quejas/excel-estados-queja/{fechaInicio}/{fechaFin}/{cadenaVectorEstados}', 'QuejaController@actionExcelEstadosQueja');	
	Route::get('/quejas/remisiones-por-competencia', 'QuejaController@actionRemisionesPorCompetencia');
	Route::post('/quejas/consultar-remisiones-competencia', 'QuejaController@actionConsultarRemisionesCompetencia');	
});
//# Quejas---

Route::get('/migrar-presuntos', function()
{
	$presuntos = DB::table('presuntoimplicado')
					->join('funcionarioEH', 'presuntoimplicado.FuncionarioEH_idFuncionarioEH', '=', 'funcionarioEH.idFuncionarioEH')
					->where('Funcionario_idFuncionario', '!=', 1)
						->get();

	foreach ($presuntos as $presunto) 
	{
		$presuntoResponsable = new PresuntoResponsable;
		$presuntoResponsable->Queja_idQueja = $presunto->Queja_idQueja;
		$presuntoResponsable->Funcionario_idFuncionario = $presunto->Funcionario_idFuncionario;
		$presuntoResponsable->save();
		echo "Func: ".$presunto->Queja_idQueja." ".$presunto->Funcionario_idFuncionario."<br>";
	}

});

Route::get('/migrar-entidades', function()
{
	$contador =0;
	$entidades = DB::table('entidad')
 					->get();

	foreach ($entidades as $entidad) 
	{
		$contador++;

		$persona = new Persona;
		$persona->documentoPersona = $entidad->documentoEntidad;
		$persona->tipoDocumentoPersona = 'NIT';
		$persona->nombre = strtoupper($entidad->nombreEntidad);
		$persona->ciudadResidencia = 339;
		$persona->ciudadCorrespondencia = 339;
		$persona->direccionResidencia = $entidad->direccionEntidad;
		$persona->direccionCorrespondencia = $entidad->direccionEntidad;
		$persona->telefono = $entidad->telefonoEntidad;
		$persona->save();

		echo "Persona Entidad creada: ".$entidad->nombreEntidad." ".$contador."<br>";

		$informantes = DB::table('informante')
					->where('Entidad_idEntidad', $entidad->idEntidad)
					 ->get();
					 
		if (count($informantes) > 0) 
		{
			foreach ($informantes as $informante) 
			{
				$quejoso = new Quejoso;
				$quejoso->Queja_idQueja = $informante->Queja_idQueja;
				$quejoso->Persona_documentoPersona = $entidad->documentoEntidad;
				$quejoso->save();

				echo "Quejoso agregado: ".$entidad->nombreEntidad." ".$contador."<br>";
			}
		} 
		else 
		{
			echo "No se encontró la entidad ".$entidad->idEntidad;
		}
	}

});

Route::get('/migrar-funcionarios', function()
{

	$presuntos = DB::table('presuntoresponsable')
	->join('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
	->join('dependencia', 'funcionario.Dependencia_idDependencia', '=', 'dependencia.idDependencia')
	->join('cargo', 'funcionario.Cargo_idCargo', '=', 'cargo.idCargo')
	->join('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')

					->where('Funcionario_idFuncionario', '!=', 0)
					//->groupBy('Funcionario_idFuncionario')
						->get();

	foreach ($presuntos as $presunto) 
	{
		$funcionarios = DB::table('funcionarios')
						 ->where('Persona_documentoPersona', $presunto->documentoPersona)
						 ->first();


		if (count($funcionarios) == 0) 
		{
			/*
			$funcionario = new Funcionarios;
			$funcionario->Persona_documentoPersona = $presunto->documentoPersona;
			$funcionario->Cargo_idCargo = $presunto->Cargo_idCargo;
			$funcionario->Dependencia_idDependencia = $presunto->Dependencia_idDependencia;
			$funcionario->save();
			*/

			echo "NO EXISTE EN PERSONAS: ".$presunto->documentoPersona."<br>";

		}
		else {
			DB::table('presuntoresponsable')
			  ->where('Queja_idQueja', $presunto->Queja_idQueja)
				->where('Funcionario_idFuncionario', $presunto->Funcionario_idFuncionario)
				->update(['Funcionario_idFuncionario' => $funcionarios->idFuncionario]);
				
			echo "ANTES: ".$presunto->Funcionario_idFuncionario." DESPUES: ".$funcionarios->idFuncionario."<br>";
		}
	}

});

Route::get('/migrar-personas', function()
{
	$contador = 0;

	$personasNew = DB::table('personas')
					   ->get();

	foreach ($personasNew as $personaNew) 
	{
		$personasOld = DB::table('persona')
						 ->where('documentoPersona', $personaNew->identificacionPersona)
						 ->first();


		if (count($personasOld) == 0) 
		{
			$persona = new Persona;
			$persona->documentoPersona = $personaNew->identificacionPersona;
			$persona->tipoDocumentoPersona = 'CC';
			$persona->nombre = $personaNew->nombrePersona;
			$persona->ciudadResidencia = 339;
			$persona->ciudadCorrespondencia = 339;
			$persona->direccionResidencia = $personaNew->direccionPersona;
			$persona->direccionCorrespondencia = $personaNew->direccionPersona;
			$persona->telefono = $personaNew->telefonoPersona;
			$persona->telefono2 = $personaNew->celularPersona;
			$persona->email = $personaNew->correoPersona;
			$persona->save();

			echo "CREADO: ".$personaNew->identificacionPersona."<br>";
			$contador++;
		}
	}

	echo "CANTIDAD ALMACENADOS: ".$contador."<br>";
});


// Procesos
Route::group(array('before' => 'auth'), function()
{	
	Route::get('/procesos/valorar/', 'ProcesoController@actionValorar');
	Route::post('/procesos/guardarValoracion', 'ProcesoController@actionGuardarValoracion');
	Route::get('/procesos/activos', 'ProcesoController@actionProcesosActivos');
	Route::get('/procesos/finalizados', 'ProcesoController@actionProcesosFinalizados');
	Route::get('/procesos/ver/{vigencia}/{idRadicado}', 'ProcesoController@actionVerProceso');
	Route::get('/procesos/actuaciones/{vigencia}/{idRadicado}', 'ProcesoController@actionActuacionesProceso');
	Route::post('/procesos/cargarPlantillas', 'ProcesoController@actionCargarPlantillas');
	Route::get('/procesos/portada/{vigencia}/{idRadicado}', 'ProcesoController@actionPortada');
	Route::get('/procesos/plantilla/{vigencia}/{idRadicado}/{idPlantilla}/{idTipoPlantilla}', 'ProcesoController@actionPlantilla');
	Route::post('/procesos/solicitar-numero-auto', 'ProcesoController@actionSolicitarNumeroAuto');
	Route::post('/procesos/cargar-autos', 'ProcesoController@actionCargarAutos');	
	Route::get('/procesos/autos/', 'ProcesoController@actionAutos');
	Route::get('/procesos/reportes-autos/', 'ProcesoController@actionReportesAutos');
	Route::get('/procesos/traslados/', 'ProcesoController@actionTraslados');
	Route::post('/procesos/cargarProcesoAbogado/', 'ProcesoController@actionProcesosAbogado');
	Route::post('/procesos/trasladarProcesos/', 'ProcesoController@actionTrasladarProcesos');
	Route::post('/procesos/verificarNumero', 'ProcesoController@actionVerificarNumeroAuto');
	Route::post('/procesos/guardarAuto', 'ProcesoController@actionVerificarGuardarNumeroAuto');
	Route::post('/procesos/eliminarSolicitud', 'ProcesoController@actionEliminarSolicitud');
	Route::get('/procesos/cargarNumeracionx/', 'ProcesoController@actionAutos');
	Route::get('/procesos/cargarNumeracion/', 'ProcesoController@actionCargarNumeracion');
	Route::post('/procesos/historico-autos', 'ProcesoController@actionHistoricoAutos');
	Route::post('/procesos/guardarAutoAntes', 'ProcesoController@actionVerificarGuardarNumeroAutoAntes');
	Route::post('/procesos/guardarAutoEspecial', 'ProcesoController@actionVerificarGuardarNumeroAutoEspecial');
	Route::post('/procesos/cargarGenOficio', 'ProcesoController@actionCargarGenOficio');
	Route::post('/procesos/cargarOficioGeneral', 'ProcesoController@actionCargarOficioGeneral');
	Route::post('/procesos/cargarCiudad', 'ProcesoController@actionCargarCiudad');
	Route::post('/procesos/fijarDestinatario', 'ProcesoController@actionFijarDestinatario');
	Route::post('/procesos/fijarDestinatarioEnt', 'ProcesoController@actionFijarDestinatarioEntidad');
	Route::post('/procesos/fijarDestinatarioEntRem', 'ProcesoController@actionFijarDestinatarioEntidadRemision');
	Route::get('/procesos/guardarOficio/{vector}', 'ProcesoController@actionGuardarOficio');
	Route::get('/procesos/guardarOficioGeneral/{vector}', 'ProcesoController@actionGuardarOficioGeneral');
	Route::get('/procesos/guardarOficioRemision/{vector}', 'ProcesoController@actionGuardarOficioRemision');
	Route::post('/procesos/agregarArchivos', 'ProcesoController@actionAgregarArchivos');
	Route::post('/procesos/mostrarSeleccionarArchivo', 'ProcesoController@actionMostrarSeleccionarArchivo');
	Route::post('/procesos/subirArchivoExpediente', 'ProcesoController@actionSubirArchivoExpediente');
	Route::post('/procesos/verExpediente', 'ProcesoController@actionVerExpediente');
	Route::post('/procesos/verTareas', 'ProcesoController@actionVerTareas');
	Route::get('/procesos/verArchivo/{idArchivo}', 'ProcesoController@actionVerArchivo');
	Route::post('/procesos/agregarOtrosArchivos', 'ProcesoController@actionAgregarOtrosArchivos');
	Route::post('/procesos/subirOtroArchivoExpediente', 'ProcesoController@actionSubirOtroArchivoExpediente');
	Route::post('/procesos/borrarArchivoGenerado', 'ProcesoController@actionBorrarArchivoGenerado');
	Route::post('/procesos/programarTarea', 'ProcesoController@actionProgramarTarea');
	Route::post('/procesos/registrarNotificacion', 'ProcesoController@actionRegistrarNotificacion');
	Route::post('/procesos/cargarHoras', 'ProcesoController@actionCargarHoras');
	Route::post('/procesos/nuevaTarea', 'ProcesoController@actionNuevaTarea');
	Route::post('/procesos/guardarTarea', 'ProcesoController@actionGuardarTarea');
	Route::post('/procesos/cargarProcesosSelect', 'ProcesoController@actionProcesosSelect');
	Route::post('/procesos/mostrarBuscarProceso', 'ProcesoController@actionMostrarBuscarProceso');
	Route::post('/procesos/mostrarBuscarSelProceso', 'ProcesoController@actionMostrarBuscarSelProceso');
	Route::post('/procesos/mostrarTerminarEtapa', 'ProcesoController@actionMostrarTerminarEtapa');
	Route::post('/procesos/terminar-etapa', 'ProcesoController@actionTerminarEtapa');
	Route::post('/procesos/finalizados', 'ProcesoController@actionProcesosFinalizados');
	Route::get('/procesos/subir', 'ProcesoController@actionSubir');
	Route::get('/procesos/subir-quejas', 'ProcesoController@actionSubirQuejas');
	Route::post('/procesos/pruebaLoader', 'ProcesoController@actionPruebaLoader');
	Route::post('/procesos/cargarEtapasProceso', 'ProcesoController@actionCargarEtapasProceso');	
	Route::post('/procesos/subirArchivoExterno', 'ProcesoController@actionSubirArchivoExterno');
	Route::post('/procesos/subirArchivoQueja', 'ProcesoController@actionSubirArchivoQueja');
	Route::get('/procesos/cuadro-control', 'ProcesoController@actionCuadroControl');
	Route::get('/procesos/reportes', 'ProcesoController@actionReportes');
	Route::post('/procesos/ejecutar-reporte', 'ProcesoController@actionEjecutarReporte');
	Route::post('/procesos/ejecutar-reporte-autos', 'ProcesoController@actionEjecutarReporteAutos');
	Route::get('/procesos/reporte/excel/{cadenaVectorDependencias}/{cadenaVectorFaltas}/{estado}/{idAbogado}/{vigencia}/{cadenaVectorEtapas}', 'ProcesoController@actionReporteExcel');
	Route::get('/procesos/reporte-autos/excel/{cadenaVectorFaltas}/{estado}/{idAbogado}/{vigencia}/{vigenciaAuto}/{cadenaVectorEtapas}', 'ProcesoController@actionReporteExcelAutos');
	Route::get('/procesos/buscar', 'ProcesoController@actionBuscar');
	Route::post('/procesos/buscar-proceso', 'ProcesoController@actionBuscarProceso');	
	Route::post('/procesos/buscar-nombre-quejoso', 'ProcesoController@actionBuscarNombreQuejoso');	
	Route::post('/procesos/buscar-nombre-presunto', 'ProcesoController@actionBuscarNombrePresunto');
	Route::post('/procesos/buscar-palabra-clave', 'ProcesoController@actionBuscarPalabraClave');
	Route::post('/procesos/calcular-vencimientos', 'ProcesoController@actionCalcularVencimientos');	
	Route::post('/procesos/modal-cambiar-fecha', 'ProcesoController@actionModalCambiarFecha');
	Route::post('/procesos/modal-cambiar-faltas-comunes', 'ProcesoController@modal-cambiar-faltas-comunes');		
	Route::post('/procesos/cambiar-fecha', 'ProcesoController@actionCambiarFecha');	
	Route::post('/procesos/cargar-linea-tiempo', 'ProcesoController@actionCargarLineaTiempo');	
	Route::post('/procesos/cargar-widget-proceso', 'ProcesoController@actionCargarWidgetProceso');	
	Route::post('/procesos/modal-cambiar-fecha-hechos', 'ProcesoController@actionModalCambiarFechaHechos');
	Route::post('/procesos/modal-cambiar-faltas-comunes', 'ProcesoController@actionModalCambiarFaltasComunes');	
	Route::post('/procesos/cambiar-fecha-hechos', 'ProcesoController@actionCambiarFechaHechos');	
	Route::post('/procesos/cargar-widget-prescripcion', 'ProcesoController@actionCargarWidgetPrescripcion');
	Route::post('/procesos/cambiar-falta', 'ProcesoController@actionCambiarFalta');
	Route::post('/procesos/cargar-widget-falta', 'ProcesoController@actionCargarWidgetFalta');
	Route::get('/procesos/plantillas', 'ProcesoController@actionPlantillas');
	Route::post('/procesos/buscar-radicado-plantillas', 'ProcesoController@actionBuscarRadicadoPlantillas');
	Route::get('/procesos/acumular-proceso-a-proceso', 'ProcesoController@actionAcumularProcesoAProceso');
	Route::post('/procesos/buscar-proceso-acumular', 'ProcesoController@actionBuscarProcesoAcumular');
	Route::post('/procesos/acumular-proceso-proceso', 'ProcesoController@actionAcumularProcesoProceso');	
	Route::get('/procesos/graficas', 'ProcesoController@actionGraficas');
	Route::post('/procesos/ejecutar-reporte-graficas', 'ProcesoController@actionEjecutarReporteGraficas');
	Route::get('/procesos/diagrama/{vigencia}/{radicado}/{fase}', 'ProcesoController@actionDiagrama');	
	Route::post('/procesos/traer-fase', 'ProcesoController@actionTraerFase');	
	Route::get('/procesos/reparto-juzgamiento', 'ProcesoController@actionRepartoJuzgamiento');	
	Route::get('/procesos/cargar-reparto-juzgamiento', 'ProcesoController@actionCargarRepartoJuzgamiento');	
	Route::get('/procesos/cargar-abogados-reparto', 'ProcesoController@actionCargarAbogadosReparto');
	Route::post('/procesos/asignar-proceso', 'ProcesoController@actionAsignarProceso');
	Route::get('/procesos/cargar-procesos-activos-etapa', 'ProcesoController@actionCargarProcesosActivosEtapa');
	Route::post('/procesos/modal-remitir-por-competencia', 'ProcesoController@actionModalRemitirPorCompetencia');
	Route::get('/procesos/remitir-por-competencia/{vector}', 'ProcesoController@actionRemitirPorCompetencia');
	Route::get('/procesos/remisiones-por-competencia', 'ProcesoController@actionRemisionesPorCompetencia');
	
});

Route::get('/agenda', function()
{
  return View::make('agenda');
});

Route::get('/test-autos', function() {

	$vigenciaAuto = 2019;

	
	$quejas = DB::table('radicado')
				
					 ->join('auto', function($join) {
						$join->on('auto.Radicado_idRadicado', '=', 'radicado.idRadicado')
							 ->on('auto.Radicado_vigencia', '=', 'radicado.vigencia');
						  })

						->join('acumulaqueja', function($join) {
				$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
						->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				});
		
				
			$quejas->where('auto.vigenciaAuto', '=', $vigenciaAuto);
				

					//$quejas->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2);//2 Radicado acumulado

					$quejas->groupBy('acumularadicado.Radicado_idRadicado');
					$quejas->groupBy('acumulaqueja.Radicado_vigencia');	

			$result = $quejas->get();

			echo count($result);

});

Route::get('/test2-autos', function() {

	$vigenciaAuto = 2019;
	
	$autos = DB::table('auto')

	->leftJoin('radicado', function($join) {
		$join->on('auto.Radicado_idRadicado', '=', 'radicado.idRadicado')
			 ->on('auto.Radicado_vigencia', '=', 'radicado.vigencia');
		  })


	            ->where('auto.vigenciaAuto', '=', $vigenciaAuto)
				->orderBy('Radicado_idRadicado')
				->get();

				echo count($autos);
				echo "<br>";
				echo "<br>";

	foreach ($autos as $auto) {

		$queja = DB::table('acumulaqueja')
   				   ->where('acumulaqueja.Radicado_idRadicado', $auto->Radicado_idRadicado)
				   ->where('acumulaqueja.Radicado_vigencia', $auto->Radicado_vigencia)
				   ->first();

		if (count($queja) > 0) 
		{
			echo "Encontrado: ".$auto->Radicado_idRadicado."-".$auto->Radicado_vigencia." <br>";
		} 
		else 
		{
			echo "No Encontrado: ".$auto->Radicado_idRadicado."-".$auto->Radicado_vigencia." <br>";
		}
	}

				



});

//AGENDA
Route::group(array('before' => 'auth'), function()
{
	Route::post('/agenda/mostrarAgendarTarea', 'AgendaController@actionAgendarTarea');
	Route::post('/agenda/guardarAgendarTarea', 'AgendaController@actionGuardarAgendarTarea');
	Route::post('/agenda/editarAgendarTarea', 'AgendaController@actionEditarAgendarTarea');
	Route::post('/agenda/mostrarEditarTarea', 'AgendaController@actionMostrarEditarTarea');
	Route::post('/agenda/finalizarTarea', 'AgendaController@actionFinalizarTarea');
});


//USUARIOS
Route::group(array('before' => 'auth'), function()
{
	Route::post('/users/password', 'UsuarioController@actionPassword');
});

//Cierra la sesión del usuario
Route::get('shutdown', function()
{
  // ===== LOGS ===== //         
  $nombreUsuario = Session::get('nombresUsuario');//Obtiene el nombre del usuario
  $accion = 2;//2 Cierra sesión
  $descripcion = "El usuario: ".$nombreUsuario." cerró la sesión.";
  Util::almacenaLog($accion, $descripcion);
  // # LOGS ********* //

  Session::flush();
  Auth::logout();
  return View::make('login');
});

Route::get('google2', array('as' => 'google', 'uses' => 'HomeController@loginWithGoogle'));
Route::get('facebook', 'OauthController@loginWithFacebook');
Route::get('google', 'OauthController@loginWithGoogle');

Route::get('/recordar', function()
{
   Artisan::call('enviar:correos');
});

Route::get('/encriptar', function()
{
	$funcionarios = DB::table('usuario')
                  ->get();

    foreach ($funcionarios as $funcionario) 
    {
        //Actualiza los campos
        DB::table('usuario')
          ->where('idUsuario', $funcionario->idUsuario)
          ->update(['password' => Hash::make($funcionario->Persona_documentoPersona)]);

        echo $funcionario->idUsuario."<br>";
        echo Hash::make($funcionario->Persona_documentoPersona)."<br>";
    }

    //Hash::make($row->documento)
});

Route::get('/actualizaretapas', function()
{
	$etapasProcesos = DB::table('etapasproceso')
	->where('Radicado_vigencia', '>', 2016)
	->get();

    foreach ($etapasProcesos as $etapaProceso) 
    {
		//Busca la etapa
		$etapa = Etapa::find($etapaProceso->Etapa_idEtapa);
		
		//Almacena el plazo de la etapa
		$plazo = $etapa->plazoEtapa;
		//Almacena si es hábil el plazo
		$habil = $etapa->habil;
		
		$fechaFinalEtapa = Util::obtenerFechaFinalHabiles($etapaProceso->fechaEtapa, $plazo, $habil);
		
		if ($etapaProceso->fechaFinalEtapa != $fechaFinalEtapa) {
			echo $etapaProceso->Radicado_vigencia.'-'.$etapaProceso->Radicado_idRadicado.' ... '.$plazo." días ".$habil." a partir de ".$etapaProceso->fechaEtapa." = ".$fechaFinalEtapa." antes: ".$etapaProceso->fechaFinalEtapa."<br>";
		}
		else {
			echo "fecha correctamente calculada. <br>";
		}
		//Actualiza los campos
        DB::table('etapasproceso')
		  ->where('Radicado_idRadicado', $etapaProceso->Radicado_idRadicado)
		  ->where('Radicado_vigencia', $etapaProceso->Radicado_vigencia)
		  ->where('Etapa_idEtapa', $etapaProceso->Etapa_idEtapa)
          ->update(['fechaFinalEtapa' => $fechaFinalEtapa]); 
    }

    //Hash::make($row->documento)
});

Route::get('/eliminarfinalizado', function()
{
	$etapasProcesos = DB::table('etapasproceso')
	->where('Etapa_idEtapa', 14)
	->where('actual', 1)
	->get();

	$contador = 0;

    foreach ($etapasProcesos as $etapaProceso) 
    {
		$contador++;
		echo $contador."- ".$etapaProceso->Radicado_vigencia.'-'.$etapaProceso->Radicado_idRadicado." fecha: ".$etapaProceso->fechaEtapa."<br>";

		$etapas = DB::table('etapasproceso')
		->where('Radicado_vigencia', $etapaProceso->Radicado_vigencia)
		->where('Radicado_idRadicado', $etapaProceso->Radicado_idRadicado)
		->where('Etapa_idEtapa', '!=', 14)
		->orderBy('fechaEtapa', 'desc')
		->get();

		//ACtualiza a 1 la última etapa
		DB::table('etapasproceso')
		->where('Radicado_idRadicado', $etapas[0]->Radicado_idRadicado)
		->where('Radicado_vigencia', $etapas[0]->Radicado_vigencia)
		->where('Etapa_idEtapa', $etapas[0]->Etapa_idEtapa)
		->update(['actual' => 1]); 

		echo "Fecha: ".$etapas[0]->fechaEtapa.".  Etapa: ".$etapas[0]->Etapa_idEtapa."<br><br>"; 
	}
	
	//Borra las etapas con 14
	DB::table('etapasproceso')
	->where('Etapa_idEtapa', 14)
	->where('actual', 1)
	->delete();
});

Route::get('/sinactualenuno', function()
{
	$etapasProcesos = DB::table('etapasproceso')
	                      ->get();

	$contador = 0;

    foreach ($etapasProcesos as $etapaProceso) 
    {
		$contador++;
		
		echo $contador."- ";

		$etapas = DB::table('etapasproceso')
					->where('Radicado_vigencia', $etapaProceso->Radicado_vigencia)
					->where('Radicado_idRadicado', $etapaProceso->Radicado_idRadicado)
					->where('actual', 1)
					->first();

		if (count($etapas) > 0){
			echo "TIENE 1: ".$etapaProceso->Radicado_vigencia.'-'.$etapaProceso->Radicado_idRadicado." actual: ".$etapaProceso->actual."<br>";
		} 
		else 
		{
			echo "<br> NO TIENE 1: ".$etapaProceso->Radicado_vigencia.'-'.$etapaProceso->Radicado_idRadicado." actual: ".$etapaProceso->actual."<br>";

			$et = DB::table('etapasproceso')
						->where('Radicado_vigencia', $etapaProceso->Radicado_vigencia)
						->where('Radicado_idRadicado', $etapaProceso->Radicado_idRadicado)
						->where('Etapa_idEtapa', '!=', 14)
					  ->orderBy('fechaEtapa', 'desc')
						->first();

						/*
			//ACtualiza a 1 la última etapa
			DB::table('etapasproceso')
			->where('Radicado_idRadicado', $et->Radicado_idRadicado)
			->where('Radicado_vigencia', $et->Radicado_vigencia)
			->where('Etapa_idEtapa', $et->Etapa_idEtapa)
			->update(['actual' => 1]); 

			echo "Fecha: ".$et->fechaEtapa.".  Etapa: ".$et->Etapa_idEtapa."<br><br>"; 
			*/

		}
		

					/*
		//ACtualiza a 1 la última etapa
		DB::table('etapasproceso')
		->where('Radicado_idRadicado', $etapas[0]->Radicado_idRadicado)
		->where('Radicado_vigencia', $etapas[0]->Radicado_vigencia)
		->where('Etapa_idEtapa', $etapas[0]->Etapa_idEtapa)
		->update(['actual' => 1]); 
*/
		
	}
});


Route::get('/sinetapasprocesos', function()
{
	$radicados = DB::table('radicado')
	                      ->get();

	$contador = 0;

    foreach ($radicados as $radicado) 
    {
		$contador++;
		
		echo $contador."- ";

		$etapas = DB::table('etapasproceso')
					->where('Radicado_vigencia', $radicado->vigencia)
					->where('Radicado_idRadicado', $radicado->idRadicado)
					->first();

		if (count($etapas) > 0){
			echo "TIENE ETAPASPROCESO: ".$radicado->vigencia.'-'.$radicado->idRadicado."<br>";
		} 
		else 
		{
			echo "NO TIENE ETAPASPROCESO: ".$radicado->vigencia.'-'.$radicado->idRadicado."<br>";

			/*
			$et = DB::table('etapasproceso')
						->where('Radicado_vigencia', $etapaProceso->Radicado_vigencia)
						->where('Radicado_idRadicado', $etapaProceso->Radicado_idRadicado)
						->where('Etapa_idEtapa', '!=', 14)
					  ->orderBy('fechaEtapa', 'desc')
						->first();

						
			//ACtualiza a 1 la última etapa
			DB::table('etapasproceso')
			->where('Radicado_idRadicado', $et->Radicado_idRadicado)
			->where('Radicado_vigencia', $et->Radicado_vigencia)
			->where('Etapa_idEtapa', $et->Etapa_idEtapa)
			->update(['actual' => 1]); 

			echo "Fecha: ".$et->fechaEtapa.".  Etapa: ".$et->Etapa_idEtapa."<br><br>"; 
			*/

		}
		

					/*
		//ACtualiza a 1 la última etapa
		DB::table('etapasproceso')
		->where('Radicado_idRadicado', $etapas[0]->Radicado_idRadicado)
		->where('Radicado_vigencia', $etapas[0]->Radicado_vigencia)
		->where('Etapa_idEtapa', $etapas[0]->Etapa_idEtapa)
		->update(['actual' => 1]); 
*/
		
	}
});

Route::get('/diastranscurridos', function()
{
	$fechaInicial = '2020-05-03';
	$fechaFinal = '2020-07-23';
	$habil = 1;
	

	$dias = Util::diasTranscurridos($fechaInicial, $fechaFinal, $habil);

	echo $dias;

});

Route::get('/fecha-hechos', function() {


	
$array = [
	'2017-22' =>	'2013-01-01',
	'2018-72' =>	'2013-01-01',
	'2018-226' =>	'2013-01-01',
	'2018-267' =>	'2013-01-01',
	'2020-5' =>	'2013-01-01',
	'2018-90' =>	'2014-01-01',
	'2018-178' =>	'2014-01-01',
	'2018-172' =>	'2014-01-07',
	'2019-268' =>	'2015-04-15',
	'2019-45' =>	'2015-04-30',
	'2019-216' =>	'2015-06-17',
	'2018-235' =>	'2015-08-10',
	'2019-242' =>	'2015-10-01',
	'2020-49' =>	'2015-12-01',
	'2017-285' =>	'2016-01-01',
	'2017-286' =>	'2016-01-01',
	'2017-295' =>	'2016-01-01',
	'2017-297' =>	'2016-01-01',
	'2018-9' =>	'2016-01-01',
	'2018-58' =>	'2016-01-01',
	'2018-191' =>	'2016-01-01',
	'2018-204' =>	'2016-01-01',
	'2018-216' =>	'2016-01-01',
	'2018-261' =>	'2016-01-01',
	'2018-318' =>	'2016-01-01',
	'2019-51' =>	'2016-01-01',
	'2019-141' =>	'2016-01-01',
	'2019-167' =>	'2016-01-01',
	'2017-28' =>	'2016-11-01',
	'2017-294' =>	'2016-12-01',
	'2017-296' =>	'2016-12-01',
	'2017-298' =>	'2016-12-01',
	'2018-249' =>	'2016-12-21',
	'2017-153' =>	'2017-01-01',
	'2017-292' =>	'2017-01-01',
	'2017-300' =>	'2017-01-01',
	'2018-109' =>	'2017-01-01',
	'2018-159' =>	'2017-01-01',
	'2018-170' =>	'2017-01-01',
	'2018-242' =>	'2017-01-01',
	'2018-293' =>	'2017-01-01',
	'2018-297' =>	'2017-01-01',
	'2018-301' =>	'2017-01-01',
	'2018-323' =>	'2017-01-01',
	'2019-127' =>	'2017-01-01',
	'2019-132' =>	'2017-01-01',
	'2019-271' =>	'2017-01-01',
	'2020-25' =>	'2017-01-01',
	'2020-88' =>	'2017-01-01',
	'2017-210' =>	'2017-02-01',
	'2019-55' =>	'2017-03-13',
	'2018-248' =>	'2017-03-29',
	'2017-120' =>	'2017-04-01',
	'2019-189' =>	'2017-04-17',
	'2017-187' =>	'2017-05-01',
	'2018-279' =>	'2017-05-01',
	'2018-322' =>	'2017-05-01',
	'2018-85' =>	'2017-05-12',
	'2018-40' =>	'2017-05-16',
	'2018-77' =>	'2017-05-18',
	'2018-13' =>	'2017-06-01',
	'2018-132' =>	'2017-06-27',
	'2017-144' =>	'2017-07-15',
	'2017-160' =>	'2017-07-29',
	'2017-176' =>	'2017-08-01',
	'2017-222' =>	'2017-08-01',
	'2017-192' =>	'2017-09-01',
	'2018-17' =>	'2017-09-01',
	'2017-212' =>	'2017-10-01',
	'2017-233' =>	'2017-10-01',
	'2017-268' =>	'2017-10-01',
	'2017-231' =>	'2017-10-04',
	'2017-259' =>	'2017-10-19',
	'2017-237' =>	'2017-10-23',
	'2017-264' =>	'2017-11-01',
	'2017-270' =>	'2017-11-01',
	'2018-8' =>	'2017-11-01',
	'2018-27' =>	'2017-11-01',
	'2018-7' =>	'2017-11-09',
	'2017-282' =>	'2017-11-10',
	'2018-142' =>	'2017-11-14',
	'2017-281' =>	'2017-12-01',
	'2017-291' =>	'2017-12-01',
	'2017-304' =>	'2017-12-01',
	'2017-308' =>	'2017-12-01',
	'2018-2' =>	'2017-12-01',
	'2018-15' =>	'2017-12-01',
	'2019-19' =>	'2017-12-01',
	'2020-26' =>	'2017-12-01',
	'2018-200' =>	'2017-12-12',
	'2018-3' =>	'2017-12-15',
	'2018-33' =>	'2018-01-01',
	'2018-51' =>	'2018-01-01',
	'2018-81' =>	'2018-01-01',
	'2018-84' =>	'2018-01-01',
	'2018-124' =>	'2018-01-01',
	'2018-144' =>	'2018-01-01',
	'2018-146' =>	'2018-01-01',
	'2018-153' =>	'2018-01-01',
	'2018-156' =>	'2018-01-01',
	'2018-227' =>	'2018-01-01',
	'2018-253' =>	'2018-01-01',
	'2018-257' =>	'2018-01-01',
	'2018-260' =>	'2018-01-01',
	'2018-269' =>	'2018-01-01',
	'2018-272' =>	'2018-01-01',
	'2018-281' =>	'2018-01-01',
	'2018-291' =>	'2018-01-01',
	'2018-296' =>	'2018-01-01',
	'2019-3' =>	'2018-01-01',
	'2019-7' =>	'2018-01-01',
	'2019-50' =>	'2018-01-01',
	'2019-60' =>	'2018-01-01',
	'2019-87' =>	'2018-01-01',
	'2019-88' =>	'2018-01-01',
	'2019-113' =>	'2018-01-01',
	'2019-166' =>	'2018-01-01',
	'2019-207' =>	'2018-01-01',
	'2019-252' =>	'2018-01-01',
	'2019-288' =>	'2018-01-01',
	'2019-289' =>	'2018-01-01',
	'2020-19' =>	'2018-01-01',
	'2018-34' =>	'2018-01-18',
	'2019-286' =>	'2018-01-19',
	'2018-21' =>	'2018-02-01',
	'2018-39' =>	'2018-02-01',
	'2018-49' =>	'2018-02-01',
	'2018-52' =>	'2018-02-01',
	'2018-80' =>	'2018-02-01',
	'2018-92' =>	'2018-02-01',
	'2018-114' =>	'2018-02-01',
	'2018-197' =>	'2018-02-01',
	'2018-82' =>	'2018-02-05',
	'2018-98' =>	'2018-03-01',
	'2018-141' =>	'2018-03-01',
	'2018-155' =>	'2018-03-01',
	'2018-218' =>	'2018-03-01',
	'2019-238' =>	'2018-03-01',
	'2018-56' =>	'2018-03-09',
	'2018-310' =>	'2018-03-11',
	'2018-231' =>	'2018-03-15',
	'2019-22' =>	'2018-03-15',
	'2018-75' =>	'2018-03-20',
	'2018-94' =>	'2018-03-21',
	'2018-104' =>	'2018-03-23',
	'2018-86' =>	'2018-04-01',
	'2018-95' =>	'2018-04-01',
	'2018-128' =>	'2018-04-01',
	'2018-320' =>	'2018-04-01',
	'2019-137' =>	'2018-04-01',
	'2019-237' =>	'2018-04-01',
	'2018-87' =>	'2018-04-03',
	'2018-91' =>	'2018-04-16',
	'2018-286' =>	'2018-04-16',
	'2018-97' =>	'2018-04-17',
	'2018-93' =>	'2018-04-19',
	'2018-101' =>	'2018-04-19',
	'2019-173' =>	'2018-04-27',
	'2018-140' =>	'2018-05-01',
	'2018-223' =>	'2018-05-01',
	'2018-337' =>	'2018-05-01',
	'2019-235' =>	'2018-05-24',
	'2020-87' =>	'2018-05-24',
	'2018-145' =>	'2018-05-25',
	'2018-149' =>	'2018-06-01',
	'2018-275' =>	'2018-06-01',
	'2019-232' =>	'2018-06-01',
	'2018-201' =>	'2018-06-26',
	'2018-213' =>	'2018-07-01',
	'2019-215' =>	'2018-07-01',
	'2020-4' =>	'2018-07-01',
	'2018-173' =>	'2018-07-05',
	'2018-229' =>	'2018-07-10',
	'2019-175' =>	'2018-07-19',
	'2019-217' =>	'2018-07-19',
	'2018-316' =>	'2018-07-30',
	'2018-202' =>	'2018-08-01',
	'2018-224' =>	'2018-08-01',
	'2018-233' =>	'2018-08-01',
	'2018-250' =>	'2018-08-01',
	'2018-287' =>	'2018-08-01',
	'2018-308' =>	'2018-08-01',
	'2019-25' =>	'2018-08-01',
	'2019-204' =>	'2018-08-01',
	'2018-283' =>	'2018-08-08',
	'2018-207' =>	'2018-08-10',
	'2018-238' =>	'2018-08-14',
	'2018-271' =>	'2018-08-14',
	'2018-228' =>	'2018-08-22',
	'2018-334' =>	'2018-08-26',
	'2019-40' =>	'2018-08-26',
	'2019-49' =>	'2018-08-26',
	'2018-262' =>	'2018-09-01',
	'2018-268' =>	'2018-09-01',
	'2019-179' =>	'2018-09-01',
	'2019-205' =>	'2018-09-01',
	'2018-241' =>	'2018-09-05',
	'2018-282' =>	'2018-09-05',
	'2020-17' =>	'2018-09-06',
	'2019-250' =>	'2018-09-21',
	'2019-176' =>	'2018-09-27',
	'2018-302' =>	'2018-10-01',
	'2019-98' =>	'2018-10-01',
	'2018-277' =>	'2018-10-08',
	'2018-274' =>	'2018-10-11',
	'2018-280' =>	'2018-10-16',
	'2018-259' =>	'2018-10-19',
	'2019-170' =>	'2018-10-25',
	'2019-73' =>	'2018-10-30',
	'2018-317' =>	'2018-11-01',
	'2019-294' =>	'2018-11-01',
	'2019-296' =>	'2018-11-01',
	'2018-336' =>	'2018-11-07',
	'2020-84' =>	'2018-11-07',
	'2018-341' =>	'2018-11-23',
	'2018-304' =>	'2018-11-24',
	'2018-314' =>	'2018-12-01',
	'2019-110' =>	'2018-12-01',
	'2019-181' =>	'2018-12-01',
	'2019-253' =>	'2018-12-01',
	'2020-27' =>	'2018-12-01',
	'2020-83' =>	'2018-12-07',
	'2018-338' =>	'2018-12-21',
	'2019-256' =>	'2018-12-21',
	'2019-85' =>	'2018-12-26',
	'2019-64' =>	'2019-01-01',
	'2019-82' =>	'2019-01-01',
	'2019-89' =>	'2019-01-01',
	'2019-94' =>	'2019-01-01',
	'2019-106' =>	'2019-01-01',
	'2019-140' =>	'2019-01-01',
	'2019-192' =>	'2019-01-01',
	'2019-193' =>	'2019-01-01',
	'2019-220' =>	'2019-01-01',
	'2019-222' =>	'2019-01-01',
	'2019-234' =>	'2019-01-01',
	'2019-239' =>	'2019-01-01',
	'2019-244' =>	'2019-01-01',
	'2019-249' =>	'2019-01-01',
	'2019-259' =>	'2019-01-01',
	'2019-269' =>	'2019-01-01',
	'2019-270' =>	'2019-01-01',
	'2019-272' =>	'2019-01-01',
	'2019-273' =>	'2019-01-01',
	'2019-291' =>	'2019-01-01',
	'2020-10' =>	'2019-01-01',
	'2020-32' =>	'2019-01-01',
	'2020-50' =>	'2019-01-01',
	'2020-51' =>	'2019-01-01',
	'2019-56' => 	'2019-01-02',
	'2019-1' =>	'2019-01-05',
	'2019-71' =>	'2019-01-09',
	'2020-12' =>	'2019-01-17',
	'2019-185' =>	'2019-01-21',
	'2019-13' =>	'2019-01-23',
	'2019-68' =>	'2019-01-23',
	'2019-37' =>	'2019-01-24',
	'2019-97' =>	'2019-01-25',
	'2019-108' =>	'2019-01-25',
	'2019-109' =>	'2019-01-28',
	'2019-78' =>	'2019-02-01',
	'2019-130' =>	'2019-02-01',
	'2019-150' =>	'2019-02-01',
	'2019-151' =>	'2019-02-01',
	'2019-246' =>	'2019-02-01',
	'2019-263' =>	'2019-02-01',
	'2020-38' => 	'2019-02-01',
	'2019-182' =>	'2019-02-06',
	'2019-84' =>	'2019-02-09',
	'2019-52' =>	'2019-02-28',
	'2019-57' =>	'2019-02-28',
	'2019-72' =>	'2019-03-01',
	'2019-74' =>	'2019-03-01',
	'2019-76' =>	'2019-03-01',
	'2019-77' =>	'2019-03-01',
	'2019-80' =>	'2019-03-01',
	'2019-86' =>	'2019-03-01',
	'2019-101' =>	'2019-03-01',
	'2019-227' =>	'2019-03-01',
	'2019-245' =>	'2019-03-01',
	'2020-41' =>	'2019-03-01',
	'2019-190' =>	'2019-03-11',
	'2019-66' =>	'2019-03-12',
	'2019-124' =>	'2019-03-15',
	'2019-69' =>	'2019-03-16',
	'2019-70' =>	'2019-03-16',
	'2019-104' =>	'2019-03-18',
	'2019-105' =>	'2019-03-18',
	'2019-111' =>	'2019-03-22',
	'2019-75' =>	'2019-03-23',
	'2019-79' =>	'2019-03-24',
	'2019-123' =>	'2019-03-27',
	'2019-90' =>	'2019-04-01',
	'2019-128' =>	'2019-04-01',
	'2019-157' =>	'2019-04-01',
	'2019-164' =>	'2019-04-01',
	'2019-223' =>	'2019-04-01',
	'2020-23' =>	'2019-04-02',
	'2019-83' =>	'2019-04-08',
	'2019-199' =>	'2019-04-09',
	'2019-117' =>	'2019-04-15',
	'2019-103' =>	'2019-04-22',
	'2019-184' =>	'2019-04-23',
	'2019-107' =>	'2019-04-27',
	'2019-156' =>	'2019-04-29',
	'2019-100' =>	'2019-05-01',
	'2020-15' => 	'2019-05-01',
	'2019-112' =>	'2019-05-13',
	'2019-292' =>	'2019-05-15',
	'2019-129' =>	'2019-05-17',
	'2019-136' =>	'2019-05-17',
	'2019-120' =>	'2019-05-18',
	'2019-119' =>	'2019-05-20',
	'2020-71' =>	'2019-05-22',
	'2019-116' =>	'2019-05-23',
	'2019-122' =>	'2019-05-24',
	'2019-138' =>	'2019-05-27',
	'2020-86' =>	'2019-05-27',
	'2019-146' =>	'2019-06-01',
	'2019-169' =>	'2019-06-01',
	'2019-178' =>	'2019-06-01',
	'2019-202' =>	'2019-06-01',
	'2019-226' =>	'2019-06-01',
	'2019-147' =>	'2019-06-05',
	'2019-187' =>	'2019-06-05',
	'2020-43' =>	'2019-06-05',
	'2019-131' =>	'2019-06-06',
	'2019-135' =>	'2019-06-12',
	'2019-142' =>	'2019-06-13',
	'2019-191' =>	'2019-06-13',
	'2019-143' =>	'2019-06-17',
	'2019-158' =>	'2019-06-18',
	'2019-196' =>	'2019-06-19',
	'2019-144' =>	'2019-06-20',
	'2020-45' =>	'2019-07-01',
	'2019-149' =>	'2019-07-02',
	'2019-148' =>	'2019-07-03',
	'2019-154' =>	'2019-07-08',
	'2019-155' =>	'2019-07-10',
	'2020-33' =>	'2019-07-14',
	'2019-197' =>	'2019-07-15',
	'2019-159' =>	'2019-07-16',
	'2019-188' =>	'2019-07-19',
	'2019-201' =>	'2019-07-25',
	'2019-194' =>	'2019-07-31',
	'2019-209' =>	'2019-07-31',
	'2020-31' =>	'2019-07-31',
	'2019-210' =>	'2019-08-01',
	'2019-260' =>	'2019-08-01',
	'2019-295' =>	'2019-08-01',
	'2020-46' =>	'2019-08-13',
	'2019-240' =>	'2019-08-21',
	'2019-200' =>	'2019-08-23',
	'2019-213' =>	'2019-08-27',
	'2019-221' =>	'2019-08-29',
	'2019-206' =>	'2019-08-30',
	'2019-212' =>	'2019-09-01',
	'2019-218' =>	'2019-09-01',
	'2020-3' =>	'2019-09-02',
	'2019-214' =>	'2019-09-04',
	'2019-228' =>	'2019-09-10',
	'2019-219' =>	'2019-09-11',
	'2019-224' =>	'2019-09-18',
	'2019-230' =>	'2019-09-19',
	'2019-229' =>	'2019-09-23',
	'2019-243' =>	'2019-09-23',
	'2019-251' =>	'2019-09-24',
	'2020-2' =>	'2019-09-25',
	'2019-233' =>	'2019-09-27',
	'2019-248' =>	'2019-10-01',
	'2019-290' =>	'2019-10-01',
	'2020-68' =>	'2019-10-01',
	'2020-70' =>	'2019-10-01',
	'2019-261' =>	'2019-10-03',
	'2019-241' =>	'2019-10-04',
	'2020-37' =>	'2019-10-05',
	'2020-81' =>	'2019-10-07',
	'2020-29' =>	'2019-10-08',
	'2019-264' =>	'2019-10-21',
	'2019-255' =>	'2019-10-22',
	'2019-254' =>	'2019-10-24',
	'2019-276' =>	'2019-10-27',
	'2019-277' =>	'2019-10-27',
	'2019-278' =>	'2019-10-27',
	'2019-279' =>	'2019-10-27',
	'2019-281' =>	'2019-10-27',
	'2019-282' =>	'2019-10-27',
	'2019-284' =>	'2019-10-27',
	'2019-280' =>	'2019-10-28',
	'2019-247' =>	'2019-10-29',
	'2019-283' =>	'2019-10-29',
	'2019-266' =>	'2019-11-01',
	'2019-275' =>	'2019-11-01',
	'2019-299' =>	'2019-11-01',
	'2020-7' =>	'2019-11-01',
	'2020-13' =>	'2019-11-01',
	'2020-22' =>	'2019-11-01',
	'2019-297' =>	'2019-11-05',
	'2019-258' =>	'2019-11-18',
	'2019-274' =>	'2019-11-18',
	'2019-285' =>	'2019-11-18',
	'2019-257' =>	'2019-11-19',
	'2019-262' =>	'2019-11-20',
	'2019-267' =>	'2019-11-21',
	'2019-287' =>	'2019-12-01',
	'2020-47' =>	'2019-12-01',
	'2019-293' =>	'2019-12-04',
	'2020-14' =>	'2019-12-04',
	'2019-298' =>	'2019-12-12',
	'2020-21' =>	'2019-12-13',
	'2020-20' =>	'2020-01-01',
	'2020-24' =>	'2020-01-01',
	'2020-36' =>	'2020-01-01',
	'2020-40' =>	'2020-01-01',
	'2020-53' =>	'2020-01-01',
	'2020-57' =>	'2020-01-01',
	'2020-63' =>	'2020-01-01',
	'2020-90' =>	'2020-01-01',
	'2020-82' =>	'2020-01-01',
	'2020-11' =>	'2020-01-16',
	'2020-16' =>	'2020-01-22',
	'2020-39' =>	'2020-01-23',
	'2020-18' =>	'2020-01-29',
	'2020-54' =>	'2020-02-01',
	'2020-78' =>	'2020-02-01',
	'2020-61' =>	'2020-02-03',
	'2020-30' =>	'2020-02-07',
	'2020-35' =>	'2020-02-14',
	'2020-52' =>	'2020-02-17',
	'2020-34' =>	'2020-02-20',
	'2020-44' => 	'2020-03-01',
	'2020-59' =>	'2020-03-01',
	'2020-60' =>	'2020-03-01',
	'2020-42' =>	'2020-03-02',
	'2020-66' =>	'2020-03-30',
	'2020-58' =>	'2020-03-31',
	'2020-55' =>	'2020-04-03',
	'2020-65' =>	'2020-04-20',
	'2020-62' =>	'2020-05-01',
	'2020-67' =>	'2020-05-01',
	'2020-74' =>	'2020-05-01',
	'2020-75' =>	'2020-05-21',
	'2020-76' =>	'2020-06-26',
	'2020-73' =>	'2020-07-03',
	'2020-77' =>	'2020-07-23',
	'2020-92' =>	'2020-08-01',
	'2020-89' =>	'2020-08-05',
	'2020-80' =>	'2020-08-07',
];

	foreach ($array as $radicado => $fecha) {

		echo "Actualizado: <br>";
		echo $radicado."<br>";
		echo $fecha."<br>";


		list($vigencia, $idRadicado) = explode("-", $radicado);
		     				 

		//Actualiza la fecha de los presuntos hechos
		DB::table('radicado')
		->where('idRadicado', $idRadicado)
		->where('vigencia', $vigencia)
		->update(['fechaHechos' => $fecha]);
	  //------------------------------------------------

	}
});

Route::get('/actualizar-finalizacion', function() {

	$radicados = DB::table('radicado')
				->where('activo', '=', 0)
				->get();
				
	foreach ($radicados as $radicado) 
	{
		echo "rad. ".$radicado->vigencia."-".$radicado->idRadicado."<br>";
	
		$etapas = DB::table('etapasproceso')
		  		->select('nombreEtapa', 'fechaEtapa', 'fechaFinalEtapa', 'Etapa_idEtapa', 'habil')
				  ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				  ->where('Radicado_idRadicado', '=', $radicado->idRadicado)
				->where('Radicado_vigencia', '=', $radicado->vigencia)
				->where('actual', '=', 1)
				//->whereIn('Etapa_idEtapa', [8, 9, 10, 14, 12, 18, 19])
				->first();
				
		if (count($etapas) > 0) 
		{
			DB::table('radicado')
		  	  ->where('vigencia', $radicado->vigencia)
			  ->where('idRadicado', $radicado->idRadicado)
			 ->update(['fechaFinalizado' => $etapas->fechaEtapa]);

			echo "et. ".$etapas->fechaEtapa."<br>";
		} 
		else 
		{
			echo "No encontró en etapas<br>";
		}
	}
});

Route::get('/uuid', function() {
	$uuid = Uuid::generate();

	echo $uuid;
});

Route::get('/incrementable-etapas-proceso', function()
{
	$etapasProcesos = DB::table('etapasproceso')
 					      ->get();

	$cont = 1;

    foreach ($etapasProcesos as $etapaProceso) 
    {
        DB::table('etapasproceso')
		  ->where('Radicado_idRadicado', $etapaProceso->Radicado_idRadicado)
		  ->where('Radicado_vigencia', $etapaProceso->Radicado_vigencia)
		  ->where('Etapa_idEtapa', $etapaProceso->Etapa_idEtapa)
          ->update(['idEstadoEtapa' => $cont]); 

		$cont++;

		echo $etapaProceso->Radicado_idRadicado."-".$etapaProceso->Radicado_vigencia." ".$etapaProceso->Etapa_idEtapa."<br>";
    }

});

Route::get('/credenciales/{documento}', 'UsuarioController@actionCredenciales');

/*
1- Agregar la tabla tiposEtapas

	CREATE TABLE IF NOT EXISTS `cdi`.`tiposEtapa` (
	`idTipoEtapa` INT NOT NULL AUTO_INCREMENT,
	`tipoEtapa` VARCHAR(128) NOT NULL COMMENT 'Etapa de instrucción, Etapa de juicio ordinario, Segunda instancia',
	PRIMARY KEY (`idTipoEtapa`))
	ENGINE = InnoDB

2- En la tabla etapa agregar la clave foránea de tiposEtapas - Predeterminado NULL

	ALTER TABLE cdi.etapa ADD tiposEtapa_idTipoEtapa INT(11) NULL;
	ALTER TABLE cdi.etapa 
	ENGINE=InnoDB;
	ALTER TABLE cdi.etapa ADD CONSTRAINT etapa_FK FOREIGN KEY (tiposEtapa_idTipoEtapa) REFERENCES cdi.tiposetapa(idTipoEtapa);

	ALTER TABLE cdi.etapa DROP KEY etapa_un;
	ALTER TABLE cdi.etapa ADD CONSTRAINT etapa_un UNIQUE KEY (tiposEtapa_idTipoEtapa);

3- En la tabla etapasproceso agregar un campo al inicio llamado idEtapaProceso el cuál será la llave int(11) Not null
4- Ejecutar la ruta: /incrementable-etapas-proceso para actualizar la numeración
5- Establecer el campo idEtapaProceso como primary: ALTER TABLE `etapasproceso` ADD PRIMARY KEY(`idEtapaProceso`);
6- Establecer la propiedad autoincrementable al campo idEtapaProceso: ALTER TABLE `etapasproceso` CHANGE `idEtapaProceso` `idEtapaProceso` INT(11) NOT NULL AUTO_INCREMENT;
7- Verificar el último radicado de la tabla etapasproceso: 7037 y establecerlo +1 como el próximo auto incrementable
8- Cambiar el motor de la tabla de MyISAM a INNODB
9- Eliminar los índices y volverlos a crear

*/