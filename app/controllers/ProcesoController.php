<?php
class ProcesoController extends \BaseController
{
	public function actionValorar()
	{
		$usuario = Session::get('documentoUsuario');

		$procesos = DB::table('radicado')
  				  ->join('acumularadicado', function($join)
					{
				       	$join->on('acumularadicado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumularadicado.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI')
			            	 ->where('radicado.EstadoRadicado_idEstadoRadicado', '=', 5);//PROCESO ASIGNADO A PROFESIONAL
			    	})
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('abogado.Persona_documentoPersona', '=', $usuario)
				  ->get();

		return View::make('procesos.valoracion')
  			  	   ->with('procesos', $procesos)
  			  	   ->with('menuActivo', "valoracion");

	}

	public function actionGuardarValoracion()
	{
		$fechaHoy = date("Y-m-d");

		$usuario = Session::get('documentoUsuario');
		//Decodifica formato el json
		$indagacion = json_decode(Input::get('jsonIndagacion'), true);
		$investigacion = json_decode(Input::get('jsonInvestigacion'), true);
		$inhibitorio = json_decode(Input::get('jsonInhibitorio'), true);

		// ------------------- INDAGACION PRELIMINAR --------------------------------------------
		if (count($indagacion) > 0)
		{
			for ($i = 0; $i < count($indagacion); $i++)
			{
		     	list($vigencia, $idRadicado) = explode("-", $indagacion[$i]);
		     	$rad[0] = $idRadicado;
		     	$vig[0] = $vigencia;

		     	//Actualiza el estado del radicado
				DB::table('radicado')
		          ->where('idRadicado', $rad[0])
		          ->where('vigencia', $vig[0])
		          ->update(['EstadoRadicado_idEstadoRadicado' => 7]);// Estado 7: "Inicia etapa INDAGACIÓN PRELIMINAR"
				//------------------------------------------------

				//Actualiza la etapa del radicado
				DB::table('radicado')
		          ->where('idRadicado', $rad[0])
		          ->where('vigencia', $vig[0])
		          ->update(['Etapa_idEtapa' => 1]);// Etapa 1: "Inicia etapa INDAGACIÓN PRELIMINAR"
				//------------------------------------------------

                //Almacena ObservacionesRadicado -- Estado: (Inicia etapa INDAGACIÓN)
                $observacionRadicado = new ObservacionRadicado;
                $observacionRadicado->EstadoRadicado_idEstadoRadicado = 7; //Estado 7: "Inicia etapa INDAGACIÓN
                $observacionRadicado->Radicado_idRadicado = $rad[0];
                $observacionRadicado->Radicado_vigencia = $vig[0]; // Almacena el año actual
                $observacionRadicado->Persona_documentoPersona = $usuario;
                $observacionRadicado->observacion = "El proceso pasó a la etapa de Indagación Preliminar.";
                $observacionRadicado->fechaObservacion = $fechaHoy;// Fecha actual
                $observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
                $observacionRadicado->save();

                //Deja cualquier etapa del radicado inactivas -- (Actual = 0)
				DB::table('etapasproceso')
		          ->where('Radicado_idRadicado', $rad[0])
		          ->where('Radicado_vigencia', $vig[0])
		          ->update(['actual' => 0]);// (Actual = 0)
				//------------------------------------------------

		        //Agrega la nueva etapa: VALORACIÓN
                $etapaProceso = new EtapaProceso;
                $etapaProceso->Radicado_idRadicado = $rad[0];
                $etapaProceso->Radicado_vigencia = $vig[0]; // Almacena el año actual
                $etapaProceso->Etapa_idEtapa = 1;//1 Etapa INDAGACIÓN PRELIMINAR
                $etapaProceso->fechaEtapa = $fechaHoy;// Fecha actual
                $etapaProceso->observacion = "El proceso pasó a la etapa de Indagación Preliminar.";
                $etapaProceso->actual = 1;
                $etapaProceso->fechaFinalEtapa = Util::calcularFechaFinalEtapa(1);//1 Indagación Preliminar
                $etapaProceso->save();
			}
		}
		//-------------------- INDAGACIÓN PRELIMINAR --------------------------------------------

		// ------------------- INVESTIGACIÓN DISCIPLINARIA --------------------------------------
		if (count($investigacion) > 0)
		{
			for ($i = 0; $i < count($investigacion); $i++)
			{
		     	list($vigencia, $idRadicado) = explode("-", $investigacion[$i]);
		     	$rad[0] = $idRadicado;
		     	$vig[0] = $vigencia;

		     	//Actualiza el estado del radicado
				DB::table('radicado')
		          ->where('idRadicado', $rad[0])
		          ->where('vigencia', $vig[0])
		          ->update(['EstadoRadicado_idEstadoRadicado' => 8]);// Estado 8: "INICIA ETAPA DE INVESTIGACIÓN DISCIPLINARIA"
				//------------------------------------------------

				//Actualiza la etapa del radicado
				DB::table('radicado')
		          ->where('idRadicado', $rad[0])
		          ->where('vigencia', $vig[0])
		          ->update(['Etapa_idEtapa' => 2]);// Etapa 2: "INICIA ETAPA DE INVESTIGACIÓN DISCIPLINARIA"
				//------------------------------------------------

                //Almacena ObservacionesRadicado -- Estado: (Inicia etapa INDAGACIÓN)
                $observacionRadicado = new ObservacionRadicado;
                $observacionRadicado->EstadoRadicado_idEstadoRadicado = 8; // Estado 8: "INICIA ETAPA DE INVESTIGACIÓN DISCIPLINARIA"
                $observacionRadicado->Radicado_idRadicado = $rad[0];
                $observacionRadicado->Radicado_vigencia = $vig[0]; // Almacena el año actual
                $observacionRadicado->Persona_documentoPersona = $usuario;
                $observacionRadicado->observacion = "El proceso pasó a la etapa de Investigación Disciplinaria.";
                $observacionRadicado->fechaObservacion = $fechaHoy;// Fecha actual
                $observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
                $observacionRadicado->save();

                //Deja cualquier etapa del radicado inactivas -- (Actual = 0)
				DB::table('etapasproceso')
		          ->where('Radicado_idRadicado', $rad[0])
		          ->where('Radicado_vigencia', $vig[0])
		          ->update(['actual' => 0]);// (Actual = 0)
				//------------------------------------------------

		        //Agrega la nueva etapa: VALORACIÓN
                $etapaProceso = new EtapaProceso;
                $etapaProceso->Radicado_idRadicado = $rad[0];
                $etapaProceso->Radicado_vigencia = $vig[0]; // Almacena el año actual
                $etapaProceso->Etapa_idEtapa = 2;//ETAPA - INVESTIGACIÓN DISCIPLINARIA
                $etapaProceso->fechaEtapa = $fechaHoy;// Fecha actual
                $etapaProceso->observacion = "El proceso pasó a la etapa de Investigación Disciplinaria.";
                $etapaProceso->actual = 1;
                $etapaProceso->fechaFinalEtapa = Util::calcularFechaFinalEtapa(2);//2 Investigación Disciplinaria
                $etapaProceso->save();
			}
		}
		//-------------------- INVESTIGACIÓN DISCIPLINARIA --------------------------------------

		// ------------------- INHIBITORIO ------------------------------------------------------
		if (count($inhibitorio) > 0)
		{
			for ($i = 0; $i < count($inhibitorio); $i++)
			{
		     	list($vigencia, $idRadicado) = explode("-", $inhibitorio[$i]);
		     	$rad[0] = $idRadicado;
		     	$vig[0] = $vigencia;

		     	//Actualiza el estado del radicado
				DB::table('radicado')
		          ->where('idRadicado', $rad[0])
		          ->where('vigencia', $vig[0])
		          ->update(['EstadoRadicado_idEstadoRadicado' => 50]);// Estado 50: "INICIA PROCESO INHIBITORIO"
				//------------------------------------------------

				//Actualiza la etapa del radicado
				DB::table('radicado')
		          ->where('idRadicado', $rad[0])
		          ->where('vigencia', $vig[0])
		          ->update(['Etapa_idEtapa' => 9]);// Etapa 9: "INICIA ETAPA DE INHIBITORIO"
				//------------------------------------------------

                //Almacena ObservacionesRadicado -- Estado: (Inicia etapa INDAGACIÓN)
                $observacionRadicado = new ObservacionRadicado;
                $observacionRadicado->EstadoRadicado_idEstadoRadicado = 50; // Estado 50: "INICIA INHIBITORIO"
                $observacionRadicado->Radicado_idRadicado = $rad[0];
                $observacionRadicado->Radicado_vigencia = $vig[0]; // Almacena el año actual
                $observacionRadicado->Persona_documentoPersona = $usuario;
                $observacionRadicado->observacion = "Pasó a proceso de Inhibitorio.";
                $observacionRadicado->fechaObservacion = $fechaHoy;// Fecha actual
                $observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
                $observacionRadicado->save();

                //Deja cualquier etapa del radicado inactivas -- (Actual = 0)
				DB::table('etapasproceso')
		          ->where('Radicado_idRadicado', $rad[0])
		          ->where('Radicado_vigencia', $vig[0])
		          ->update(['actual' => 0]);// (Actual = 0)
				//------------------------------------------------

		        //Agrega la nueva etapa: VALORACIÓN
                $etapaProceso = new EtapaProceso;
                $etapaProceso->Radicado_idRadicado = $rad[0];
                $etapaProceso->Radicado_vigencia = $vig[0]; // Almacena el año actual
                $etapaProceso->Etapa_idEtapa = 9;//ETAPA - INHIBITORIO
                $etapaProceso->fechaEtapa = $fechaHoy;// Fecha actual
                $etapaProceso->observacion = "Pasó a proceso de Inhibitorio.";
                $etapaProceso->actual = 1;
                $etapaProceso->fechaFinalEtapa = Util::calcularFechaFinalEtapa(9);//9 Inhibitorio
                $etapaProceso->save();
			}
		}
		//-------------------- INHIBITORIO ------------------------------------------------------
	}

	public function actionProcesosActivos()
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

		//NEw

		$etapas = DB::table('etapa')
			     ->leftJoin('tiposetapa', 'etapa.tiposetapa_idTipoEtapa', '=', 'tiposetapa.idTipoEtapa')		
					  ->get();

		$arrEtapas = array();

		foreach ($etapas as $etapa) 
		{
			$total = Util::traerCantidadEtapa($etapa->idEtapa);
			if($total > 0)
			{
				$arrEtapa = array('idEtapa' => $etapa->idEtapa, 'etapa' => $etapa->nombreCorto, 'tipoEtapa' => $etapa->tipoEtapa, 'total' => $total);
				array_push($arrEtapas, $arrEtapa);
			}
		}

		return View::make('inicio')
				->with('arrEtapas', $arrEtapas)
				->with('tareas', $tareas)
				->with('autos', $autos)
				->with('porcentaje', $porcentaje)
				->with('cantAutos', count($autosAsig))
				->with('menuActivo', "activos");
	}

	public function actionCargarProcesosActivosEtapa()
	{
		$documentoUsuario = Session::get('documentoUsuario');
		$idEtapa = Input::get('idEtapa');
		$nombreEtapa = Util::traerNombreEtapaId($idEtapa);

		$procesos = DB::table('radicado')
				   ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'presuntosHechos', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'falta', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'vigencia', 'idRadicado', 'idEtapa')
  		  		     ->join('acumulaqueja', function($join) {
				  $join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				       ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				  })
				     ->join('abogadoasignado', function($join) {
			      $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			           ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			        ->where('abogadoasignado.actual', '=', 'SI');
			      })
				     ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				 ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				     ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				 ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				     ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('radicado.activo', '=', 1)
				  ->where('radicado.Etapa_idEtapa', '=', $idEtapa)
				  ->where('abogado.Persona_documentoPersona', $documentoUsuario)
				  ->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2) //2 Radicado acumulado
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return View::make('plantillas.ajaxCargarProcesosActivos')
  			  	   ->with('procesos', $procesos)
  			  	   ->with('nombreEtapa', $nombreEtapa)
  			  	   ->with('menuActivo', "activos");
	}

	public function actionProcesosFinalizados()
	{
		$usuario = Session::get('documentoUsuario');

		$procesos = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('observacionesradicado', function($join)
				 	{
			       		$join->on('observacionesradicado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('observacionesradicado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('observacionesradicado.EstadoRadicado_idEstadoRadicado', '=', 63);
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  //->where('radicado.activo', '=', 0)
				  //->where('radicado.Etapa_idEtapa', '=', 14)//14 Finalizados
				  //->where('radicado.EstadoRadicado_idEstadoRadicado', '=', 63)//63  PROCESO FINALIZADO
				  ->where('abogado.Persona_documentoPersona', '=', $usuario)
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return View::make('procesos.finalizados')
  			  	   ->with('procesos', $procesos)
  			  	   ->with('menuActivo', "finalizados");
	}

	public function actionProcesosFinalizadosFiltro($etapa)
	{
		$usuario = Session::get('documentoUsuario');

		switch ($etapa)
		{
			case '8':
				$nombreEtapa = "FALLADOS";
				break;
			case '9':
				$nombreEtapa = "INHIBIDOS";
				break;
			case '10':
				$nombreEtapa = "ARCHIVADOS";
				break;
			case '12':
				$nombreEtapa = "CANCELADOS";
				break;
		}

		$procesos = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('radicado.activo', '=', 1)
				  ->where('radicado.Etapa_idEtapa', '=', $etapa)
				  ->where('abogado.Persona_documentoPersona', '=', $usuario)
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return View::make('procesos.activos')
  			  	   ->with('procesos', $procesos)
  			  	   ->with('nombreEtapa', $nombreEtapa)
  			  	   ->with('menuActivo', "finalizados");
	}

	public function actionVerProceso($vigencia, $idRadicado)
	{
		$proceso = DB::table('radicado')
		->select('vigencia', 'idRadicado', 'fechaHechos', 'radicado.activo as activoProceso')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  //->where('radicado.activo', '=', 1)
				  ->where('radicado.idRadicado', '=', $idRadicado)
				  ->where('radicado.vigencia', '=', $vigencia)
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		//Trael el id de la etapa actual
		$etapas = DB::table('etapasproceso')
		           ->select('Etapa_idEtapa', 'tiposEtapa_idTipoEtapa')
		             ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				    ->where('Radicado_idRadicado', '=', $idRadicado)
				    ->where('Radicado_vigencia', '=', $vigencia)
				    ->where('actual', '=', 1)//1 actual
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

		$idEtapa = 0;
		$fase = 0;

  		if(count($etapas) > 0)
  		{
  			$idEtapa = $etapas->Etapa_idEtapa;
			$fase = $etapas->tiposEtapa_idTipoEtapa;
  		}  			

		$observaciones =  DB::table('observacionesradicado')
						  ->join('estadoradicado', 'observacionesradicado.EstadoRadicado_idEstadoRadicado', '=', 'estadoradicado.idEstadoRadicado')
						  ->join('persona', 'observacionesradicado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						  ->where('observacionesradicado.Radicado_idRadicado', '=', $idRadicado)
						  ->where('observacionesradicado.Radicado_vigencia', '=', $vigencia)
						  ->where('estadoradicado.lineaTiempo', '=', 1)// visible en la línea de tiempo
						  ->orderBy('fechaObservacion')
						  ->get();

		$archivos = DB::table('archivo')
					  ->join('etapa', 'archivo.Etapa_idEtapa', '=', 'etapa.idEtapa')
					  ->join('tipoarchivo', 'archivo.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
					  ->where('archivo.Radicado_idRadicado', '=', $idRadicado)
					  ->where('archivo.Radicado_vigencia', '=', $vigencia)
					  //->where('archivo.vistoBueno', '=', 'SI')
					  ->Where('archivo.vistoBueno', '=', 'N/A')
					  ->orderBy('archivo.idArchivo')
					  ->get();

		$bitacoras =  DB::table('observacionesradicado')
						  ->join('estadoradicado', 'observacionesradicado.EstadoRadicado_idEstadoRadicado', '=', 'estadoradicado.idEstadoRadicado')
						  ->join('persona', 'observacionesradicado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						  ->where('observacionesradicado.Radicado_idRadicado', '=', $idRadicado)
						  ->where('observacionesradicado.Radicado_vigencia', '=', $vigencia)
						  ->orderBy('observacionesradicado.fechaObservacion')
						  ->get();

		//Trae la lista de etapas por las que ha pasado el proceso -------------------------
		$etp = DB::table('etapasproceso')
			    ->select('Etapa_idEtapa')
				->where('Radicado_idRadicado', '=', $idRadicado)
				->where('Radicado_vigencia', '=', $vigencia)
				->where('Etapa_idEtapa', '!=', 14)//Todas las etapas excepto las finalizadas
                ->get();

        $etapasProceso = json_decode(json_encode($etp),TRUE);

		$lista_etapas = DB::table('etapa')
							   ->whereIn('idEtapa', $etapasProceso)
	   						   ->orderBy('nombreEtapa', 'desc')
	   						   ->lists('nombreEtapa','idEtapa');

		$documentoAbogado = Util::traerDocumentoAbogadoAsignado($vigencia, $idRadicado);

		return View::make('procesos.ver')
  			  	   ->with('proceso', $proceso)
				   ->with('documentoAbogado', $documentoAbogado)
				   ->with('etapaActual', Util::traerNombreEtapaId($idEtapa))					   
  			  	   ->with('idEtapa', $idEtapa)
				   ->with('fase', $fase)
  			  	   ->with('observaciones', $observaciones)
  			  	   ->with('archivos', $archivos)
  			  	   ->with('bitacoras', $bitacoras)
  			  	   ->with('lista_etapas', $lista_etapas)
  			  	   ->with('menuActivo', "activos");
	}

	public function actionActuacionesProceso($vigencia, $idRadicado)
	{
		$autoRemisionCompetencia = DB::table('auto')
					 				 ->where('Radicado_idRadicado', $idRadicado)
									 ->where('Radicado_vigencia', $vigencia)
									 ->where('Etapa_idEtapa', 19)//19 Remisión por competencia
									 ->count();

		$proceso = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})				 
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  //->where('radicado.activo', '=', 1)
				  ->where('radicado.idRadicado', '=', $idRadicado)
				  ->where('radicado.vigencia', '=', $vigencia)
				  ->groupBy('radicado.idRadicado')
				  ->get();

		//Trael el id de la etapa actual
		$etapas = DB::table('etapasproceso')
		           ->select('Etapa_idEtapa', 'tiposEtapa_idTipoEtapa')
		             ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				    ->where('Radicado_idRadicado', '=', $idRadicado)
				    ->where('Radicado_vigencia', '=', $vigencia)
				    ->where('actual', '=', 1)//1 actual
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

		$idEtapa = 0;
		$fase = 0;

  		if(count($etapas) > 0)
  		{
  			$idEtapa = $etapas->Etapa_idEtapa;
			$fase = $etapas->tiposEtapa_idTipoEtapa;
  		}

		$tiposPlantillas =  TipoPlantilla::all();

		//Trae la lista de etapas por las que ha pasado el proceso -------------------------
		$etp = DB::table('etapasproceso')
			    ->select('Etapa_idEtapa')
				 ->where('Radicado_idRadicado', '=', $idRadicado)
				 ->where('Radicado_vigencia', '=', $vigencia)
                   ->get();

        $etapasProceso = json_decode(json_encode($etp),TRUE);

		//Lista de etapas para el filtro de expediente (Etapas por donde ya ha pasado el proceso)
		$lista_etapas = DB::table('etapa')
						->whereIn('idEtapa', $etapasProceso)
						->orderBy('nombreEtapa', 'desc')
						  ->lists('nombreEtapa','idEtapa');

		//Trae la fase actual del proceso						  
		$faseActual = Util::actionTraerFase($vigencia, $idRadicado);		

		$lista_etapas_fase = DB::table('etapa')
						       ->where('fase', 'like', '%'.$faseActual.'%')
							   ->where('auto', 1)
  						     ->orderBy('nombreEtapa', 'asc')
							   ->lists('nombreEtapa','idEtapa');

		$documentoAbogado = Util::traerDocumentoAbogadoAsignado($vigencia, $idRadicado);

		return View::make('procesos.actuaciones')
  			  	   ->with('proceso', $proceso)
				   ->with('documentoAbogado', $documentoAbogado)
  			  	   ->with('idEtapa', $idEtapa)
				   ->with('fase', $fase)
  			  	   ->with('tiposPlantillas', $tiposPlantillas)
  			  	   ->with('menuActivo', "activos")
				   ->with('lista_etapas', $lista_etapas)					   
  			  	   ->with('lista_etapas_fase', $lista_etapas_fase)				   
				   ->with('proceso', $proceso)
				   ->with('autoRemisionCompetencia', $autoRemisionCompetencia);
	}

	
	public function actionBuscarRadicadoPlantillas() 
	{
		list($vigencia, $idRadicado) = explode("-", Input::get("radicado"));
		$idRadicado = (int)$idRadicado;

		$proceso = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				  //->where('radicado.activo', '=', 1)
				  ->where('radicado.idRadicado', '=', $idRadicado)
				  ->where('radicado.vigencia', '=', $vigencia)
				  ->groupBy('radicado.idRadicado')
				  ->get();

				  //Trael el id de la etapa actual
		$etapas = DB::table('etapasproceso')
					->where('Radicado_idRadicado', '=', $idRadicado)
					->where('Radicado_vigencia', '=', $vigencia)
					->where('actual', '=', 1)//1 actual
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

		if(count($etapas) > 0)
		{
			$idEtapa = $etapas->Etapa_idEtapa;
		}
		else
		{
			$idEtapa = 0;
		}

		$tiposPlantillas =  TipoPlantilla::all();

		return View::make('plantillas.ajaxBuscarRadicadoPlantillas')
				   ->with('tiposPlantillas', $tiposPlantillas)
				   ->with('idEtapa', $idEtapa)
		 		   ->with('proceso', $proceso);
	}

	public function actionCargarPlantillas()
	{
		$plantillas = DB::table('plantilla')
						  ->where('TipoPlantilla_idTipoPlantilla', '=', Input::get('idTipoPlantilla'))
						  ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
						  ->get();

		return View::make('plantillas.ajaxCargarPlantillas')		
  			  	   ->with('vigencia', Input::get('vigencia'))
				   ->with('idRadicado', Input::get('idRadicado'))
			       ->with('plantillas', $plantillas);
	}

	public function actionPortada($vigencia, $idRadicado)
	{
		$templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/portadas/portadaProceso.docx');

		//Datos plantilla
		$radicado =  $vigencia."-".$idRadicado;
		$presuntoResponsable = Util::traerPresuntosResponsablesPortada($vigencia, $idRadicado);
		$quejoso = Util::traerQuejososPortada($vigencia, $idRadicado);
		//------------------------------------------------------------------------------

		// --- Asignamos valores a la plantilla
		$templateWord->setValue('radicado', $radicado);
		$templateWord->setValue('presuntoResponsable', $presuntoResponsable);
		$templateWord->setValue('quejoso', $quejoso);

		// --- Guardamos el documento
		$templateWord->saveAs('Portada proceso '.$radicado.'.docx');

		header("Content-Disposition: attachment; filename=Portada proceso ".$radicado.".docx; charset=iso-8859-1");
		echo file_get_contents('Portada proceso '.$radicado.'.docx');

		Unlink('Portada proceso '.$radicado.'.docx');
	}


	public function actionGuardarOficio($vector)
	{
		$datos = json_decode($vector, true);

		//echo $datos['ciudad'];

		//return;

		$idUsuario = Session::get('documentoUsuario');

		//Evalúa el tipo de plantilla
		switch ($datos['idTipoPlantilla'])
		{
			case '1':
				$idEstado = 18;//18 Genera citación
				$idTipoArchivo = 5;//5 citación
				break;
			case '2':
				$idEstado = 15;//15 Genera comunicación
				$idTipoArchivo = 2;//2 comunicación
				break;
			case '3':
				$idEstado = 21;//21 Genera solicitud
				$idTipoArchivo = 7;//7 solicitud
				break;
		}

		//Busca la plantilla y trae el nombre-------------------
		$plantilla = Plantilla::find($datos['idPlantilla']);
		$nombrePlantilla = $plantilla->nombrePlantilla;
		//------------------------------------------------------

		//Número del radicado del proceso
		$radicado = $datos['vigencia']."-".$datos['idRadicado'];
		//Nombre del abogado del proceso
		$abogado = Util::traerNombreAbogado($datos['vigencia'], $datos['idRadicado']);

		//Nombre de la ciudad --------------------
		$ciudad = Ciudad::find($datos['ciudad']);
		$nombreCiudad = $ciudad->nombreCiudad;
		//----------------------------------------

		//------- Guarda el archivo generado
        $arg = new ArchivoGenerado;
        $arg->Radicado_idRadicado = $datos['idRadicado'];
        $arg->Radicado_vigencia = $datos['vigencia'];
        $arg->Etapa_idEtapa = $plantilla->Etapa_idEtapa;
        $arg->TipoArchivo_idTipoArchivo = $idTipoArchivo;
        $arg->Persona_documentoPersona = $idUsuario;
        $arg->nombreArchivoGenerado = $nombrePlantilla.' | '.$radicado;
        $arg->fechaArchivoGenerado = date('Y-m-d');
        $arg->horaArchivoGenerado = date('g:i a');
        $arg->subido = "NO";
        $arg->save();
        //-----# Guarda el archivo generado

		$valores = Util::almacenarOficio($datos['destinatario'], $datos['entidad'], $datos['direccion'], $datos['ciudad'], $datos['asunto'], " Exp. ".$radicado);
		$numeroCdi = $valores[0];
		$numeroArco = $valores[1];

        $observacionRadicado  = new ObservacionRadicado;
        $observacionRadicado->EstadoRadicado_idEstadoRadicado = $idEstado;
        $observacionRadicado->Radicado_idRadicado = $datos['idRadicado'];
        $observacionRadicado->Radicado_vigencia = $datos['vigencia'];
        $observacionRadicado->Persona_documentoPersona = $idUsuario;
        $observacionRadicado->observacion = "Se genera oficio ".$numeroCdi." con destino a ".$datos['destinatario'].". Radicado arco: ".$numeroArco;
        $observacionRadicado->fechaObservacion = date('Y-m-d');
        $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
        $observacionRadicado->save();

        //********** PLANTILLA WORD ***************************
        $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/'.$datos['idPlantilla'].'.docx');

        // --- Asignamos valores a la plantilla
		$templateWord->setValue('numeroOficio', $numeroCdi." Exp. ".$radicado);
		$templateWord->setValue('numeroArco', $numeroArco);
		$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));
		$templateWord->setValue('destinatario', $datos['destinatario']);
		$templateWord->setValue('direccion', $datos['direccion']);
		$templateWord->setValue('ciudad', $nombreCiudad);
		$templateWord->setValue('asunto', $datos['asunto']);
		$templateWord->setValue('radicado', $radicado);
		$templateWord->setValue('abogado', $abogado);

		// --- Se guarda el documento
		$templateWord->saveAs($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

		header("Content-Disposition: attachment; filename=".$arg->idArchivoGenerado."_".$nombrePlantilla." ".$radicado.".docx; charset=iso-8859-1");
		echo file_get_contents($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		//Elimina el archivo temporal
		Unlink($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		//********** # PLANTILLA WORD ****************************/
	}

	public function actionGuardarOficioGeneral($vector)
	{
		$datos = json_decode($vector, true);

		$documentoUsuario = Session::get('documentoUsuario');

		//Nombre de la ciudad --------------------
		$ciudad = Ciudad::find($datos['ciudad']);
		$nombreCiudad = $ciudad->nombreCiudad;
		//----------------------------------------

		$valores = Util::almacenarOficio($datos['destinatario'], $datos['entidad'], $datos['direccion'], $datos['ciudad'], $datos['asunto'], "");
		$numeroCdi = $valores[0];
		$numeroArco = $valores[1];
		$usuario = Util::traerDatosUsuario($documentoUsuario);		

        //********** PLANTILLA WORD ***************************
        $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/oficio general.docx');

        // --- Asignamos valores a la plantilla
		$templateWord->setValue('numeroOficio', $numeroCdi);
		$templateWord->setValue('numeroArco', $numeroArco);
		$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));
		$templateWord->setValue('destinatario', $datos['destinatario']);
		$templateWord->setValue('direccion', $datos['direccion']);
		$templateWord->setValue('ciudad', $nombreCiudad);
		$templateWord->setValue('asunto', $datos['asunto']);
		$templateWord->setValue('remitente', $usuario->nombre);
		$templateWord->setValue('cargo', $usuario->nombreCargo);
		$numeroCdi = str_replace("/", "-", $valores[0]);

		// --- Se guarda el documento
		$templateWord->saveAs($numeroCdi." ".$numeroArco.'.docx');

		header("Content-Disposition: attachment; filename=".$numeroCdi." ".$numeroArco.".docx; charset=iso-8859-1");
		echo file_get_contents($numeroCdi." ".$numeroArco.'.docx');
		//Elimina el archivo temporal
		Unlink($numeroCdi." ".$numeroArco.'.docx');
		//********** # PLANTILLA WORD ****************************/
	}
	
	public function actionRemitirPorCompetencia($vector)
	{
		$datos = json_decode($vector, true);
		$idUsuario = Session::get('documentoUsuario');
		$fechaHoy = date("Y-m-d");

		//Consulta la última remisión
		$remisionCompetencia = DB::table('remisioncompetencia')
		                         ->where('vigenciaRemision', '=', date('Y'))
		                           ->max('idRemisionCompetencia');
		//Construye el asunto
		$asunto = "Remisión por competencia N° RXC ".$remisionCompetencia."/".date('Y')." -  Proceso ".$datos['vigencia']."-".$datos['idRadicado'];
		
		//Trae las quejas del proceso
		$quejasProceso = Util::traerQuejasProceso($datos['vigencia'], $datos['idRadicado']);
				
		if (count($quejasProceso) > 0) 
		{
			foreach ($quejasProceso as $quejaProceso) 
			{
				$observacion = "Se remite por competencia la queja ".$quejaProceso." a ".strtoupper($datos['destinatario'])." - ".$datos['entidad']." por el siguiente motivo: ".$datos['motivo'];

				//Actualiza el estado de la queja
				DB::table('queja')
				  ->where('idQueja', $quejaProceso)
				 ->update(['EstadoQueja_idEstadoQueja' => 7]);// Estado 7 (Queja repartida y luego remitida por competencia)

				//Almacena ObservacionesQueja
				$observacionQueja = new ObservacionQueja;
				$observacionQueja->EstadoQueja_idEstadoQueja = 7;// Estado 7 (Queja repartida y luego remitida por competencia)
				$observacionQueja->Queja_idQueja = $quejaProceso;
				$observacionQueja->Persona_documentoPersona = $idUsuario;
				$observacionQueja->observacion = $observacion;
				$observacionQueja->fechaObservacion = $fechaHoy;// Fecha actual
				$observacionQueja->horaObservacion = date('g:i a'); // Hora actual
				$observacionQueja->save();				

				//Guarda el oficio y obtiene los radicados
				$valores = Util::almacenarOficio(strtoupper($datos['destinatario']), $datos['entidad'], $datos['direccion'], $datos['ciudad'], $asunto, 'Exp. '.$datos['vigencia']."-".$datos['idRadicado']);
				$numeroCdi = $valores[0];
				$numeroArco = $valores[1];

				//Guarda la remisión
				$remisionCompetencia = new RemisionCompetencia;
				$remisionCompetencia->vigenciaRemision = date("Y");
				$remisionCompetencia->Queja_idQueja = $quejaProceso;
				$remisionCompetencia->TipoRemision_idTipoRemision = $datos['tipoRemision'];
				$remisionCompetencia->motivoRemisionCompetencia = $datos['motivo'];
				$remisionCompetencia->fechaRemisionCompetencia = $fechaHoy;
				$remisionCompetencia->oficioRemisionCompetencia = $numeroCdi." ARCO ".$numeroArco;
				$remisionCompetencia->save();
			}
		} 

		$observacionRad = $asunto.". Se remite por competencia el proceso ".$datos['vigencia']."-".$datos['idRadicado']." a ".strtoupper($datos['destinatario'])." - ".$datos['entidad']." por el siguiente motivo: ".$datos['motivo'];

		//Guardado en el radicado
		$observacionRadicado = new ObservacionRadicado;
		$observacionRadicado->EstadoRadicado_idEstadoRadicado = 4; //(Remisión por Competencia)
		$observacionRadicado->Radicado_idRadicado = $datos['idRadicado'];
		$observacionRadicado->Radicado_vigencia = $datos['vigencia']; // Almacena el año actual
		$observacionRadicado->Persona_documentoPersona = Session::get('documentoUsuario');
		$observacionRadicado->observacion = $observacionRad;
		$observacionRadicado->fechaObservacion = date("Y-m-d");// Fecha actual
		$observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
		$observacionRadicado->save();

		//Estado y etapa del radicado 
		DB::table('radicado')
		  ->where('idRadicado', $datos['idRadicado'])
	      ->where('vigencia', $datos['vigencia'])
	     ->update(['EstadoRadicado_idEstadoRadicado' => 4, 'Etapa_idEtapa' => 19, 'activo' => 0, 'fechaFinalizado' => date('Y-m-d')]);	

		 DB::table('etapasproceso')
  		   ->where('Radicado_idRadicado', $datos['idRadicado'])
		   ->where('Radicado_vigencia', $datos['vigencia'])
	 	  ->update(['actual' => 0]);// (Actual = 0)

		 //Agrega la nueva etapa
		 $etapaProceso = new EtapaProceso;
		 $etapaProceso->Radicado_idRadicado = $datos['idRadicado'];
		 $etapaProceso->Radicado_vigencia = $datos['vigencia'];
		 $etapaProceso->Etapa_idEtapa = 19;// 19 Remisión por competencia
		 $etapaProceso->fechaEtapa = date('Y-m-d');// Fecha actual
		 $etapaProceso->observacion = $observacionRad;
		 $etapaProceso->actual = 1;
		 $etapaProceso->fechaFinalEtapa = date('Y-m-d');
		 $etapaProceso->save();	

		  /*
		//Agrega la nueva etapa
		$etapaProceso = new EtapaProceso;
		$etapaProceso->Radicado_idRadicado = $datos['idRadicado'];
		$etapaProceso->Radicado_vigencia = $datos['vigencia'];
		$etapaProceso->Etapa_idEtapa = 14;// 14 Proceso finalizado
		$etapaProceso->fechaEtapa = date('Y-m-d');// Fecha actual
		$etapaProceso->observacion = $observacionRad;
		$etapaProceso->actual = 1;
		$etapaProceso->fechaFinalEtapa = date('Y-m-d');
		$etapaProceso->save();
		*/

		//Evalúa el tipo de plantilla
		switch ($datos['tipoRemision'])
		{
			case '1':
				$nombrePlantilla = "Remision por Competencia - Entidad";
				break;
			case '2':
				$nombrePlantilla = "Remision por Competencia - Comite Convivencia Laboral";
				break;
			case '3':
				$nombrePlantilla = "Remision por Competencia - Devolucion";
				break;
		}

		//Nombre de la ciudad --------------------
		$ciudad = Ciudad::find($datos['ciudad']);
		$nombreCiudad = $ciudad->nombreCiudad;
		//----------------------------------------

		//Nombre del remitente ----------------------------
		$director = Util::traerNombreDirector();
		
        //********** PLANTILLA WORD ***************************
        $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/remisiones/'.$nombrePlantilla.'.docx');

        // --- Asignamos valores a la plantilla
		$templateWord->setValue('numeroOficio', $numeroCdi);
		$templateWord->setValue('numeroArco', $numeroArco);
		$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));
		$templateWord->setValue('destinatario', strtoupper($datos['destinatario']));
		$templateWord->setValue('direccion', $datos['direccion']);
		$templateWord->setValue('ciudad', $nombreCiudad);
		$templateWord->setValue('asunto', $asunto);
		$templateWord->setValue('director', $director);

		// --- Se guarda el documento
		$templateWord->saveAs($nombrePlantilla.' '.$datos['vigencia']."-".$datos['idRadicado'].'.docx');

		header("Content-Disposition: attachment; filename=".$nombrePlantilla." ".$datos['vigencia']."-".$datos['idRadicado'].".docx; charset=iso-8859-1");
		echo file_get_contents($nombrePlantilla.' '.$datos['vigencia']."-".$datos['idRadicado'].'.docx');
		//Elimina el archivo temporal
		Unlink($nombrePlantilla.' '.$datos['vigencia']."-".$datos['idRadicado'].'.docx');
		//********** # PLANTILLA WORD ****************************/
	}

	public function actionGuardarOficioRemision($vector)
	{
		$datos     = json_decode($vector, true);
		$idUsuario = Session::get('documentoUsuario');
		$fechaHoy  = date("Y-m-d");

		// Actualiza el estado de la queja
		DB::table('queja')
			->where('idQueja', $datos['idQueja'])
			->update(['EstadoQueja_idEstadoQueja' => 4]); // 4 Queja remitida por competencia

		// Almacena ObservacionesQueja
		$observacionQueja = new ObservacionQueja;
		$observacionQueja->EstadoQueja_idEstadoQueja = 4;
		$observacionQueja->Queja_idQueja             = $datos['idQueja'];
		$observacionQueja->Persona_documentoPersona  = $idUsuario;
		$observacionQueja->observacion               =
			"Se remite por competencia la queja ".$datos['idQueja']." a ".$datos['destinatario'].
			" en la entidad: ".$datos['entidad'];
		$observacionQueja->fechaObservacion          = $fechaHoy;
		$observacionQueja->horaObservacion           = date('g:i a');
		$observacionQueja->save();

		// Tipo de plantilla
		switch ($datos['tipoRemision']) {
			case '1':
				$nombrePlantilla = "Remision por Competencia - Entidad";
				break;
			case '2':
				$nombrePlantilla = "Remision por Competencia - Comite Convivencia Laboral";
				break;
			case '3':
				$nombrePlantilla = "Remision por Competencia - Devolucion";
				break;
			default:
				$nombrePlantilla = "Remision por Competencia";
		}

		// Ciudad
		$ciudad       = Ciudad::find($datos['ciudad']);
		$nombreCiudad = $ciudad->nombreCiudad;

		// Director
		$director = Util::traerNombreDirector();

		// Asunto
		$remisionCompetencia = DB::table('remisioncompetencia')
			->where('vigenciaRemision', '=', date('Y'))
			->max('idRemisionCompetencia');

		$asunto = "Remisión RXC - ".$remisionCompetencia."/".date('Y').
				" ".$datos['origenQueja']." ".$datos['idQueja'];

		$valores    = Util::almacenarOficio(
			$datos['destinatario'],
			$datos['entidad'],
			$datos['direccion'],
			$datos['ciudad'],
			$asunto,
			$datos['origenQueja']." ".$datos['idQueja']
		);
		$numeroCdi  = $valores[0];
		$numeroArco = $valores[1];

		$remision = new RemisionCompetencia;
		$remision->vigenciaRemision                 = date("Y");
		$remision->Queja_idQueja                    = $datos['idQueja'];
		$remision->TipoRemision_idTipoRemision      = $datos['tipoRemision'];
		$remision->EntidadRemision_idEntidadRemision= $datos['idEntidadSeleccionada'];
		$remision->motivoRemisionCompetencia        = $datos['motivo'];
		$remision->fechaRemisionCompetencia         = $fechaHoy;
		$remision->oficioRemisionCompetencia        = $numeroCdi." ARCO ".$numeroArco;
		$remision->save();

		// -------- PLANTILLA WORD --------
		$templatePath = 'plantillas/remisiones/'.$nombrePlantilla.'.docx';
		$templateWord = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

		$escapar = function ($str) {
			return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
		};

		$templateWord->setValue('numeroOficio', $numeroCdi);
		$templateWord->setValue('numeroArco',   $numeroArco);
		$templateWord->setValue('fechaHoy',     Util::formatearFecha(date('Y-m-d')));
		$templateWord->setValue('destinatario', $escapar($datos['destinatario']));
		$templateWord->setValue('direccion',    $escapar($datos['direccion']));
		$templateWord->setValue('ciudad',       $escapar($nombreCiudad));
		$templateWord->setValue('asunto',       $escapar($asunto));
		$templateWord->setValue('director',     $escapar($director)); // evita que & y otros rompan el XML[web:107][web:113]

		// Guardar en archivo temporal
		$tempFile = tempnam(sys_get_temp_dir(), 'remision_');
		$templateWord->saveAs($tempFile); // DOCX válido en disco[web:33]

		// Limpiar salida previa
		if (ob_get_length()) {
			ob_end_clean();
		}

		$downloadName = $nombrePlantilla.' '.$datos['idQueja'].'.docx';

		// Cabeceras correctas para DOCX
		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Disposition: attachment; filename="'.$downloadName.'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.filesize($tempFile));

		// Enviar archivo y eliminar temporal
		readfile($tempFile); // salida binaria segura[web:27]
		unlink($tempFile);
		exit;
	}

	public function actionGuardarOficioRemision_OLD($vector)
	{
		$datos = json_decode($vector, true);

		$idUsuario = Session::get('documentoUsuario');
		$fechaHoy = date("Y-m-d");

		//Actualiza el estado de la queja
		DB::table('queja')
          ->where('idQueja', $datos['idQueja'])
          ->update(['EstadoQueja_idEstadoQueja' => 4]);// Estado 4 (Queja remitida por competencia)
		//------------------------------------------------

        //Almacena ObservacionesQueja
        $observacionQueja = new ObservacionQueja;
        $observacionQueja->EstadoQueja_idEstadoQueja = 4;// 4 Queja Remitida por Competencia
        $observacionQueja->Queja_idQueja = $datos['idQueja'];
        $observacionQueja->Persona_documentoPersona = $idUsuario;
        $observacionQueja->observacion = "Se remite por competencia la queja ".$datos['idQueja']." a ".$datos['destinatario']." en la entidad: ".$datos['entidad'];
        $observacionQueja->fechaObservacion = $fechaHoy;// Fecha actual
        $observacionQueja->horaObservacion = date('g:i a'); // Hora actual
        $observacionQueja->save();

		//Evalúa el tipo de plantilla
		switch ($datos['tipoRemision'])
		{
			case '1':
				$nombrePlantilla = "Remision por Competencia - Entidad";
				break;
			case '2':
				$nombrePlantilla = "Remision por Competencia - Comite Convivencia Laboral";
				break;
			case '3':
				$nombrePlantilla = "Remision por Competencia - Devolucion";
				break;
		}

		//Nombre de la ciudad --------------------
		$ciudad = Ciudad::find($datos['ciudad']);
		$nombreCiudad = $ciudad->nombreCiudad;
		//----------------------------------------

		//Nombre del remitente ----------------------------
		$director = Util::traerNombreDirector();
		//---------------------------------------------------

		//Asunto
		$remisionCompetencia = DB::table('remisioncompetencia')
						  ->where('vigenciaRemision', '=', date('Y'))
						  ->max('idRemisionCompetencia');

		$asunto = "Remisión RXC - ".$remisionCompetencia."/".date('Y')." ".$datos['origenQueja']." ".$datos['idQueja'];
		//---------------------------------------------------------------------------------------------

		$valores = Util::almacenarOficio($datos['destinatario'], $datos['entidad'], $datos['direccion'], $datos['ciudad'], $asunto, $datos['origenQueja']." ".$datos['idQueja']);
		$numeroCdi = $valores[0];
		$numeroArco = $valores[1];

		$remisionCompetencia = new RemisionCompetencia;
        $remisionCompetencia->vigenciaRemision = date("Y");
        $remisionCompetencia->Queja_idQueja = $datos['idQueja'];
        $remisionCompetencia->TipoRemision_idTipoRemision = $datos['tipoRemision'];
        $remisionCompetencia->EntidadRemision_idEntidadRemision = $datos['idEntidadSeleccionada'];
        $remisionCompetencia->motivoRemisionCompetencia = $datos['motivo'];
        $remisionCompetencia->fechaRemisionCompetencia = $fechaHoy;
        $remisionCompetencia->oficioRemisionCompetencia = $numeroCdi." ARCO ".$numeroArco;
        $remisionCompetencia->save();

        //********** PLANTILLA WORD ***************************
        $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/remisiones/'.$nombrePlantilla.'.docx');

		$escapar = function ($str) {
			return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
		};

        // --- Asignamos valores a la plantilla
		$templateWord->setValue('numeroOficio', $numeroCdi);
		$templateWord->setValue('numeroArco', $numeroArco);
		$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));		
		$templateWord->setValue('destinatario', $escapar($datos['destinatario']));
		$templateWord->setValue('direccion',   $escapar($datos['direccion'])); // aquí va "Cll 21 21-49 Ed. Millán & Asociados"
		$templateWord->setValue('ciudad',      $escapar($nombreCiudad));
		$templateWord->setValue('asunto',      $escapar($asunto));
		$templateWord->setValue('director',    $escapar($director));

		// --- Se guarda el documento
		$templateWord->saveAs($nombrePlantilla.' '.$datos["idQueja"].'.docx');

		header("Content-Disposition: attachment; filename=".$nombrePlantilla." ".$datos["idQueja"].".docx; charset=iso-8859-1");
		echo file_get_contents($nombrePlantilla.' '.$datos["idQueja"].'.docx');
		//Elimina el archivo temporal
		Unlink($nombrePlantilla.' '.$datos["idQueja"].'.docx');
		//********** # PLANTILLA WORD ****************************/
	}

	public function actionPlantilla($vigencia, $idRadicado, $idPlantilla, $idTipoPlantilla)
	{
		$idUsuario = Session::get('documentoUsuario');

		$templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/'.$idPlantilla.'.docx');

		//Busca la plantilla
		$plantilla = Plantilla::find($idPlantilla);
		//Trae el nombre de la plantilla
		$nombrePlantilla = $plantilla->nombrePlantilla;

		//Busca la etapa
		$etapa = Etapa::find($plantilla->Etapa_idEtapa);
		//Trae el nombre de la etapa
		$nombreEtapa = $etapa->nombreEtapa;

		//4 Auto - Si la plantilla seleccionada es de tipo Auto
		if($idTipoPlantilla == 4)
		{
			//Asunto Auto
			switch ($plantilla->Etapa_idEtapa)
			{
				case '1':
					$asunto = "Por medio del cual se inicia una Indagación Preliminar";
					break;
				case '2':
					$asunto = "Por medio del cual se apertura una Investigación Disciplinaria";
					break;
				case '5':
					$asunto = "Por medio del cual se formula Pliego de Cargos";
					break;
				case '8':
					$asunto = "Por medio del cual se profiere un fallo sancionatorio en primera instancia";
					break;
				case '9':
					$asunto = "Por medio del cual se inhibe de iniciar una accion disciplinaria";
					break;
				case '10':
					$asunto = "Por medio del cual se archiva un Proceso Disciplinario de forma definitiva";
					break;
				default:
					$asunto = "-";
					break;
			}

			//Datos cabezote ----------------------------------------------------------------------
			$radicado =  $vigencia."-".$idRadicado;
			$presuntoResponsable = Util::traerPresuntosResponsablesPortada($vigencia, $idRadicado);
			$cargo = Util::traerCargoPresuntoResponsablePortada($vigencia, $idRadicado);
			$dependencia = Util::traerDependenciaPresuntoResponsablePortada($vigencia, $idRadicado);
			$quejoso = Util::traerQuejososPortada($vigencia, $idRadicado);
			$documentoQuejoso = Util::traerDocumentoQuejoso($vigencia, $idRadicado);
			$fechaQueja = Util::formatearFecha(Util::traerFechaQuejaPortada($vigencia, $idRadicado));
			$fechaRecepQueja = Util::formatearFecha(Util::traerFechaRecepQuejaPortada($vigencia, $idRadicado));
			//-------------------------------------------------------------------------------------

			$auto = Util::traerAutoEtapa($vigencia, $idRadicado, $plantilla->Etapa_idEtapa);
			$director = Util::traerNombreDirector();
			$abogado = Util::traerNombreAbogado($vigencia, $idRadicado);

			// --- Asignamos valores a la plantilla
			$templateWord->setValue('radicado', $radicado);
			$templateWord->setValue('presuntoResponsable', $presuntoResponsable);
			$templateWord->setValue('cargo', $cargo);
			$templateWord->setValue('dependencia', $dependencia);
			$templateWord->setValue('quejoso', $quejoso);
			$templateWord->setValue('fechaQueja', $fechaQueja);
			$templateWord->setValue('fechaRecepcionQueja', $fechaRecepQueja);
			$templateWord->setValue('documentoQuejoso', $documentoQuejoso);
			$templateWord->setValue('auto', $auto);
			$templateWord->setValue('asunto', strtoupper($asunto));
			$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));
			$templateWord->setValue('director', $director);
			$templateWord->setValue('abogado', $abogado);

			//Guarda el archivo generado
            $arg = new ArchivoGenerado;
            $arg->Radicado_idRadicado = $idRadicado;
            $arg->Radicado_vigencia = $vigencia;
            $arg->Etapa_idEtapa = $plantilla->Etapa_idEtapa; // 1 Etapa INDAGACIÓN PRELIMINAR
            $arg->TipoArchivo_idTipoArchivo = 1; // 1 tipo de archivo AUTO
            $arg->Persona_documentoPersona = $idUsuario;
            $arg->nombreArchivoGenerado = $nombrePlantilla.' '.$radicado;
            $arg->fechaArchivoGenerado = date('Y-m-d');
            $arg->horaArchivoGenerado = date('g:i a');
            $arg->subido = "NO";
            $arg->save();
            //# Guarda el archivo generado

            //Almacena Estado:  (GENERA MINUTA AUTO)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 14;//GENERA MINUTA AUTO - IND. PRELIMINAR
            $observacionRadicado->Radicado_idRadicado = $idRadicado;
            $observacionRadicado->Radicado_vigencia = $vigencia;
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se generó minuta del Auto de apertura de INDAGACIÓN PRELIMINAR Número: ".$auto." ".$asunto.".";
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();
            //# //Almacena Estado:  (GENERA MINUTA AUTO - IND. PRELIMINAR)

			// --- Guardamos el documento
			$templateWord->saveAs($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			header("Content-Disposition: attachment; filename=".$arg->idArchivoGenerado."_".$nombrePlantilla." ".$radicado.".docx; charset=iso-8859-1");
			echo file_get_contents($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			Unlink($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		}//# Si la plantilla seleccionada es de tipo Auto

		//5 Diligencia - Si la plantilla seleccionada es de tipo diligencia
		else if($idTipoPlantilla == 5)
		{
			$radicado =  $vigencia."-".$idRadicado;
			$abogado = Util::traerNombreAbogado($vigencia, $idRadicado);

			// --- Asignamos valores a la plantilla
			$templateWord->setValue('radicado', $radicado);
			$templateWord->setValue('abogado', $abogado);

			//Guarda el archivo generado
            $arg = new ArchivoGenerado;
            $arg->Radicado_idRadicado = $idRadicado;
            $arg->Radicado_vigencia = $vigencia;
            $arg->Etapa_idEtapa = $plantilla->Etapa_idEtapa; // 1 Etapa INDAGACIÓN PRELIMINAR
            $arg->TipoArchivo_idTipoArchivo = 5; // 5 tipo de archivo DILIGENCIA
            $arg->Persona_documentoPersona = $idUsuario;
            $arg->nombreArchivoGenerado = $nombrePlantilla.' '.$radicado;
            $arg->fechaArchivoGenerado = date('Y-m-d');
            $arg->horaArchivoGenerado = date('g:i a');
            $arg->subido = "NO";
            $arg->save();
            //# Guarda el archivo generado

            //Almacena Estado:  (GENERA DILIGENCIA)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 19;//GENERA DILIGENCIA
            $observacionRadicado->Radicado_idRadicado = $idRadicado;
            $observacionRadicado->Radicado_vigencia = $vigencia;
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se generó diligencia";
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();
            //# //Almacena Estado:  (GENERA MINUTA AUTO - IND. PRELIMINAR)

			// --- Guardamos el documento
			$templateWord->saveAs($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			header("Content-Disposition: attachment; filename=".$arg->idArchivoGenerado."_".$nombrePlantilla." ".$radicado.".docx; charset=iso-8859-1");
			echo file_get_contents($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			Unlink($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		}//# Si la plantilla seleccionada es de tipo Diligencia

		//6 Notificación - Si la plantilla seleccionada es de tipo notificación
		else if($idTipoPlantilla == 6)
		{
			$radicado =  $vigencia."-".$idRadicado;
			$abogado = Util::traerNombreAbogado($vigencia, $idRadicado);

			// --- Asignamos valores a la plantilla
			$templateWord->setValue('radicado', $radicado);
			$templateWord->setValue('abogado', $abogado);

			//Guarda el archivo generado
            $arg = new ArchivoGenerado;
            $arg->Radicado_idRadicado = $idRadicado;
            $arg->Radicado_vigencia = $vigencia;
            $arg->Etapa_idEtapa = $plantilla->Etapa_idEtapa;
            $arg->TipoArchivo_idTipoArchivo = 6; // 6 tipo de archivo NOTIFICACIÓN
            $arg->Persona_documentoPersona = $idUsuario;
            $arg->nombreArchivoGenerado = $nombrePlantilla.' '.$radicado;
            $arg->fechaArchivoGenerado = date('Y-m-d');
            $arg->horaArchivoGenerado = date('g:i a');
            $arg->subido = "NO";
            $arg->save();
            //# Guarda el archivo generado

            //Almacena Estado:  (GENERA NOTIFICACION)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 20;//GENERA NOTIFICACIÓN
            $observacionRadicado->Radicado_idRadicado = $idRadicado;
            $observacionRadicado->Radicado_vigencia = $vigencia;
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se generó notificación";
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();
            //# //Almacena Estado:  (GENERA MINUTA AUTO - IND. PRELIMINAR)

			// --- Guardamos el documento
			$templateWord->saveAs($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			header("Content-Disposition: attachment; filename=".$arg->idArchivoGenerado."_".$nombrePlantilla." ".$radicado.".docx; charset=iso-8859-1");
			echo file_get_contents($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			Unlink($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		}//# Si la plantilla seleccionada es de tipo Diligencia

		//7 Notificación - Si la plantilla seleccionada es de tipo acta
		else if($idTipoPlantilla == 7)
		{
			$radicado =  $vigencia."-".$idRadicado;
			$abogado = Util::traerNombreAbogado($vigencia, $idRadicado);
			$director = Util::traerNombreDirector();

			// --- Asignamos valores a la plantilla
			$templateWord->setValue('radicado', $radicado);
			$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));
			$templateWord->setValue('abogado', $abogado);
			$templateWord->setValue('director', $director);

			//Guarda el archivo generado
            $arg = new ArchivoGenerado;
            $arg->Radicado_idRadicado = $idRadicado;
            $arg->Radicado_vigencia = $vigencia;
            $arg->Etapa_idEtapa = $plantilla->Etapa_idEtapa;
            $arg->TipoArchivo_idTipoArchivo = 7; // 7 tipo de archivo ACTA
            $arg->Persona_documentoPersona = $idUsuario;
            $arg->nombreArchivoGenerado = $nombrePlantilla.' '.$radicado;
            $arg->fechaArchivoGenerado = date('Y-m-d');
            $arg->horaArchivoGenerado = date('g:i a');
            $arg->subido = "NO";
            $arg->save();
            //# Guarda el archivo generado

            //Almacena Estado:  (GENERA ACTA)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 56;//GENERA ACTA
            $observacionRadicado->Radicado_idRadicado = $idRadicado;
            $observacionRadicado->Radicado_vigencia = $vigencia;
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se generó acta";
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();
            //# //Almacena Estado:  (GENERA MINUTA AUTO - IND. PRELIMINAR)

			// --- Guardamos el documento
			$templateWord->saveAs($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			header("Content-Disposition: attachment; filename=".$arg->idArchivoGenerado."_".$nombrePlantilla." ".$radicado.".docx; charset=iso-8859-1");
			echo file_get_contents($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			Unlink($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		}//# Si la plantilla seleccionada es de tipo Diligencia

		//8 Constancia Secretarial - Si la plantilla seleccionada es de tipo Constancia Secretarial
		else if($idTipoPlantilla == 8)
		{
			$radicado =  $vigencia."-".$idRadicado;
			$abogado = Util::traerNombreAbogado($vigencia, $idRadicado);

			// --- Asignamos valores a la plantilla
			$templateWord->setValue('radicado', $radicado);
			$templateWord->setValue('fechaHoy', Util::formatearFecha(date('Y-m-d')));
			$templateWord->setValue('hora', date('g:i a'));
			$templateWord->setValue('abogado', $abogado);

			//Guarda el archivo generado
            $arg = new ArchivoGenerado;
            $arg->Radicado_idRadicado = $idRadicado;
            $arg->Radicado_vigencia = $vigencia;
            $arg->Etapa_idEtapa = $plantilla->Etapa_idEtapa;
            $arg->TipoArchivo_idTipoArchivo = 8; // 8 tipo de archivo CONSTANCIA SECRETARIAL
            $arg->Persona_documentoPersona = $idUsuario;
            $arg->nombreArchivoGenerado = $nombrePlantilla.' '.$radicado;
            $arg->fechaArchivoGenerado = date('Y-m-d');
            $arg->horaArchivoGenerado = date('g:i a');
            $arg->subido = "NO";
            $arg->save();
            //# Guarda el archivo generado

            //Almacena Estado:  (GENERA CONSTANCIA SECRETARIAL)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 44;//GENERA CONSTANCIA SECRETARIAL
            $observacionRadicado->Radicado_idRadicado = $idRadicado;
            $observacionRadicado->Radicado_vigencia = $vigencia;
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se generó constancia secretarial";
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();
            //# //Almacena Estado:  (GENERA MINUTA AUTO - IND. PRELIMINAR)

			// --- Guardamos el documento
			$templateWord->saveAs($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			header("Content-Disposition: attachment; filename=".$arg->idArchivoGenerado."_".$nombrePlantilla." ".$radicado.".docx; charset=iso-8859-1");
			echo file_get_contents($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');

			Unlink($arg->idArchivoGenerado."_".$nombrePlantilla.' '.$radicado.'.docx');
		}//# Si la plantilla seleccionada es de tipo Diligencia

	}

	public function actionSolicitarNumeroAuto()
	{
		$idUsuario = Session::get('documentoUsuario');
		$nombresUsuario = Session::get('nombresUsuario');

		$sa = new SolicitudAuto;
		$sa->fechaSolicitudAuto = date("Y-m-d H:i:s");
		$sa->Radicado_vigencia = Input::get("vigencia");
		$sa->Radicado_idRadicado = Input::get("idRadicado");
		$sa->Etapa_idEtapa = Input::get("idEtapa");
		$sa->Persona_documentoPersona = $idUsuario;
		$sa->observaciones = strtoupper(Input::get("observacion"));
		$sa->numAutoAsignado = 0;
		$sa->vigAutoAsignado = 0;
		$sa->asignado = 0;
		$sa->save();

		//REALTIME Autos Director ------------------------------------------------------------------------------
		$autos = DB::table('solicitudauto')
					->join('etapa', 'solicitudauto.Etapa_idEtapa', '=', 'etapa.idEtapa')
					->join('persona', 'solicitudauto.Persona_documentoPersona', '=', 'persona.documentoPersona')
					->where('asignado', '=', 0)
					->orderBy('idSolicitudAuto', 'desc')
					->get();

		//Vista 
		$vistaAutos = View::make('plantillas.ajaxSolicitudAutos')
						  ->with('autos', $autos)
						->render();

		return Response::json(array('vistaAutos' => $vistaAutos, 'nombresUsuario'  => $nombresUsuario));
	}

	public function actionCargarAutos() 
	{
		return View::make('plantillas.ajaxCargarAutos')
		   		   ->with('idRadicado', Input::get("idRadicado"))
				   ->with('vigencia', Input::get("vigencia"));
	}

	public function actionAutos()
	{
		$fases = array();

		//27 Ver autos fase de instrucción
		if(Util::verificarPermiso(27, Session::get('perfilUsuario'))) 
		{
			array_push($fases, 1);
		}

		//28 Ver autos fase de juzgamiento
		if(Util::verificarPermiso(28, Session::get('perfilUsuario'))) 
		{
			array_push($fases, 2);
		}

		$autos = DB::table('solicitudauto')
					->join('etapa', 'solicitudauto.Etapa_idEtapa', '=', 'etapa.idEtapa')
				->leftJoin('tiposetapa', 'etapa.tiposetapa_idTipoEtapa', '=', 'tiposetapa.idTipoEtapa')		
					->join('persona', 'solicitudauto.Persona_documentoPersona', '=', 'persona.documentoPersona')
				   ->where('asignado', 0)		
		         ->whereIn('tiposEtapa_idTipoEtapa', $fases)
		         ->orderBy('idSolicitudAuto', 'desc')
		             ->get();

		//Trae todos los abogados activos y los retorna en un array
	    $lista_abogados = DB::table('abogado')
	    					   ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    					   ->where('activo', '=', 1)
	   						   ->orderBy('nombre', 'asc')
	   						   ->lists('nombre','idAbogado');

		return View::make('procesos.autos')
  			  	   ->with('autos', $autos)
  			  	   ->with('lista_abogados', $lista_abogados)
  			  	   ->with('menuActivo', 'director');
	}	

	public function actionTraslados()
	{
		//Trae todos los abogados activos y los retorna en un array
	    $lista_abogados = DB::table('abogado')
	    					   ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    					   ->where('activo', '=', 1)
	   						   ->orderBy('nombre', 'asc')
	   						   ->lists('nombre','idAbogado');

		return View::make('procesos.traslados')
				   ->with('lista_abogados', $lista_abogados)
				   ->with('menuActivo', 'director');
	}

	public function actionProcesosAbogado()
	{
		$procesos = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  //->where('radicado.activo', '=', 1)
				  ->where('abogado.idAbogado', '=', Input::get('idAbogado'))
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return View::make('plantillas.ajaxProcesosAbogado')
			           ->with('procesos', $procesos)
			           ->with('idAbogado', Input::get('idAbogado'));
	}

	public function actionCargarNumeracion() 
	{
		$etapasInstruccion = DB::table('etapa')
					->where('fase', 'like', '%1%')
					->where('auto', 1)
  				  ->orderBy('nombreEtapa', 'asc')
					  ->get();

					  
		$etapasJuzgamiento = DB::table('etapa')
							   ->where('fase', 'like', '%2%')
							   ->where('auto', 1)
							 ->orderBy('nombreEtapa', 'asc')
							     ->get();

		return View::make('procesos.numeracion')
			       ->with('etapasInstruccion', $etapasInstruccion)
			       ->with('etapasJuzgamiento', $etapasJuzgamiento);
	}

	public function actionTrasladarProcesos()
	{
		$numeros = json_decode(Input::get('jsonSeleccionados'), true);


		for ($i = 0; $i < count($numeros); $i++)
		{
		    list($vigencia, $idRadicado) = explode("-", $numeros[$i]);

	      	//Actualiza los abogados asignados previamente para que el estado actual sea "NO"
	    	DB::table('abogadoasignado')
	    	  ->where('Radicado_vigencia', $vigencia)
              ->where('Radicado_idRadicado', $idRadicado)
              ->update(['actual' => "NO"]);

            $abogadoAsignado = new AbogadoAsignado;
            $abogadoAsignado->Radicado_idRadicado = $idRadicado;
            $abogadoAsignado->Radicado_vigencia = $vigencia;
            $abogadoAsignado->Abogado_idAbogado = Input::get('idAbogadoDestino');
            $abogadoAsignado->fechaAsignacion = date("Y-m-d");// Fecha actual
            $abogadoAsignado->observacion = "Se reasignó al profesional ".Util::traerNombreAbogadoId(Input::get('idAbogadoDestino'))." por el siguiente motivo: ".Input::get('motivo');
            $abogadoAsignado->actual = "SI";
            $abogadoAsignado->save();

           //Almacena ObservacionesRadicado -- Estado: (Radicado asignado a Abogado)
            $observacionRadicado = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 36; //(CAMBIO DE PROFESIONAL UNIVERSITARIO ASIGNADO A PROCESO)
            $observacionRadicado->Radicado_idRadicado = $idRadicado;
            $observacionRadicado->Radicado_vigencia = $vigencia; // Almacena el año actual
            $observacionRadicado->Persona_documentoPersona = Session::get('documentoUsuario');
            $observacionRadicado->observacion = "Se reasignó al profesional ".Util::traerNombreAbogadoId(Input::get('idAbogadoDestino'))." por el siguiente motivo: ".Input::get('motivo');
            $observacionRadicado->fechaObservacion = date("Y-m-d");// Fecha actual
            $observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
            $observacionRadicado->save();
	    }

	  	$procesos = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('radicado.activo', '=', 1)
				  ->where('abogado.idAbogado', '=', Input::get('idAbogadoOrigen'))
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return View::make('plantillas.ajaxProcesosAbogado')
			           ->with('procesos', $procesos)
			           ->with('idAbogado', Input::get('idAbogadoOrigen'));
	}

	public function actionVerificarNumeroAuto()
	{
		$ultimoAuto = DB::table('auto')
						->where('vigenciaAuto', '=', date('Y'))
						->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
						->max('idAuto');

		return $ultimoAuto + 1;
	}

	public function actionVerificarGuardarNumeroAuto()
	{
		$idUsuario = Session::get('documentoUsuario');
		
		$solicitudAuto = DB::table('solicitudauto')
						  ->select('observaciones')
					   	   ->where('idSolicitudAuto', Input::get('idSolicitudAuto'))
						   ->first();
							
		$observacion = $solicitudAuto->observaciones;
		$nombreEtapa = Util::traerNombreEtapaId(Input::get("idEtapa"));

		//Verifica si ya se asignó un auto para la etapa solicitada
		/*
		$autoProceso = DB::table('auto')
				    ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
				    ->where('Radicado_vigencia', '=', Input::get('vigencia'))
				    ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
					->get();

		*/

		$auto = new Auto;
		$auto->vigenciaAuto = date('Y');
		$auto->Etapa_idEtapa = Input::get('idEtapa');
		$auto->Radicado_idRadicado = Input::get('idRadicado');
		$auto->Radicado_vigencia = Input::get('vigencia');
		$auto->observacionAuto = $observacion;
		$auto->fechaAuto = date('Y-m-d');
		$auto->horaAuto = date('g:i a');
		$auto->apertura = 1;
		$auto->save();

		//Almacena ObservacionesRadicado -- Estado:  (Radicado Generado)
		$observacionRadicado  = new ObservacionRadicado;
		$observacionRadicado->EstadoRadicado_idEstadoRadicado = 6; //Asigna número de auto
		$observacionRadicado->Radicado_idRadicado = Input::get('idRadicado');
		$observacionRadicado->Radicado_vigencia = Input::get('vigencia');
		$observacionRadicado->Persona_documentoPersona = $idUsuario;
		$observacionRadicado->observacion = 'Se asignó número de auto '.$auto->idAuto.' "'.$observacion.'" de fecha '.Util::formatearFecha(date('Y-m-d')).' para la etapa de '.$nombreEtapa;
		$observacionRadicado->fechaObservacion = date('Y-m-d');
		$observacionRadicado->horaObservacion = date('g:i a');// Hora actual
		$observacionRadicado->save();

		//Cambia el estado asignado de la solicitud
		DB::table('solicitudauto')
			->where('idSolicitudAuto', Input::get('idSolicitudAuto'))
			->update(['numAutoAsignado' => $auto->idAuto,
					'vigAutoAsignado' => date('Y'),
					'asignado' => 1,
					'fechaAsignacionAuto' => date('Y-m-d H:i:s')]);
		//--------------------------------------------------

		$autos = DB::table('solicitudauto')
					->join('etapa', 'solicitudauto.Etapa_idEtapa', '=', 'etapa.idEtapa')
					->join('persona', 'solicitudauto.Persona_documentoPersona', '=', 'persona.documentoPersona')
					->where('asignado', '=', 0)
					->orderBy('idSolicitudAuto', 'desc')
					->get();

		return View::make('plantillas.ajaxSolicitudAutos')
					->with('autos', $autos);
	}

	public function actionEliminarSolicitud()
	{
		DB::table('solicitudauto')
		  ->where('idSolicitudAuto', Input::get('idSolicitudAuto'))
	  	 ->delete();

		$autos = DB::table('solicitudauto')
					->join('etapa', 'solicitudauto.Etapa_idEtapa', '=', 'etapa.idEtapa')
					->join('persona', 'solicitudauto.Persona_documentoPersona', '=', 'persona.documentoPersona')
		   		   ->where('asignado', '=', 0)
				 ->orderBy('idSolicitudAuto', 'desc')
					 ->get();

		return View::make('plantillas.ajaxSolicitudAutos')
		           ->with('autos', $autos);
	}

	public function actionVerificarGuardarNumeroAutoAntes()
	{
		$idUsuario = Session::get('documentoUsuario');

		//Verifica si ya se asignó un auto para la etapa solicitada
		$autoProceso = DB::table('auto')
				    ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
				    ->where('Radicado_vigencia', '=', Input::get('vigenciaAntes'))
				    ->where('Radicado_idRadicado', '=', Input::get('radicadoAntes'))
					->get();

		if(count($autoProceso) > 0)
		{
			$error = 1;//1 Ya existe el auto
			return $error;
		}
		else
		{
			switch (Input::get("idEtapa"))
			{
				case '1':
					$observacion = "Por medio del cual se inicia una Indagación Preliminar";
					$nombreEtapa = "Indagación Preliminar";
					break;
				case '2':
					$observacion = "Por medio del cual se inicia una Investigación Disciplinaria";
					$nombreEtapa = "Investigación Disciplinaria";
					break;
				case '5':
					$observacion = "Por medio del cual se formula Pliego de Cargos";
					$nombreEtapa = "Pliego de Cargos";
					break;
				case '8':
					$observacion = "Por medio del cual se falla...";
					$nombreEtapa = "Fallo";
					break;
				case '9':
					$observacion = "Por medio del cual se inhibe de iniciar una accion disciplinaria";
					$nombreEtapa = "Inhibitorio";
					break;
				case '10':
					$observacion = "Por medio del cual se archiva un Proceso Disciplinario de forma definitiva";
					$nombreEtapa = "Archivo";
					break;
				default:
					$observacion = "";
					$nombreEtapa = "";
					break;
			}

			$auto = new Auto;
			$auto->vigenciaAuto = date('Y');
			$auto->Etapa_idEtapa = Input::get('idEtapa');
			$auto->Radicado_idRadicado = Input::get('radicadoAntes');
			$auto->Radicado_vigencia = Input::get('vigenciaAntes');
			$auto->observacionAuto = $observacion;
			$auto->fechaAuto = date('Y-m-d');
			$auto->horaAuto = date('g:i a');
			$auto->apertura = 1;
			$auto->save();

            //Almacena ObservacionesRadicado -- Estado:  (Radicado Generado)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 6; //Asigna número de auto
            $observacionRadicado->Radicado_idRadicado = Input::get('radicadoAntes');
            $observacionRadicado->Radicado_vigencia = Input::get('vigenciaAntes');
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se asignó número de auto ".$auto->idAuto." de fecha ".Util::formatearFecha(date('Y-m-d'))." para la etapa de ".$nombreEtapa;
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();

            //Almacena AbogadoAsignado
            $abogadoAsignado = new AbogadoAsignado;
            $abogadoAsignado->Radicado_idRadicado = Input::get('radicadoAntes');
            $abogadoAsignado->Radicado_vigencia = Input::get('vigenciaAntes');
            $abogadoAsignado->Abogado_idAbogado = Input::get('idAbogado');// id del abogado de cada iteración
            $abogadoAsignado->fechaAsignacion = "Inf. 2014";// Fecha actual
            $abogadoAsignado->observacion = "En reparto se delegó al profesional ".Util::traerNombreAbogadoId(Input::get('idAbogado'))." el conocimiento del proceso";
            $abogadoAsignado->actual = "SI";
            $abogadoAsignado->save();

			$autos = DB::table('solicitudauto')
						->join('etapa', 'solicitudauto.Etapa_idEtapa', '=', 'etapa.idEtapa')
						->join('persona', 'solicitudauto.Persona_documentoPersona', '=', 'persona.documentoPersona')
						->where('asignado', '=', 0)
						->orderBy('idSolicitudAuto', 'desc')
						->get();

			return View::make('plantillas.ajaxSolicitudAutos')
			           ->with('autos', $autos);
		}
	}

	public function actionVerificarGuardarNumeroAutoEspecial()
	{
		$idUsuario = Session::get('documentoUsuario');

		//Verifica si ya se asignó un auto para la etapa solicitada
		$autoProceso = DB::table('auto')
				    ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
				    ->where('Radicado_vigencia', '=', Input::get('vigenciaEspecial'))
				    ->where('Radicado_idRadicado', '=', Input::get('radicadoEspecial'))
					->get();

		if(count($autoProceso) > 0)
		{
			$error = 1;//1 Ya existe el auto
			return $error;
		}
		else
		{
			switch (Input::get("idEtapa"))
			{
				case '15':
					$observacion = "Por medio del cual se declara la prescripción de un proceso";
					$nombreEtapa = "Prescripción";
					break;
				case '16':
					$observacion = "Por medio del cual se declara la caducidad de un proceso";
					$nombreEtapa = "Caducidad";
					break;
				case '17':
					$observacion = "Por medio del cual se declara una variación en el pliego de cargos";
					$nombreEtapa = "Variación Pliego de Cargos";
					break;
				default:
					$observacion = "";
					$nombreEtapa = "";
					break;
			}

			$auto = new Auto;
			$auto->vigenciaAuto = date('Y');
			$auto->Etapa_idEtapa = Input::get('idEtapa');
			$auto->Radicado_idRadicado = Input::get('radicadoEspecial');
			$auto->Radicado_vigencia = Input::get('vigenciaEspecial');
			$auto->observacionAuto = $observacion;
			$auto->fechaAuto = date('Y-m-d');
			$auto->horaAuto = date('g:i a');
			$auto->apertura = 1;
			$auto->save();

            //Almacena ObservacionesRadicado -- Estado:  (Radicado Generado)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = 6; //Asigna número de auto
            $observacionRadicado->Radicado_idRadicado = Input::get('radicadoEspecial');
            $observacionRadicado->Radicado_vigencia = Input::get('vigenciaEspecial');
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = "Se asignó número de auto ".$auto->idAuto." de fecha ".Util::formatearFecha(date('Y-m-d'))." para la etapa de ".$nombreEtapa;
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();

	        if(Input::get('vigenciaEspecial') < "2014")
	        {
	            //Almacena AbogadoAsignado
	            $abogadoAsignado = new AbogadoAsignado;
	            $abogadoAsignado->Radicado_idRadicado = Input::get('radicadoEspecial');
	            $abogadoAsignado->Radicado_vigencia = Input::get('vigenciaEspecial');
	            $abogadoAsignado->Abogado_idAbogado = Input::get('idAbogadoEspecial');// id del abogado de cada iteración
	            $abogadoAsignado->fechaAsignacion = "Inf. 2014";// Fecha actual
	            $abogadoAsignado->observacion = "En reparto se delegó al profesional ".Util::traerNombreAbogadoId(Input::get('idAbogadoEspecial'))." el conocimiento del proceso";
	            $abogadoAsignado->actual = "SI";
	            $abogadoAsignado->save();
	        }

			return 0;
		}
	}

	public function actionHistoricoAutos()
	{
		$fases = array();

		//27 Ver autos fase de instrucción
		if(Util::verificarPermiso(27, Session::get('perfilUsuario'))) 
		{
			array_push($fases, 1);
		}

		//28 Ver autos fase de juzgamiento
		if(Util::verificarPermiso(28, Session::get('perfilUsuario'))) 
		{
			array_push($fases, 2);
		}

		$numerosAuto = DB::table('auto')
				          ->join('etapa', 'auto.Etapa_idEtapa', '=', 'etapa.idEtapa')
				      ->leftJoin('tiposetapa', 'etapa.tiposetapa_idTipoEtapa', '=', 'tiposetapa.idTipoEtapa')		
				         ->where('auto.vigenciaAuto', '=', Input::get('vigencia'))
				       ->whereIn('tiposEtapa_idTipoEtapa', $fases)
				       ->orderBy('auto.idAuto')
				           ->get();

		return View::make('plantillas.ajaxHistoricoAutos')
				   ->with('numerosAuto', $numerosAuto);

	}

	public function actionCargarGenOficio()
	{
		$idUsuario = Session::get('documentoUsuario');

		//Trae todos los departamentos y los retorna en un array
	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						   ->lists('nombreDepartamento','idDepartamento');

	   	$numeroOficio = DB::table('oficio')
						  ->where('vigenciaOficio', '=', date('Y'))
						  ->max('idOficio');

		$operador = DB::table('usuario')
						  ->where('usuario.Persona_documentoPersona', '=', $idUsuario)
						  ->get();

		$iniciales = $operador[0]->inicialesUsuario;

		$numerosQuejas = Util::traerQuejasProceso(Input::get('vigencia'), Input::get('idRadicado'));

		$entidades = DB::table('comunicacionesreglamentarias')
					 ->get();

		return View::make('plantillas.ajaxCargarGenOficios')
		           ->with('numeroOficio', $numeroOficio + 1)
		           ->with('lista_departamentos', $lista_departamentos)
		           ->with('iniciales', $iniciales)
		           ->with('numerosQuejas', $numerosQuejas)
		           ->with('entidades', $entidades)
				   ->with('vigencia', Input::get('vigencia'))
				   ->with('idRadicado', Input::get('idRadicado'))
		           ->with('idPlantilla', Input::get('idPlantilla'))
		           ->with('idTipoPlantilla', Input::get('idTipoPlantilla'));
	}


	public function actionCargarOficioGeneral()
	{
		$idUsuario = Session::get('documentoUsuario');

		//Trae todos los departamentos y los retorna en un array
	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						   ->lists('nombreDepartamento','idDepartamento');

	   	$numeroOficio = DB::table('oficio')
						  ->where('vigenciaOficio', '=', date('Y'))
						  ->max('idOficio');

		$operador = DB::table('usuario')
						  ->where('usuario.Persona_documentoPersona', '=', $idUsuario)
						  ->get();

		$iniciales = $operador[0]->inicialesUsuario;

		//$personas = Persona::all();

		$entidades = DB::table('comunicacionesreglamentarias')
					 ->get();

		return View::make('plantillas.ajaxCargarOficioGeneral')
		           ->with('numeroOficio', $numeroOficio + 1)
		           ->with('lista_departamentos', $lista_departamentos)
		           ->with('iniciales', $iniciales)
		           //->with('personas', $personas)
		           ->with('entidades', $entidades);
	}

	public function actionFijarDestinatario()
	{
		$persona = DB::table('persona')
					->leftJoin('ciudad', 'persona.ciudadCorrespondencia', '=', 'ciudad.idCiudad')
					->join('departamento', 'ciudad.departamento_idDepartamento', '=', 'departamento.idDepartamento')
					->where('persona.documentoPersona', '=', Input::get('documentoPersona'))
					->get();

		//Trae todos los departamentos y los retorna en un array
	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						   ->lists('nombreDepartamento','idDepartamento');

	   	//Trae todas las ciudades y los retorna en un array
	    $lista_ciudades = DB::table('ciudad')
	    					->where('departamento_idDepartamento', '=', $persona[0]->idDepartamento)
	   						->orderBy('nombreCiudad', 'asc')
	   						->lists('nombreCiudad','idCiudad');

		return View::make('plantillas.ajaxFijarDestinatario')
				    ->with('persona', $persona)
				    ->with('lista_departamentos', $lista_departamentos)
				    ->with('lista_ciudades', $lista_ciudades);
	}

	public function actionFijarDestinatarioEntidad()
	{
		$entidad = DB::table('comunicacionesreglamentarias')
					->join('ciudad', 'comunicacionesreglamentarias.Ciudad_idCiudad', '=', 'ciudad.idCiudad')
					->join('departamento', 'ciudad.departamento_idDepartamento', '=', 'departamento.idDepartamento')
					->where('comunicacionesreglamentarias.idComunicacionesReglamentarias', '=', Input::get('idComReg'))
					->get();

		//Trae todos los departamentos y los retorna en un array
	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						   ->lists('nombreDepartamento','idDepartamento');

	   	//Trae todas las ciudades y los retorna en un array
	    $lista_ciudades = DB::table('ciudad')
	    					->where('departamento_idDepartamento', '=', $entidad[0]->idDepartamento)
	   						->orderBy('nombreCiudad', 'asc')
	   						->lists('nombreCiudad','idCiudad');

		return View::make('plantillas.ajaxFijarDestinatarioEnt')
				    ->with('entidad', $entidad)
				    ->with('lista_departamentos', $lista_departamentos)
				    ->with('lista_ciudades', $lista_ciudades);
	}


	public function actionFijarDestinatarioEntidadRemision()
	{
		$entidad = DB::table('entidadremision')
					->join('ciudad', 'entidadremision.Ciudad_idCiudad', '=', 'ciudad.idCiudad')
					->join('departamento', 'ciudad.departamento_idDepartamento', '=', 'departamento.idDepartamento')
					->where('entidadremision.idEntidadRemision', '=', Input::get('idEntidad'))
					->get();

		//Trae todos los departamentos y los retorna en un array
	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						   ->lists('nombreDepartamento','idDepartamento');

	   	//Trae todas las ciudades y los retorna en un array
	    $lista_ciudades = DB::table('ciudad')
	    					->where('departamento_idDepartamento', '=', $entidad[0]->idDepartamento)
	   						->orderBy('nombreCiudad', 'asc')
	   						->lists('nombreCiudad','idCiudad');

		return View::make('plantillas.ajaxFijarDestinatarioEnt')
				    ->with('entidad', $entidad)
				    ->with('lista_departamentos', $lista_departamentos)
				    ->with('lista_ciudades', $lista_ciudades);
	}

	public function actionCargarCiudad()
	{
		//Trae todas las ciudades y los retorna en un array
	    $lista_ciudades = DB::table('ciudad')
	    					->where('departamento_idDepartamento', '=', Input::get('idDepartamento'))
	   						->orderBy('nombreCiudad', 'asc')
	   						->lists('nombreCiudad','idCiudad');

	   	return View::make('plantillas.ajaxCiudades')
  			  	   ->with('lista_ciudades', $lista_ciudades);
	}

	public function actionAgregarArchivos()
	{
		$archivos = DB::table('archivogenerado')
		              ->join('tipoarchivo', 'archivogenerado.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
					  ->where('Radicado_vigencia', '=', Input::get('vigencia'))
					  ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
					  ->where('archivogenerado.subido', '=', 'NO')
					  ->get();

		return View::make('plantillas.ajaxArchivosGenerados')
				   ->with('archivos', $archivos);
	}

	public function actionMostrarSeleccionarArchivo()
	{
		$archivo = DB::table('archivogenerado')
		              ->join('tipoarchivo', 'archivogenerado.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
					  ->where('archivogenerado.idArchivoGenerado', '=', Input::get('idArchivo'))
					  ->get();

		return View::make('plantillas.ajaxSeleccionarArchivo')
		           ->with('archivo', $archivo);
	}

	public function actionAgregarOtrosArchivos()
	{
		$lista_tiposArchivos = DB::table('tipoarchivo')
	   						   ->orderBy('descTipoArchivo', 'asc')
	   						   ->lists('descTipoArchivo','idTipoArchivo');

	   	//Trae la lista de etapas por las que ha pasado el proceso -------------------------
		$etp = DB::table('etapasproceso')
			    ->select('Etapa_idEtapa')
				->where('Radicado_idRadicado', '=', Input::get("idRadicado"))
				->where('Radicado_vigencia', '=', Input::get("vigencia"))
                ->get();

        $etapasProceso = json_decode(json_encode($etp),TRUE);

		$lista_etapas = DB::table('etapa')
							   ->whereIn('idEtapa', $etapasProceso)
	   						   ->orderBy('nombreEtapa', 'desc')
	   						   ->lists('nombreEtapa','idEtapa');

		return View::make('plantillas.ajaxSeleccionarOtroArchivo')
				   ->with('lista_tiposArchivos', $lista_tiposArchivos)
				   ->with('lista_etapas', $lista_etapas)
				   ->with('idEtapa', Input::get("idEtapa"));
	}

	public function actionSubirArchivoExpediente()
	{
		switch (Input::get("idEtapa"))
		{
			case '1':
				$nombreEtapa = "INDAGACION PRELIMINAR";
				break;
			case '2':
				$nombreEtapa = "INVESTIGACION DISCIPLINARIA";
				break;
			case '3':
				$nombreEtapa = "PRORROGA INVESTIGACION DISCIPLINARIA";
				break;
			case '4':
				$nombreEtapa = "EVALUACION";
				break;
			case '5':
				$nombreEtapa = "PLIEGO DE CARGOS";
				break;
			case '6':
				$nombreEtapa = "PRUEBA DE DESCARGOS";
				break;
			case '7':
				$nombreEtapa = "ALEGATOS DE CONCLUSION";
				break;
			case '8':
				$nombreEtapa = "FALLO";
				break;
			case '9':
				$nombreEtapa = "INHIBITORIO";
				break;
			case '10':
				$nombreEtapa = "ARCHIVO";
				break;
			case '11':
				$nombreEtapa = "VALORACION";
				break;
		}

		$archivoGenerado = ArchivoGenerado::find(Input::get("idArchivoGenerado"));

		//Busca el tipo de archivo
		switch ($archivoGenerado->TipoArchivo_idTipoArchivo)
		{
			case '1':
				$nombreTipoArchivo = "AUTOS";
				break;
			case '2':
				$nombreTipoArchivo = "COMUNICACIONES";
				break;
			case '3':
				$nombreTipoArchivo = "NOTIFICACIONES";
				break;
			case '4':
				$nombreTipoArchivo = "EDICTOS";
				break;
			case '5':
				$nombreTipoArchivo = "CITACIONES";
				break;
			case '6':
				$nombreTipoArchivo = "DILIGENCIAS";
				break;
			case '7':
				$nombreTipoArchivo = "SOLICITUDES";
				break;
			case '8':
				$nombreTipoArchivo = "DOCUMENTOS EXTERNOS";
				break;
			case '9':
				$nombreTipoArchivo = "QUEJAS O INFORMES";
				break;
			case '10':
				$nombreTipoArchivo = "CONSTANCIAS SECRETARIALES";
				break;
			case '11':
				$nombreTipoArchivo = "CONSTANCIAS DE RECIBIDO";
				break;
		}

		//El nombre temporal del archivo en el que se guarda el archivo cargado en el servidor
	    $temporalFile = isset($_FILES['archivoImportar']['tmp_name'])?$_FILES['archivoImportar']['tmp_name']:null;
	    //Nombre del archivo
	    $fileName = isset($_FILES['archivoImportar']['name'])?$_FILES['archivoImportar']['name']:null;

		//Si llegó un archivo
		if(!empty($fileName))
		{
			$idUsuario = Session::get('documentoUsuario');
			//Extensión Archivo
			$trozos = explode(".", $fileName);
			$fileExt = end($trozos);

			//Ruta del archivo donde se guardará
			$path = "\procesos\\".Input::get('vigencia')."-".Input::get('idRadicado')."\\".Input::get("idEtapa")."\\".$nombreTipoArchivo."\\";

			//Si no exite la carpeta la crea
			if (!file_exists(public_path().$path))
			{
			    mkdir(public_path().$path, 0777, true);
			}

			//Si el archivo se copió correctamente en el servidor
			if(move_uploaded_file($temporalFile, public_path().$path.utf8_decode($fileName)))
			{
			 	//Almacena el archivo
			 	$archivo = new Archivo;
        		$archivo->TipoArchivo_idTipoArchivo = $archivoGenerado->TipoArchivo_idTipoArchivo;
        		$archivo->Radicado_idRadicado = Input::get("idRadicado");
        		$archivo->Radicado_vigencia = Input::get("vigencia");
        		$archivo->Etapa_idEtapa = Input::get("idEtapa");
        		$archivo->Persona_documentoPersona = $idUsuario;
        		$archivo->nombreArchivo = $fileName;
        		$archivo->fechaSubido = date('Y-m-d');
        		$archivo->horaSubido = date('g:i a');
        		$archivo->revisado = 0;
        		$archivo->vistoBueno = "N/A";
        		$archivo->save();

        		//Actualiza el campo: 'subido' de los archivos generados
				DB::table('archivogenerado')
		          ->where('idArchivoGenerado', Input::get("idArchivoGenerado"))
		          ->update(['subido' => 'SI']);// Estado 7: "Inicia etapa INDAGACIÓN PRELIMINAR"
				//------------------------------------------------
			}
			else//Si no se pudo mover el archivo
			{
				$archivos = DB::table('archivogenerado')
	              ->join('tipoarchivo', 'archivogenerado.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
				  ->where('Radicado_vigencia', '=', Input::get('vigencia'))
				  ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
				  ->where('archivogenerado.subido', '=', 'NO')
				  ->get();

				return View::make('plantillas.ajaxArchivosGenerados')
			   		->with('archivos', $archivos);
			}
		}

		$archivos = DB::table('archivogenerado')
		              ->join('tipoarchivo', 'archivogenerado.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
					  ->where('Radicado_vigencia', '=', Input::get('vigencia'))
					  ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
					  ->where('archivogenerado.subido', '=', 'NO')
					  ->get();

		return View::make('plantillas.ajaxArchivosGenerados')
				   ->with('archivos', $archivos);
	}

	public function actionSubirArchivoExterno()
	{
		list($vigencia, $idRadicado) = explode("-", Input::get("proceso"));
     	$rad[0] = $idRadicado;
     	$vig[0] = $vigencia;

		//El nombre temporal del archivo en el que se guarda el archivo cargado en el servidor
	    $temporalFile = isset($_FILES['archivoExterno']['tmp_name'])?$_FILES['archivoExterno']['tmp_name']:null;
	    //Nombre del archivo
	    $fileName = isset($_FILES['archivoExterno']['name'])?$_FILES['archivoExterno']['name']:null;

		//Si llegó un archivo
		if(!empty($fileName))
		{
			$idUsuario = Session::get('documentoUsuario');
			//Extensión Archivo
			$trozos = explode(".", $fileName);
			$fileExt = end($trozos);

			//Ruta del archivo donde se guardará
			$path = "\procesos\\".$vig[0]."-".$rad[0]."\\DOCUMENTOS EXTERNOS\\DOCUMENTO EXTERNO\\";

			//Si no exite la carpeta la crea
			if (!file_exists(public_path().$path))
			{
			    mkdir(public_path().$path, 0777, true);
			}

			//Si el archivo se copió correctamente en el servidor
			if(move_uploaded_file($temporalFile, public_path().$path.utf8_decode($fileName)))
			{
			 	//Almacena el archivo
			 	$archivo = new Archivo;
        		$archivo->TipoArchivo_idTipoArchivo = 8;//Documento Externo
        		$archivo->Radicado_idRadicado = $rad[0];
        		$archivo->Radicado_vigencia = $vig[0];
        		$archivo->Etapa_idEtapa = NULL;
        		$archivo->Persona_documentoPersona = $idUsuario;
        		$archivo->nombreArchivo = $fileName;
        		$archivo->fechaSubido = date('Y-m-d');
        		$archivo->horaSubido = date('g:i a');
        		$archivo->revisado = 0;
        		$archivo->vistoBueno = "N/A";
        		$archivo->save();
			}
			else//Si no se pudo mover el archivo
			{
				$error = 1;
			}
		}

		$error = 0;

		return $error;
	}

	public function actionSubirArchivoQueja()
	{
		switch (Input::get("etapa"))
		{
			case '1':
				$nombreEtapa = "INDAGACION PRELIMINAR";
				break;
			case '2':
				$nombreEtapa = "INVESTIGACION DISCIPLINARIA";
				break;
			case '3':
				$nombreEtapa = "PRORROGA INVESTIGACION DISCIPLINARIA";
				break;
			case '4':
				$nombreEtapa = "EVALUACION";
				break;
			case '5':
				$nombreEtapa = "PLIEGO DE CARGOS";
				break;
			case '6':
				$nombreEtapa = "PRUEBA DE DESCARGOS";
				break;
			case '7':
				$nombreEtapa = "ALEGATOS DE CONCLUSION";
				break;
			case '8':
				$nombreEtapa = "FALLO";
				break;
			case '9':
				$nombreEtapa = "INHIBITORIO";
				break;
			case '10':
				$nombreEtapa = "ARCHIVO";
				break;
			case '11':
				$nombreEtapa = "VALORACION";
				break;
			case '13'://Descargos
				$nombreEtapa = "DESCARGOS";
				break;
			case '14'://Finalizados
				$nombreEtapa = "FINALIZADOS";
				break;
		}

		list($vigencia, $idRadicado) = explode("-", Input::get("proceso"));
     	$rad[0] = $idRadicado;
     	$vig[0] = $vigencia;

		$nombreTipoArchivo = "QUEJAS O INFORMES";

		//El nombre temporal del archivo en el que se guarda el archivo cargado en el servidor
	    $temporalFile = isset($_FILES['archivoQueja']['tmp_name'])?$_FILES['archivoQueja']['tmp_name']:null;
	    //Nombre del archivo
	    $fileName = isset($_FILES['archivoQueja']['name'])?$_FILES['archivoQueja']['name']:null;

		//Si llegó un archivo
		if(!empty($fileName))
		{
			$idUsuario = Session::get('documentoUsuario');
			//Extensión Archivo
			$trozos = explode(".", $fileName);
			$fileExt = end($trozos);

			//Ruta del archivo donde se guardará
			$path = "\procesos\\".$vig[0]."-".$rad[0]."\\".Input::get("etapa")."\\".$nombreTipoArchivo."\\";

			//Si no exite la carpeta la crea
			if (!file_exists(public_path().$path))
			{
			    mkdir(public_path().$path, 0777, true);
			}

			//Si el archivo se copió correctamente en el servidor
			if(move_uploaded_file($temporalFile, public_path().$path.utf8_decode($fileName)))
			{
			 	//Almacena el archivo
			 	$archivo = new Archivo;
        		$archivo->TipoArchivo_idTipoArchivo = 9;//QUEJA O INFORME
        		$archivo->Radicado_idRadicado = $rad[0];
        		$archivo->Radicado_vigencia = $vig[0];
        		$archivo->Etapa_idEtapa = Input::get("etapa");
        		$archivo->Persona_documentoPersona = $idUsuario;
        		$archivo->nombreArchivo = $fileName;
        		$archivo->fechaSubido = date('Y-m-d');
        		$archivo->horaSubido = date('g:i a');
        		$archivo->revisado = 0;
        		$archivo->vistoBueno = "N/A";
        		$archivo->save();
			}
			else//Si no se pudo mover el archivo
			{
				$error = 1;
			}
		}

		$error = 0;

		return $error;
	}

	public function actionSubirOtroArchivoExpediente()
	{
		switch (Input::get("idEtapa"))
		{
			case '1':
				$nombreEtapa = "INDAGACION PRELIMINAR";
				break;
			case '2':
				$nombreEtapa = "INVESTIGACION DISCIPLINARIA";
				break;
			case '3':
				$nombreEtapa = "PRORROGA INVESTIGACION DISCIPLINARIA";
				break;
			case '4':
				$nombreEtapa = "EVALUACION";
				break;
			case '5':
				$nombreEtapa = "PLIEGO DE CARGOS";
				break;
			case '6':
				$nombreEtapa = "PRUEBA DE DESCARGOS";
				break;
			case '7':
				$nombreEtapa = "ALEGATOS DE CONCLUSION";
				break;
			case '8':
				$nombreEtapa = "FALLO";
				break;
			case '9':
				$nombreEtapa = "INHIBITORIO";
				break;
			case '10':
				$nombreEtapa = "ARCHIVO";
				break;
			case '11':
				$nombreEtapa = "VALORACION";
				break;
			case '13'://Descargos
				$nombreEtapa = "DESCARGOS";
				break;
			case '14'://Finalizados
				$nombreEtapa = "FINALIZADOS";
				break;
		}

		//Busca el tipo de archivo
		switch (Input::get("idTipoArchivo"))
		{
			case '1':
				$nombreTipoArchivo = "AUTOS";
				break;
			case '2':
				$nombreTipoArchivo = "COMUNICACIONES";
				break;
			case '3':
				$nombreTipoArchivo = "NOTIFICACIONES";
				break;
			case '4':
				$nombreTipoArchivo = "EDICTOS";
				break;
			case '5':
				$nombreTipoArchivo = "CITACIONES";
				break;
			case '6':
				$nombreTipoArchivo = "DILIGENCIAS";
				break;
			case '7':
				$nombreTipoArchivo = "SOLICITUDES";
				break;
			case '8':
				$nombreTipoArchivo = "DOCUMENTOS EXTERNOS";
				break;
			case '9':
				$nombreTipoArchivo = "QUEJAS O INFORMES";
				break;
			case '10':
				$nombreTipoArchivo = "CONSTANCIAS SECRETARIALES";
				break;
			case '11':
				$nombreTipoArchivo = "CONSTANCIAS DE RECIBIDO";
				break;
		}

		//El nombre temporal del archivo en el que se guarda el archivo cargado en el servidor
	    $temporalFile = isset($_FILES['archivoImportarOtro']['tmp_name'])?$_FILES['archivoImportarOtro']['tmp_name']:null;
	    //Nombre del archivo
	    $fileName = isset($_FILES['archivoImportarOtro']['name'])?$_FILES['archivoImportarOtro']['name']:null;

		//Si llegó un archivo
		if(!empty($fileName))
		{
			$idUsuario = Session::get('documentoUsuario');
			//Extensión Archivo
			$trozos = explode(".", $fileName);
			$fileExt = end($trozos);

			//Ruta del archivo donde se guardará
			$path = "\procesos\\".Input::get('vigencia')."-".Input::get('idRadicado')."\\".Input::get("idEtapa")."\\".$nombreTipoArchivo."\\";

			//Si no exite la carpeta la crea
			if (!file_exists(public_path().$path))
			{
			    mkdir(public_path().$path, 0777, true);
			}

			//Si el archivo se copió correctamente en el servidor
			if(move_uploaded_file($temporalFile, public_path().$path.utf8_decode($fileName)))
			{
			 	//Almacena el archivo
			 	$archivo = new Archivo;
        		$archivo->TipoArchivo_idTipoArchivo = Input::get("idTipoArchivo");
        		$archivo->Radicado_idRadicado = Input::get("idRadicado");
        		$archivo->Radicado_vigencia = Input::get("vigencia");
        		$archivo->Etapa_idEtapa = Input::get("idEtapa");
        		$archivo->Persona_documentoPersona = $idUsuario;
        		$archivo->nombreArchivo = $fileName;
        		$archivo->fechaSubido = date('Y-m-d');
        		$archivo->horaSubido = date('g:i a');
        		$archivo->revisado = 0;
        		$archivo->vistoBueno = "N/A";
        		$archivo->save();
			}
			else//Si no se pudo mover el archivo
			{
				$error = 1;
			}
		}

		$error = 0;

		return $error;
	}

	public function actionVerExpediente()
	{
		//Si el valor es cero es porque desea consultar todo el expediente
		if(Input::get('idEtapa') == -1)
		{
			$archivos = DB::table('archivo')
						  ->leftJoin('etapa', 'archivo.Etapa_idEtapa', '=', 'etapa.idEtapa')
						  ->join('tipoarchivo', 'archivo.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
						  ->where('archivo.TipoArchivo_idTipoArchivo', '=', 8)//8 Documento Externo
						  ->where('archivo.Radicado_idRadicado', '=', Input::get('idRadicado'))
						  ->where('archivo.Radicado_vigencia', '=', Input::get('vigencia'))
						  ->where(function ($query) {
				                $query->where('archivo.vistoBueno', '=', 'SI')
				                      ->orWhere('archivo.vistoBueno', '=', 'N/A');
				            })
						  ->orderBy('archivo.idArchivo')
						  ->get();
		}
		else if(Input::get('idEtapa') == 0)
		{
			$archivos = DB::table('archivo')
						  ->leftJoin('etapa', 'archivo.Etapa_idEtapa', '=', 'etapa.idEtapa')
						  ->join('tipoarchivo', 'archivo.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
						  ->where('archivo.Radicado_idRadicado', '=', Input::get('idRadicado'))
						  ->where('archivo.Radicado_vigencia', '=', Input::get('vigencia'))
						  ->where(function ($query) {
				                $query->where('archivo.vistoBueno', '=', 'SI')
				                      ->orWhere('archivo.vistoBueno', '=', 'N/A');
				            })
						  ->orderBy('archivo.idArchivo')
						  ->get();
		}
		else // Si no, es porque desea consultar sólo una etapa del expediente
		{

			$archivos = DB::table('archivo')
						  ->join('etapa', 'archivo.Etapa_idEtapa', '=', 'etapa.idEtapa')
						  ->join('tipoarchivo', 'archivo.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
						  ->where('archivo.Radicado_idRadicado', '=', Input::get('idRadicado'))
						  ->where('archivo.Radicado_vigencia', '=', Input::get('vigencia'))
						  ->where('archivo.Etapa_idEtapa', '=', Input::get('idEtapa'))
						  ->where(function ($query) {
				                $query->where('archivo.vistoBueno', '=', 'SI')
				                      ->orWhere('archivo.vistoBueno', '=', 'N/A');
				            })
						  ->orderBy('archivo.idArchivo')
						  ->get();
		}

		return View::make('plantillas.ajaxExpediente')
				   ->with('archivos', $archivos);
	}

	public function actionVerTareas()
	{
		$tareas = DB::table('tarea')
					  ->where('tarea.Radicado_idRadicado', '=', Input::get('idRadicado'))
					  ->where('tarea.Radicado_vigencia', '=', Input::get('vigencia'))
					  ->where('tarea.finalizadaTarea', '=', 0)
					  ->get();

		return View::make('plantillas.ajaxTareas')
				   ->with('tareas', $tareas);
	}

	public function actionVerArchivo($idArchivo)
	{
		$archivo = Archivo::find($idArchivo);

		switch ($archivo->Etapa_idEtapa)
		{
			case '1':
				$nombreEtapa = "INDAGACION PRELIMINAR";
				break;
			case '2':
				$nombreEtapa = "INVESTIGACION DISCIPLINARIA";
				break;
			case '3':
				$nombreEtapa = "PRORROGA INVESTIGACION DISCIPLINARIA";
				break;
			case '4':
				$nombreEtapa = "EVALUACION";
				break;
			case '5':
				$nombreEtapa = "PLIEGO DE CARGOS";
				break;
			case '6':
				$nombreEtapa = "PRUEBA DE DESCARGOS";
				break;
			case '7':
				$nombreEtapa = "ALEGATOS DE CONCLUSION";
				break;
			case '8':
				$nombreEtapa = "FALLO";
				break;
			case '9':
				$nombreEtapa = "INHIBITORIO";
				break;
			case '10':
				$nombreEtapa = "ARCHIVO";
				break;
			case '11':
				$nombreEtapa = "VALORACION";
				break;
			case '13'://Descargos
				$nombreEtapa = "DESCARGOS";
				break;
			case '14'://Finalizados
				$nombreEtapa = "FINALIZADOS";
				break;
			case NULL://DOCUMENTOS EXTERNOS
				$nombreEtapa = "DOCUMENTOS EXTERNOS";
				break;
		}

		//Busca el tipo de archivo
		switch ($archivo->TipoArchivo_idTipoArchivo)
		{
			case '1':
				$nombreTipoArchivo = "AUTOS";
				break;
			case '2':
				$nombreTipoArchivo = "COMUNICACIONES";
				break;
			case '3':
				$nombreTipoArchivo = "NOTIFICACIONES";
				break;
			case '4':
				$nombreTipoArchivo = "EDICTOS";
				break;
			case '5':
				$nombreTipoArchivo = "CITACIONES";
				break;
			case '6':
				$nombreTipoArchivo = "DILIGENCIAS";
				break;
			case '7':
				$nombreTipoArchivo = "SOLICITUDES";
				break;
			case '8':
				$nombreTipoArchivo = "DOCUMENTO EXTERNO";
				break;
			case '9':
				$nombreTipoArchivo = "QUEJAS O INFORMES";
				break;
			case '10':
				$nombreTipoArchivo = "CONSTANCIAS SECRETARIALES";
				break;
			case '11':
				$nombreTipoArchivo = "CONSTANCIAS DE RECIBIDO";
				break;
		}

		$filename = 'procesos\\'.$archivo->Radicado_vigencia."-".$archivo->Radicado_idRadicado."\\".$archivo->Etapa_idEtapa."\\".$nombreTipoArchivo."\\".utf8_decode($archivo->nombreArchivo);

		$path = public_path($filename);

		return Response::make(file_get_contents($path), 200, [
		    'Content-Type' => 'application/pdf',
		    'Content-Disposition' => 'inline; filename="'.$filename.'"'
		]);
	}

	public function actionBorrarArchivoGenerado()
	{
		$archivo = ArchivoGenerado::where('idArchivoGenerado', '=', Input::get('idArchivo'))
								  ->delete();

		return;
	}

	public function actionProgramarTarea()
	{
		return View::make('plantillas.ajaxProgramarTarea')
		 		   ->with('vigencia', Input::get('vigencia'))
		 	       ->with('idRadicado', Input::get('idRadicado'));
	}

	public function actionCargarHoras()
	{
		$idUsuario = Session::get('documentoUsuario');

		return View::make('plantillas.ajaxCargarHoras')
		 	       ->with('fechaTarea', Input::get('fechaTarea'));
	}

	public function actionNuevaTarea()
	{
		return View::make('plantillas.ajaxNuevaTarea')
		 	       ->with('fechaTarea', Input::get('fechaTarea'))
		 	       ->with('horaTarea', Input::get('horaTarea'))
		 	       ->with('vigencia', Input::get('vigencia'))
		 	       ->with('idRadicado', Input::get('idRadicado'));
	}

	public function actionGuardarTarea()
	{
		$idUsuario = Session::get('documentoUsuario');

		//Almacena Tarea para la Secretaria
        $tarea = new Tarea;
        $tarea->Radicado_idRadicado = Input::get("idRadicado");
        $tarea->Radicado_vigencia = Input::get("vigencia"); // Almacena el año actual
        $tarea->asuntoTarea = Input::get("asuntoTarea");
        $tarea->lugarTarea = Input::get("lugarTarea");
        $tarea->descripcionTarea = Input::get("descripcionTarea");
        $tarea->fechaInicioTarea = Input::get("fechaTarea")." ".Input::get("horaTarea");
        $tarea->fechaFinTarea = Input::get("fechaTarea")." ".date('H:i:s', strtotime(Input::get("horaTareaFin")));
        $tarea->fechaProgramaTarea = date("Y-m-d H:i:s");// Fecha actual
        $tarea->todoElDiaTarea = 0;
        $tarea->color = 6;
	    $tarea->finalizadaTarea = 0;
        $tarea->Persona_documentoPersona = $idUsuario;
		$tarea->save();
        return;
	}

	public function actionProcesosSelect()
	{
		$idUsuario = Session::get('documentoUsuario');

		$lista_procesos = DB::table('radicado')
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('abogado.Persona_documentoPersona', '=', $idUsuario)
				  ->where('radicado.vigencia', '=', Input::get('vigencia'))
				  ->orderBy('radicado.idRadicado', 'asc')
				  ->lists('idRadicado','idRadicado');

		return View::make('plantillas.ajaxProcesosSelect')
  			  	   ->with('lista_procesos', $lista_procesos);
	}


	public function actionMostrarBuscarProceso()
	{
		$quejas = DB::table('queja')
   	                ->leftJoin('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
   	                ->join('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->join('abogadoasignado', function($join)
					 	{
				       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
				            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
				            	 ->where('abogadoasignado.actual', '=', 'SI');
				    	})
					->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
					->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					//->where(DB::raw('substr(acumulaqueja.fechaAcumula, -10, 4)'), '=', Input::get("vigencia"))
					->where('acumulaqueja.Radicado_vigencia', '=', Input::get("vigencia"))
					->orderBy('acumulaqueja.Queja_idQueja', 'asc')
					->groupBy('acumulaqueja.Radicado_idRadicado')
				  	->groupBy('acumulaqueja.Radicado_vigencia')
					->get();


		return View::make('plantillas.ajaxBuscarProceso')
  			  	   ->with('quejas', $quejas);
	}

	public function actionMostrarBuscarSelProceso()
	{
		$quejas = DB::table('queja')
   	                ->leftJoin('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
   	                ->join('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->join('abogadoasignado', function($join)
					 	{
				       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
				            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
				            	 ->where('abogadoasignado.actual', '=', 'SI');
				    	})
					->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
					->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					//->where(DB::raw('substr(acumulaqueja.fechaAcumula, -10, 4)'), '=', Input::get("vigencia"))
					->where('acumulaqueja.Radicado_vigencia', '=', Input::get("vigencia"))
					->orderBy('acumulaqueja.Queja_idQueja', 'asc')
					->get();


		return View::make('plantillas.ajaxBuscarSelProceso')
  			  	   ->with('quejas', $quejas);
	}

	public function actionMostrarTerminarEtapa()
	{
		$etp = DB::table('etapa')
			    ->select('siguienteEtapa')
				 ->where('idEtapa', '=', Input::get("idEtapa"))
                   ->get();

		$array = explode('-', $etp[0]->siguienteEtapa);

		$etapasSiguientes = json_decode(json_encode($array),TRUE);

		$lista_etapas = DB::table('etapa')
						   ->whereIn('idEtapa', $etapasSiguientes)
   						   ->orderBy('nombreEtapa', 'desc')
   						   ->lists('nombreEtapa','idEtapa');

		return View::make('plantillas.ajaxTerminarEtapa')
				   ->with('lista_etapas', $lista_etapas);
	}

	public function actionTerminarEtapa()
	{
		$idUsuario = Session::get('documentoUsuario');

		/*
		$archivosGenerados = DB::table('archivogenerado')
					  ->where('Radicado_vigencia', '=', Input::get('vigencia'))
					  ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
					  ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
					  ->where('archivogenerado.subido', '=', 'NO')
					  ->get();

		if(count($archivosGenerados) > 0)
		{
			$mensaje = 1;//1 Hay archivos sin subir en esta etapa
		}
		else
		{

		*/
			$idEtapaSiguiente = Input::get('idEtapaSiguiente');

			//Valida si es etapa de Avoca Conocimiento
			if ($idEtapaSiguiente == 13) //13 Avoca Conocimiento
			{
				//Radicado para reparto de juzgamiento
				DB::table('radicado')
 			      ->where('idRadicado', Input::get('idRadicado'))
			      ->where('vigencia', Input::get('vigencia'))
			     ->update(['repartoJuzgamiento' => 1]);

				//Actualiza los abogados asignados previamente para que el estado actual sea "NO"
				DB::table('abogadoasignado')
				  ->where('Radicado_vigencia', Input::get('vigencia'))
				  ->where('Radicado_idRadicado', Input::get('idRadicado'))
				 ->update(['actual' => "NO"]);

				//Trae las quejas del proceso
				$quejasProceso = Util::traerQuejasProceso(Input::get('vigencia'), Input::get('idRadicado'));
				
				if (count($quejasProceso) > 0) 
				{
					foreach ($quejasProceso as $quejaProceso) 
					{
						//Actualiza el estado de la queja
						DB::table('queja')
						  ->where('idQueja', $quejaProceso)
						 ->update(['EstadoQueja_idEstadoQueja' => 9]);// Estado 9 (Queja enviada a fase de juzgamiento)

						//Guarda ObservacionesQueja 
						$observacionQueja = new ObservacionQueja;
						$observacionQueja->EstadoQueja_idEstadoQueja = 9;// Estado 9 (Queja enviada a fase de juzgamiento)
						$observacionQueja->Queja_idQueja = $quejaProceso;
						$observacionQueja->Persona_documentoPersona = Session::get('documentoUsuario');
						$observacionQueja->observacion = "El proceso se envió a reparto en fase de juzgamiento.";
						$observacionQueja->fechaObservacion = date("Y-m-d");// Fecha actual
						$observacionQueja->horaObservacion = date('g:i a'); // Hora actual
						$observacionQueja->save();
					}
				} 

				//NOTIFICACIÓN VÍA EMAIL
				//Consulta los usuarios encargados del reparto en fase de juzgamiento
				$lideresJuzgamiento = DB::table('usuario')
				                       ->select('nombre', 'email')
									     ->join('persona', 'usuario.Persona_documentoPersona', '=', 'persona.documentoPersona')
										->where('Perfil_idPerfil', 6)
										->where('activoUsuario', 1)
										  ->get();

				if (count($lideresJuzgamiento) > 0) 
				{
					foreach ($lideresJuzgamiento as $lider) 
					{
						//Construye arreglo con los datos a enviar vía email
						$datos = array( "radicado" => Input::get('vigencia')."-".Input::get('idRadicado'),
										"email"    => $lider->email,
										"nombre"   => $lider->nombre
									);
									
						//Envía correo electrónico al solicitante con los detalles de la inscripción
						if(Util::valid_email_address($lider->email) == 1)
						{
							Queue::push('EmailController@emailProcesoRepartoJuzgamiento', $datos);
						}
					}
				}
			}

			$estadoSgte = Util::siguienteEstado($idEtapaSiguiente);
			$nombreEtapa = Util::traerNombreEtapaId($idEtapaSiguiente);

			//Actualiza el radicado con el último estado
			DB::table('radicado')
 			  ->where('idRadicado', Input::get('idRadicado'))
			  ->where('vigencia', Input::get('vigencia'))
			 ->update(['EstadoRadicado_idEstadoRadicado' => $estadoSgte]);

		    //No cambia la etapa a los radicados que pasan a finalizados, con el fin de conservar la etapa donde fueron finalizados
		    if($idEtapaSiguiente != 14)//Si la etapa es diferente de finalizados
		    {
		        //Actualiza la Etapa del Radicado -------------------
				DB::table('radicado')
		          ->where('idRadicado', Input::get('idRadicado'))
		          ->where('vigencia', Input::get('vigencia'))
		          ->update(['Etapa_idEtapa' => $idEtapaSiguiente]);
				//---------------------------------------------------

				$observacion = "El proceso pasó a la etapa ".$nombreEtapa;
		    }
		    else
		    {
				//Si es etapa 14 de finalizado, inactiva el radicado y actualiza la fecha de finalización
				DB::table('radicado')
				->where('idRadicado', Input::get('idRadicado'))
				->where('vigencia', Input::get('vigencia'))
				->update(['activo' => 0, 'fechaFinalizado' => date('Y-m-d')]);
				//------------------------------------------------

		    	//Se omite la actualización de la etapa
				$observacion = "El proceso fue terminado correctamente.  Se pasa a ".$nombreEtapa;
			}			

	        //Almacena ObservacionesRadicado -- Estado:  (Radicado Generado)
            $observacionRadicado  = new ObservacionRadicado;
            $observacionRadicado->EstadoRadicado_idEstadoRadicado = $estadoSgte;
            $observacionRadicado->Radicado_idRadicado = Input::get('idRadicado');
            $observacionRadicado->Radicado_vigencia = Input::get('vigencia');
            $observacionRadicado->Persona_documentoPersona = $idUsuario;
            $observacionRadicado->observacion = $observacion;
            $observacionRadicado->fechaObservacion = date('Y-m-d');
            $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
            $observacionRadicado->save();

			//No cambia la etapa a los radicados que pasan a finalizados, con el fin de conservar la etapa donde fueron finalizados
		    if($idEtapaSiguiente != 14)//Si la etapa es diferente de finalizados
		    {
				//Deja cualquier etapa del radicado inactivas -- (Actual = 0)
				DB::table('etapasproceso')
  				  ->where('Radicado_idRadicado', Input::get('idRadicado'))
				  ->where('Radicado_vigencia', Input::get('vigencia'))
				 ->update(['actual' => 0]);// (Actual = 0)
			
				//Agrega la nueva etapa
				$etapaProceso = new EtapaProceso;
				$etapaProceso->Radicado_idRadicado = Input::get('idRadicado');
				$etapaProceso->Radicado_vigencia = Input::get('vigencia');
				$etapaProceso->Etapa_idEtapa = $idEtapaSiguiente;
				$etapaProceso->fechaEtapa = date('Y-m-d');// Fecha actual
				$etapaProceso->observacion = $observacion;
				$etapaProceso->actual = 1;
				$etapaProceso->fechaFinalEtapa = Util::calcularFechaFinalEtapa($idEtapaSiguiente);
				$etapaProceso->save();			
			}

			$mensaje = 2;//2 No hubo errores.  Ok.
			   
		//}

		return $mensaje;
	}

	public function actionSubir()
	{
		return View::make('procesos.subir')
     			   ->with('menuActivo', "subir");
	}

	public function actionRepartoJuzgamiento()
	{
		if (!Util::verificarPermiso(24, Session::get('perfilUsuario')))
		{
			return Response::view('errors.denegado', array(), 403);
        }

		$abogados = DB::table('abogado')
					   ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					  ->where('abogado.activo',  1)//1 Activo
					  ->where('abogado.juzgamiento', 1)//1 Pertenecen a la secretaría jurídica - Fase Juzgamiento
					->orderBy('persona.nombre', 'asc')
						->get();

		return View::make('procesos.repartoJuzgamiento')
				   ->with('abogados', $abogados)
     			   ->with('menuActivo', "reapartoJuzgamiento");
	}

	public function actionCargarAbogadosReparto()
	{
		$abogados = DB::table('abogado')
					   ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					  ->where('abogado.activo',  1)//1 Activo
					  ->where('abogado.juzgamiento', 1)//1 Pertenecen a la secretaría jurídica - Fase Juzgamiento
					->orderBy('persona.nombre', 'asc')
						->get();

		return View::make('plantillas.ajaxCargarAbogadosReparto')
				   ->with('abogados', $abogados);
	}
	
	public function actionAsignarProceso()
	{
		$fechaHoy = date("Y-m-d");
		$usuario = Session::get('documentoUsuario');
		
		//Desactiva que el Radicado no sea para reparto de juzgamiento
		DB::table('radicado')
		  ->where('idRadicado', Input::get('idRadicado'))
	      ->where('vigencia', Input::get('vigencia'))
	     ->update(['repartoJuzgamiento' => 0]);

		//Consulta los abogados activos
		$nombreAbogado = Util::traerNombreAbogadoId(Input::get('idAbogado'));	
		
		$observacion = "En reparto de fase de juzgamiento se delegó al profesional ".strtoupper($nombreAbogado)." el conocimiento del proceso";

		//Actualiza los abogados asignados previamente para que el estado actual sea "NO"
		DB::table('abogadoasignado')
		  ->where('Radicado_vigencia', Input::get('vigencia'))
		  ->where('Radicado_idRadicado', Input::get('idRadicado'))
		 ->update(['actual' => "NO"]);

		//Almacena AbogadoAsignado
		$abogadoAsignado = new AbogadoAsignado;
		$abogadoAsignado->Radicado_idRadicado = Input::get('idRadicado');
		$abogadoAsignado->Radicado_vigencia = Input::get('vigencia'); 
		$abogadoAsignado->Abogado_idAbogado = Input::get('idAbogado');
		$abogadoAsignado->fechaAsignacion = $fechaHoy;// Fecha actual
		$abogadoAsignado->observacion = $observacion;
		$abogadoAsignado->actual = "SI";
		$abogadoAsignado->save();

		//Trae las quejas del proceso
		$quejasProceso = Util::traerQuejasProceso(Input::get('vigencia'), Input::get('idRadicado'));
		
		if (count($quejasProceso) > 0) 
		{
			foreach ($quejasProceso as $quejaProceso) 
			{
				//Actualiza el estado de la queja
				DB::table('queja')
 				  ->where('idQueja', $quejaProceso)
				 ->update(['EstadoQueja_idEstadoQueja' => 8]);// Estado 8 (Queja repartida en fase de juzgamiento)

				 //Guarda ObservacionesQueja 
				$observacionQueja = new ObservacionQueja;
				$observacionQueja->EstadoQueja_idEstadoQueja = 8;// Estado 8 (Queja repartida en fase de juzgamiento)
				$observacionQueja->Queja_idQueja = $quejaProceso;
				$observacionQueja->Persona_documentoPersona = Session::get('documentoUsuario');
				$observacionQueja->observacion = $observacion;
				$observacionQueja->fechaObservacion = $fechaHoy;// Fecha actual
				$observacionQueja->horaObservacion = date('g:i a'); // Hora actual
				$observacionQueja->save();
			}
		} 

		//Almacena ObservacionesRadicado -- Estado: (Radicado asignado a Abogado)
		$observacionRadicado = new ObservacionRadicado;
		$observacionRadicado->EstadoRadicado_idEstadoRadicado = 86; //(Proceso asignado a profesional en fase de juzgamiento)
		$observacionRadicado->Radicado_idRadicado = Input::get('idRadicado');
		$observacionRadicado->Radicado_vigencia = Input::get('vigencia'); // Almacena el año actual
		$observacionRadicado->Persona_documentoPersona = Session::get('documentoUsuario');
		$observacionRadicado->observacion = $observacion;
		$observacionRadicado->fechaObservacion = $fechaHoy;// Fecha actual
		$observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
		$observacionRadicado->save();

		//NOTIFICACIÓN VÍA EMAIL
		//Trae los datos del abogado
		$datosAbogado = Util::traerDatosAbogadoId(Input::get('idAbogado'));

		$nombreEtapa = Util::traerNombreEtapa(Input::get('vigencia'), Input::get('idRadicado'));

		//Construye arreglo con los datos a enviar vía email
        $datos = array( "radicado" => Input::get('vigencia')."-".Input::get('idRadicado'),
                        "email"    => $datosAbogado->email,
                        "nombre"   => $datosAbogado->nombre,
						"etapa"    => $nombreEtapa
                    );
                       
        //Envía correo electrónico al solicitante con los detalles de la inscripción
		if(Util::valid_email_address($datosAbogado->email) == 1)
		{
			Queue::push('EmailController@emailProcesoAsignado', $datos);
        }
		
		return $nombreAbogado;
	}

	public function actionModalRemitirPorCompetencia()
	{
		$idUsuario = Session::get('documentoUsuario');

	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						     ->lists('nombreDepartamento','idDepartamento');

	   	$numeroOficio = DB::table('oficio')
						  ->where('vigenciaOficio', '=', date('Y'))
 						    ->max('idOficio');

		$remisionCompetencia = DB::table('remisioncompetencia')
		   					     ->where('vigenciaRemision', '=', date('Y'))
							       ->max('idRemisionCompetencia');

		$operador = DB::table('usuario')
					  ->where('usuario.Persona_documentoPersona', '=', $idUsuario)
					    ->get();

		$iniciales = $operador[0]->inicialesUsuario;

		return View::make('plantillas.ajaxModalRemitirPorCompetencia')
				   ->with('idRadicado', Input::get('idRadicado'))
				   ->with('vigencia', Input::get('vigencia'))
				   ->with('numeroOficio', $numeroOficio + 1)
				   ->with('remisionCompetencia', $remisionCompetencia + 1)
		           ->with('lista_departamentos', $lista_departamentos)
		           ->with('iniciales', $iniciales);
	}
		
	public function actionCargarRepartoJuzgamiento()
	{
		$procesos = DB::table('etapasproceso')
					 ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'presuntosHechos', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'falta',  DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'vigencia', 'idRadicado', 'idEtapa', 'fechaEtapa', 'repartoJuzgamiento')
				       ->join('radicado', function($join) {
						 $join->on('etapasproceso.Radicado_idRadicado', '=', 'radicado.idRadicado')
						      ->on('etapasproceso.Radicado_vigencia', '=', 'radicado.vigencia');
					   })

					   ->join('acumulaqueja', function($join) {
						$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
							 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
						})
   					       ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
					   ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
					   ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
					   ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					   ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				 		->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
					   ->where('repartoJuzgamiento', 1)
					   ->where('etapasproceso.actual', 1)
              	     ->orderBy('idEtapaProceso', 'asc')					 
					->groupBy('acumulaqueja.Radicado_idRadicado')
					->groupBy('acumulaqueja.Radicado_vigencia')
			   	  	     ->get();
	
		return View::make('plantillas.ajaxCargarRepartoJuzgamiento')
				   ->with('procesos', $procesos);
	}

	public function actionSubirQuejas()
	{
		return View::make('procesos.subirQuejas')
     			   ->with('menuActivo', "subir-quejas");
	}

	public function actionCargarEtapasProceso()
	{
		list($vigencia, $idRadicado) = explode("-", Input::get("proceso"));
		     	$rad[0] = $idRadicado;
		     	$vig[0] = $vigencia;

		//Trae la lista de etapas por las que ha pasado el proceso -------------------------
		$etp = DB::table('etapasproceso')
			    ->select('Etapa_idEtapa')
				->where('Radicado_idRadicado', '=', $rad[0])
				->where('Radicado_vigencia', '=', $vig[0])
                ->get();

        $etapasProceso = json_decode(json_encode($etp), TRUE);

		$lista_etapas = DB::table('etapa')
						   ->whereIn('idEtapa', $etapasProceso)
   						   ->orderBy('nombreEtapa', 'desc')
   						   ->lists('nombreEtapa','idEtapa');

		return View::make('plantillas.ajaxEtapasProceso')
				   ->with('lista_etapas', $lista_etapas)
				   ->with('idEtapa', Input::get("idEtapa"));
	}

	public function actionCuadroControl()
	{
		$vigencia = 2017;
		$quejas = DB::table('queja')
   	                ->leftJoin('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
   	                //->join('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					//->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->join('abogadoasignado', function($join)
					 	{
				       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
				            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
				            	 ->where('abogadoasignado.actual', '=', 'SI');
				    	})
					->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
					->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					//->where(DB::raw('substr(acumulaqueja.fechaAcumula, -10, 4)'), '=', Input::get("vigencia"))
					->where('acumulaqueja.Radicado_vigencia', '=', $vigencia)
					->orderBy('acumulaqueja.Queja_idQueja', 'asc')
					->get();

		return View::make('procesos.cuadroControl')
				   ->with('quejas', $quejas)
				   ->with('menuActivo', 'cuadro');
	}

	public function actionRegistrarNotificacion()
	{
		$lista_tipNot = DB::table('tiposnotificacion')
							   ->whereIn('idEtapa', $etapasProceso)
	   						   ->orderBy('nombreEtapa', 'desc')
	   						   ->lists('nombreEtapa','idEtapa');
	}

	public function actionBuscar()
	{
		return View::make('procesos.buscar')
				   ->with('menuActivo', 'buscar');		
	}

	public function actionPlantillas()
	{
		return View::make('procesos.plantillas')
				   ->with('menuActivo', 'plantillas');		
	}
	
	public function actionReportes()
	{		
		if (Session::get('perfilUsuario') == 2) 
		{
			$lista_abogados = DB::table('abogado')
       					     ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    					->where('activo', '=', 1)
	   					  ->orderBy('nombre', 'asc')
						    ->lists('nombre','idAbogado');
		} 
		else 
		{
			$lista_abogados = DB::table('abogado')
       					     ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    					->where('idAbogado', '=', Session::get('idAbogado'))
	   					  ->orderBy('nombre', 'asc')
						    ->lists('nombre','idAbogado');
		}
							   
		$lista_etapas = DB::table('etapa')
						  ->where('idEtapa', '!=', 14)
						->orderBy('nombreEtapa', 'asc')
						  ->lists('nombreEtapa','idEtapa');
							
		$lista_dependencias = DB::table('dependencia')
	   	  			          ->orderBy('nombreDependencia', 'asc')
	   						    ->lists('nombreDependencia','idDependencia');

		
		$lista_faltas = DB::table('faltas')
						->orderBy('falta', 'asc')
	  					  ->lists('falta','idFalta');

		return View::make('procesos.reportes')
  			  	   ->with('lista_abogados', $lista_abogados)
				   ->with('lista_etapas', $lista_etapas)
				   ->with('lista_dependencias', $lista_dependencias)
				   ->with('lista_faltas', $lista_faltas)
				   ->with('menuActivo', 'reportes');					   
	}

	public function actionGraficas()
	{		
		if (Session::get('perfilUsuario') == 2) 
		{
			$lista_abogados = DB::table('abogado')
       					     ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    					->where('activo', '=', 1)
	   					  ->orderBy('nombre', 'asc')
						    ->lists('nombre','idAbogado');
		} 
		else 
		{
			$lista_abogados = DB::table('abogado')
       					     ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    					->where('idAbogado', '=', Session::get('idAbogado'))
	   					  ->orderBy('nombre', 'asc')
						    ->lists('nombre','idAbogado');
		}
							   
		$lista_etapas = DB::table('etapa')
						  ->where('idEtapa', '!=', 14)
						->orderBy('nombreEtapa', 'asc')
						  ->lists('nombreEtapa','idEtapa');
							
		$lista_dependencias = DB::table('dependencia')
	   	  			          ->orderBy('nombreDependencia', 'asc')
	   						    ->lists('nombreDependencia','idDependencia');

		
		$lista_faltas = DB::table('faltas')
						->orderBy('falta', 'asc')
	  					  ->lists('falta','idFalta');

		return View::make('procesos.graficas')
  			  	   ->with('lista_abogados', $lista_abogados)
				   ->with('lista_etapas', $lista_etapas)
				   ->with('lista_dependencias', $lista_dependencias)
				   ->with('lista_faltas', $lista_faltas)
				   ->with('menuActivo', 'reportes');					   
	}

	public function actionReportesAutos()
	{		
		$lista_abogados = DB::table('abogado')
							->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						->where('activo', '=', 1)
						->orderBy('nombre', 'asc')
						->lists('nombre','idAbogado');
							   
		$lista_etapas = DB::table('etapa')
						  ->where('idEtapa', '!=', 14)
						->orderBy('nombreEtapa', 'asc')
						  ->lists('nombreEtapa','idEtapa');
							
		$lista_dependencias = DB::table('dependencia')
	   	  			          ->orderBy('nombreDependencia', 'asc')
	   						    ->lists('nombreDependencia','idDependencia');

		
		$lista_faltas = DB::table('faltas')
						->orderBy('falta', 'asc')
	  					  ->lists('falta','idFalta');

		return View::make('procesos.reportesAutos')
  			  	   ->with('lista_abogados', $lista_abogados)
				   ->with('lista_etapas', $lista_etapas)
				   ->with('lista_dependencias', $lista_dependencias)
				   ->with('lista_faltas', $lista_faltas)
				   ->with('menuActivo', 'reportes');					   
	}

	public function actionCalcularVencimientos()
	{
		$inicio           = Input::get("inicio");
		$limite           = Input::get("limite");
		$documentoAbogado = Util::traerDocumentoAbogadoId(Input::get("abogado"));
		$vigencia         = Input::get("vigencia");
		$etapa            = Input::get("etapa");
		$estado           = Input::get("estado");
		$dependencia      = Input::get("dependencia");		
		$falta            = Input::get("faltas");		  

		$quejas = $this->reporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa, $inicio, $limite);				

		$arrayRadicados = array();

		if (count($quejas) > 0) 
		{			
			foreach ($quejas as $queja) 
			{				
				$vencimiento = Util::traerVencimientoProceso($queja->vigencia, $queja->idRadicado); 
				
				$vistaVencimiento = View::make('plantillas.ajaxVencimiento')
										->with('vencimiento'  ,  $vencimiento)
									  ->render();
								  
				$proceso = $queja->vigencia."-".$queja->idRadicado;

				$datos = array("vistaVto_".$proceso => $vistaVencimiento,
							   "vigencia"           => $queja->vigencia,
							   "idRadicado"         => $queja->idRadicado);

                array_push($arrayRadicados, $datos);
			}
		} 

		return Response::json(array('arrayRadicados' => $arrayRadicados));
	}

	public function actionEjecutarReporte()
	{
        $inicio           = Input::get("inicio");
        $limite           = Input::get("limite");
        $documentoAbogado = Util::traerDocumentoAbogadoId(Input::get("abogado"));
        $vigencia         = Input::get("vigencia");
        $etapa            = Input::get("etapa");
        $estado           = Input::get("estado");
		$dependencia      = Input::get("dependencia");
		$falta            = Input::get("faltas");		  
				
		$totalQuejas = $this->totalReporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa);		
		$quejas = $this->reporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa, $inicio, $limite);

		$radicados = array();

		//Texto para el estado de los procesos
		if ($estado == 0) 
		{
			$txtEstado = '<b>inactivos</b>';
		} 
		else if ($estado == 1) 
		{
			$txtEstado = '<b>activos</b>';
		}
		else
		{
			$txtEstado = '<b>activos e inactivos</b>';
		}

		//Texto para la vigencia
		if (in_array(0, $vigencia))
		{
			$txtVigencia = '<b>todas las vigencias</b>';
		} 
		else 
		{
			$vi = '';
			for ($i = 0; $i < count($vigencia); $i++) 
			{ 
				$vi .= $vigencia[$i].", ";
			}

			if (count($vigencia) == 1) 
			{
				$txtVigencia = '<b>la vigencia '.trim($vi, ', ').'</b>'; 
			} 
			else 
			{
				$txtVigencia = '<b>las vigencias: '.trim($vi, ', ').'</b>'; 
			}
		}

		//Texto para el abogado
		if ($documentoAbogado == 0) 
		{
			$txtAbogado = '<b>todos los abogados</b>';
		} 
		else 
		{
			$txtAbogado = '<b>'.ucwords(json_decode(Util::traerNombreAbogadoId(Input::get("abogado")))).'</b>';
		}

		//Texto para las etapas		
		if (in_array(0, $etapa))
		{
			$txtEtapa = '<b>todas las etapas</b>';
		} 
		else 
		{
			$et = '';
			for ($i = 0; $i < count($etapa); $i++) 
			{ 
				$et .= ucwords(Util::traerNombreEtapaId($etapa[$i])).", ";
			}

			if (count($etapa) == 1) 
			{
				$txtEtapa = '<b>la etapa de '.trim($et, ', ').'</b>'; 
			} 
			else 
			{
				$txtEtapa = '<b>las etapas de '.trim($et, ', ').'</b>'; 
			}
		}

		//Texto para las dependencias		
		if (in_array(0, $dependencia))
		{
			$txtDependencia = '<b>todas las dependencias</b>';
		} 
		else 
		{
			$dep = '';
			for ($i = 0; $i < count($dependencia); $i++) 
			{ 
				$dep .= Util::traerNombreDependenciaId($dependencia[$i]).", ";
			}

			$txtDependencia = '<b>la dependencia '.trim($dep, ', ').'</b>'; 
		}

		//Texto para las faltas		
		if (in_array(0, $falta))
		{
			$txtFalta = '<b>todas las faltas</b>';
		} 
		else 
		{
			$fal = '';
			for ($i = 0; $i < count($falta); $i++) 
			{ 
				$fal .= Util::traerNombreFaltaId($falta[$i]).", ";
			}

			$txtFalta = '<b>'.trim($fal, ', ').'</b>'; 
		}
		
		$texto = '<b>'.$totalQuejas.'</b> procesos '.$txtEstado.', a cargo de '.$txtAbogado.', de '.$txtVigencia.', en '.$txtEtapa.', de '.$txtDependencia.', cuyas faltas son:'.$txtFalta.":";

		$vista = View::make('plantillas.ajaxEjecutarReporte')
			  	     ->with('quejas', $quejas)
				   ->render();

		// Retorna parámetros para el botón del excel
		$cadenaVectorEtapas = implode(';', $etapa); 
		$cadenaVectorDependencias = implode(';', $dependencia);
		$cadenaVectorFaltas = implode(';', $falta);
		$cadenaVectorVigencias = implode(';', $vigencia);

		$vistaResumenReporte = View::make('plantillas.ajaxResumenReporte')
								   ->with('texto', $texto)
								   ->with('totalQuejas', $totalQuejas)
								   ->with('idAbogado', Input::get("abogado"))
								   ->with('vigencia', $vigencia)
								   ->with('estado', $estado)
								   ->with('cadenaVectorEtapas', $cadenaVectorEtapas)
								   ->with('cadenaVectorVigencias', $cadenaVectorVigencias)
								   ->with('cadenaVectorDependencias', $cadenaVectorDependencias)
								   ->with('cadenaVectorFaltas', $cadenaVectorFaltas)
					   			 ->render();
		//------------------

		return Response::json(array('vista'               => $vista,
									'vistaResumenReporte' => $vistaResumenReporte,
									'totalQuejas'         => $totalQuejas));
	}

	public function actionEjecutarReporteGraficas()
	{
        $vigencia         = Input::get("vigencia");
        $etapa            = Input::get("etapa");
        $estado           = Input::get("estado");
		$dependencia      = Input::get("dependencia");
		$falta            = Input::get("faltas");	
		
		$tipos0   = array();
		$colores0 = array();
		$valores0 = array();
		
		//Gráfica por vigencias
		$vigenciasTodas = array();
		$vigenciaActual = date("Y");

		for ($i=2014; $i<=$vigenciaActual; $i++) 
		{
			array_push($vigenciasTodas,  $i);
		}  								
		
		foreach ($vigenciasTodas as $vig) 
		{
			$quejas = DB::table('radicado')
			->select('idQueja')
					->join('acumulaqueja', function($join) {
			$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
			})
				->join('abogadoasignado', function($join) {
			$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
				->where('abogadoasignado.actual', '=', 'SI');
			})
				->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
			->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
			->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
			->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
			->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');

			$quejas->where(DB::raw('substr(queja.fechaRecepcionQueja, -10, 4)'), '=', $vig);
			$result = $quejas->count();

			//$quejas->whereIn('radicado.Etapa_idEtapa', $etapa);					
			array_push($tipos0,  $vig);
			array_push($colores0, sprintf("#%06x",rand(0,16777215)));
			array_push($valores0, $result);
		}

		//Gráfica 1
		$tipos1   = array();
		$colores1 = array();
		$valores1 = array();
		
		//Gráfica por etapas
		$etapa = array();

		$listaEtapas = DB::table('etapa')
						->select('idEtapa', 'nombreEtapa')
    					   ->get();

		foreach ($listaEtapas as $le) 
		{
			array_push($etapa,  $le->idEtapa);
		}
		
		//Etapas
		for ($i=0; $i < count($etapa) ; $i++) 
		{ 
			$nombreEtapa = DB::table('etapa')
							->select('nombreEtapa')
							->where('idEtapa', $etapa[$i])
							->first();

			$quejas = DB::table('radicado')
			->select('idQueja')
					->join('acumulaqueja', function($join) {
			$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
			})
				->join('abogadoasignado', function($join) {
			$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
				->where('abogadoasignado.actual', '=', 'SI');
			})
				->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
			->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
			->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
			->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
			->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');
				
			$quejas->where('etapa.idEtapa', $etapa[$i]);

			$quejas->where(DB::raw('substr(queja.fechaRecepcionQueja, -10, 4)'), '=', $vigencia);
			
			$result = $quejas->count();

			if($result > 0)
			{				
				array_push($tipos1,  $nombreEtapa->nombreEtapa);
				array_push($colores1, sprintf("#%06x",rand(0,16777215)));
				array_push($valores1, $result);
			}
		}

		//Gráfica 2
		$tipos2   = array();
		$colores2 = array();
		$valores2 = array();
		
		//Gráfica por dependencias
		$dependencia = array();

		$listaDependencias = DB::table('dependencia')
								->select('idDependencia', 'nombreDependencia')
									->get();

		foreach ($listaDependencias as $ld) 
		{
			array_push($dependencia,  $ld->idDependencia);
		}
		
		//Etapas
		for ($i=0; $i < count($dependencia) ; $i++) 
		{ 
			$nombreDependencia = DB::table('dependencia')
							      ->select('nombreDependencia')
							       ->where('idDependencia', $dependencia[$i])
							       ->first();

			$quejas = DB::table('radicado')
			->select('idQueja')
					->join('acumulaqueja', function($join) {
			$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
			})
				->join('abogadoasignado', function($join) {
			$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
				->where('abogadoasignado.actual', '=', 'SI');
			})
				->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
			->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
			->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
			->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
			->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');
				

		    $quejas->where('queja.dependencia_idDependencia', $dependencia[$i]);

			$quejas->where(DB::raw('substr(queja.fechaRecepcionQueja, -10, 4)'), '=', $vigencia);
			
			$result = $quejas->count();

			if($result > 0)
			{
				array_push($tipos2,  $nombreDependencia->nombreDependencia);
				array_push($colores2, sprintf("#%06x",rand(0,16777215)));
				array_push($valores2, $result);
			}

		}

		//Gráfica 3
		$tipos3   = array();
		$colores3 = array();
		$valores3 = array();
		
		//Gráfica por faltas
		$falta = array();

		$listaFaltas = DB::table('faltas')
 					    ->select('idFalta', 'falta')
					       ->get();

		foreach ($listaFaltas as $lf) 
		{
			array_push($falta,  $lf->idFalta);
		}
		
		//Etapas
		for ($i=0; $i < count($falta) ; $i++) 
		{ 
			$nombreFalta = DB::table('faltas')
							->select('falta')
						     ->where('idFalta', $falta[$i])
							 ->first();

			$quejas = DB::table('radicado')
			->select('idQueja')
					->join('acumulaqueja', function($join) {
			$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
			})
				->join('abogadoasignado', function($join) {
			$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
				->where('abogadoasignado.actual', '=', 'SI');
			})
				->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
			->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
			->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
			->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
			->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');
				

		    $quejas->where('radicado.faltas_idFalta', $falta[$i]);

			$quejas->where(DB::raw('substr(queja.fechaRecepcionQueja, -10, 4)'), '=', $vigencia);
			
			$result = $quejas->count();

			if($result > 0)
			{
				array_push($tipos3,  $nombreFalta->falta);
				array_push($colores3, sprintf("#%06x",rand(0,16777215)));
				array_push($valores3, $result);
			}

		}

		//Gráfica Abogados
	    $abogados = DB::table('abogado')
	       		       ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
	    			  ->where('activo', '=', 1)
					  ->where('director', '=', 0)
	   			    ->orderBy('nombre', 'asc')
	   			        ->get();

		$dataAbogados = array();

		$meses = array('01','02','03','04','05','06','07','08','09','10','11','12');

		foreach ($abogados as $abogado) 
		{
			$label = $abogado->nombre;
			$color = '#'.dechex(rand(0,10000000));

			$data = array();

			foreach ($meses as $mes) 
			{
				$cantidad = Util::traerCantidadAsignadosMes($vigencia, $mes, $abogado->idAbogado);
				array_push($data, $cantidad);
			}

			$dataAbogado = array('label' => $label, 'data' => $data, 'fill' => false, 'backgroundColor' => $color);
			array_push($dataAbogados, $dataAbogado);
		}

		$vistaReporteGraficas = View::make('plantillas.ajaxReporteGraficas')
									->with('vigencia', $vigencia)
					   			  ->render();

		return Response::json(array('vistaReporteGraficas' => $vistaReporteGraficas,
									'vigencia'             => $vigencia,
									'tipos0'               => $tipos0,
									'colores0'             => $colores0,
									'valores0'             => $valores0,
									'tipos1'               => $tipos1,
									'colores1'             => $colores1,
									'valores1'             => $valores1,		
									'tipos2'               => $tipos2,
									'colores2'             => $colores2,
									'valores2'             => $valores2,
									'tipos3'               => $tipos3,
									'colores3'             => $colores3,
									'valores3'             => $valores3,
									'dataAbogados'         => $dataAbogados									
									));
									
		
		if (!in_array(0, $etapa))
		{
			foreach ($etapa as $et) 
			{
				//$quejas->whereIn('radicado.Etapa_idEtapa', $etapa);					
				array_push($tipos,  'proc');
                array_push($colores, sprintf("#%06x",rand(0,16777215)));
                array_push($valores, Util::traerCantidadEtapaVigencia($queja->idEtapa, $vigencia));
			}
		}

		$vistaReporteGraficas = View::make('plantillas.ajaxReporteGraficas')
					   			  ->render();

		return Response::json(array('vistaReporteGraficas' => $vistaReporteGraficas,
									'tipos'                => $tipos,
									'colores'              => $colores,
									'valores'              => $valores));

		//----------------
				
		$totalQuejas = $this->totalReporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa);		
		$quejas = $this->reporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa, $inicio, $limite);

		$radicados = array();

		//Texto para el estado de los procesos
		if ($estado == 0) 
		{
			$txtEstado = '<b>inactivos</b>';
		} 
		else if ($estado == 1) 
		{
			$txtEstado = '<b>activos</b>';
		}
		else
		{
			$txtEstado = '<b>activos e inactivos</b>';
		}

		//Texto para la vigencia
		if (in_array(0, $vigencia))
		{
			$txtVigencia = '<b>todas las vigencias</b>';
		} 
		else 
		{
			$vi = '';
			for ($i = 0; $i < count($vigencia); $i++) 
			{ 
				$vi .= $vigencia[$i].", ";
			}

			if (count($vigencia) == 1) 
			{
				$txtVigencia = '<b>la vigencia '.trim($vi, ', ').'</b>'; 
			} 
			else 
			{
				$txtVigencia = '<b>las vigencias: '.trim($vi, ', ').'</b>'; 
			}
		}

		//Texto para el abogado
		if ($documentoAbogado == 0) 
		{
			$txtAbogado = '<b>todos los abogados</b>';
		} 
		else 
		{
			$txtAbogado = '<b>'.ucwords(json_decode(Util::traerNombreAbogadoId(Input::get("abogado")))).'</b>';
		}

		//Texto para las etapas		
		if (in_array(0, $etapa))
		{
			$txtEtapa = '<b>todas las etapas</b>';
		} 
		else 
		{
			$et = '';
			for ($i = 0; $i < count($etapa); $i++) 
			{ 
				$et .= ucwords(Util::traerNombreEtapaId($etapa[$i])).", ";
			}

			if (count($etapa) == 1) 
			{
				$txtEtapa = '<b>la etapa de '.trim($et, ', ').'</b>'; 
			} 
			else 
			{
				$txtEtapa = '<b>las etapas de '.trim($et, ', ').'</b>'; 
			}
		}

		//Texto para las dependencias		
		if (in_array(0, $dependencia))
		{
			$txtDependencia = '<b>todas las dependencias</b>';
		} 
		else 
		{
			$dep = '';
			for ($i = 0; $i < count($dependencia); $i++) 
			{ 
				$dep .= Util::traerNombreDependenciaId($dependencia[$i]).", ";
			}

			$txtDependencia = '<b>la dependencia '.trim($dep, ', ').'</b>'; 
		}

		//Texto para las faltas		
		if (in_array(0, $falta))
		{
			$txtFalta = '<b>todas las faltas</b>';
		} 
		else 
		{
			$fal = '';
			for ($i = 0; $i < count($falta); $i++) 
			{ 
				$fal .= Util::traerNombreFaltaId($falta[$i]).", ";
			}

			$txtFalta = '<b>'.trim($fal, ', ').'</b>'; 
		}
		
		$texto = '<b>'.$totalQuejas.'</b> procesos '.$txtEstado.', a cargo de '.$txtAbogado.', de '.$txtVigencia.', en '.$txtEtapa.', de '.$txtDependencia.', cuyas faltas son:'.$txtFalta.":";

		/*
		$vista = View::make('plantillas.ajaxReporteGraficas')
			  	     ->with('quejas', $quejas)
				   ->render();
		*/

		$tipos   = array();
		$colores = array();
		$valores = array();

		if (count($quejas) > 0) 
		{
			foreach ($quejas as $queja) 
			{
				array_push($tipos,  'proc');
                array_push($colores, sprintf("#%06x",rand(0,16777215)));
                array_push($valores, Util::traerCantidadEtapaVigencia($queja->idEtapa, $vigencia));
			}
		} 
		
		// Retorna parámetros para el botón del excel
		$cadenaVectorEtapas = implode(';', $etapa); 
		$cadenaVectorDependencias = implode(';', $dependencia);
		$cadenaVectorFaltas = implode(';', $falta);
		$cadenaVectorVigencias = implode(';', $vigencia);

		$vistaReporteGraficas = View::make('plantillas.ajaxReporteGraficas')
							 	    ->with('texto', $texto)
								    ->with('totalQuejas', $totalQuejas)
								    ->with('idAbogado', Input::get("abogado"))
								    ->with('vigencia', $vigencia)
								    ->with('estado', $estado)
								    ->with('cadenaVectorEtapas', $cadenaVectorEtapas)
								    ->with('cadenaVectorVigencias', $cadenaVectorVigencias)
								    ->with('cadenaVectorDependencias', $cadenaVectorDependencias)
								    ->with('cadenaVectorFaltas', $cadenaVectorFaltas)
					   			  ->render();

		return Response::json(array('vistaReporteGraficas' => $vistaReporteGraficas,
									'totalQuejas'          => $totalQuejas,
									'tipos'                => $tipos,
									'colores'              => $colores,
									'valores'              => $valores));
	}
	

	public function actionEjecutarReporteAutos()
	{
        $inicio           = Input::get("inicio");
        $limite           = Input::get("limite");
        $documentoAbogado = Util::traerDocumentoAbogadoId(Input::get("abogado"));
        $vigencia         = Input::get("vigencia");
		$vigenciaAuto     = Input::get("vigenciaAuto");
        $etapa            = Input::get("etapa");
        $estado           = Input::get("estado");
		$falta            = Input::get("faltas");		  
				
		$totalQuejas = $this->totalReporteAutos($falta, $estado, $documentoAbogado, $vigencia, $vigenciaAuto, $etapa);	
		$quejas = $this->reporteAutos($falta, $estado, $documentoAbogado, $vigencia, $vigenciaAuto, $etapa, $inicio, $limite);

		$radicados = array();

		//Texto para el estado de los procesos
		if ($estado == 0) 
		{
			$txtEstado = '<b>inactivos</b>';
		} 
		else if ($estado == 1) 
		{
			$txtEstado = '<b>activos</b>';
		}
		else
		{
			$txtEstado = '<b>activos e inactivos</b>';
		}

		//Texto para la vigencia
		if ($vigencia == 0) 
		{
			$txtVigencia = '<b>todas las vigencias de procesos</b>';
		} 
		else 
		{
			$txtVigencia = '<b>la vigencia de proceso'.$vigencia.'</b>';
		}

		//Texto para la vigencia
		if ($vigenciaAuto == 0) 
		{
			$txtVigenciaAuto = '<b>todas las vigencias de auto</b>';
		} 
		else 
		{
			$txtVigenciaAuto = '<b>la vigencia de auto'.$vigenciaAuto.'</b>';
		}

		//Texto para el abogado
		if ($documentoAbogado == 0) 
		{
			$txtAbogado = '<b>todos los abogados</b>';
		} 
		else 
		{
			$txtAbogado = '<b>'.ucwords(json_decode(Util::traerNombreAbogadoId(Input::get("abogado")))).'</b>';
		}

		//Texto para las etapas		
		if (in_array(0, $etapa))
		{
			$txtEtapa = '<b>todas las etapas</b>';
		} 
		else 
		{
			$et = '';
			for ($i = 0; $i < count($etapa); $i++) 
			{ 
				$et .= ucwords(Util::traerNombreEtapaId($etapa[$i])).", ";
			}

			if (count($etapa) == 1) 
			{
				$txtEtapa = '<b>la etapa de '.trim($et, ', ').'</b>'; 
			} 
			else 
			{
				$txtEtapa = '<b>las etapas de '.trim($et, ', ').'</b>'; 
			}
		}

		//Texto para las faltas		
		if (in_array(0, $falta))
		{
			$txtFalta = '<b>todas las faltas</b>';
		} 
		else 
		{
			$fal = '';
			for ($i = 0; $i < count($falta); $i++) 
			{ 
				$fal .= Util::traerNombreFaltaId($falta[$i]).", ";
			}

			$txtFalta = '<b>'.trim($fal, ', ').'</b>'; 
		}
		
		$texto = '<b>Autos: '.$totalQuejas.'</b> procesos '.$txtEstado.', a cargo de '.$txtAbogado.', de '.$txtVigencia.', de '.$txtVigenciaAuto.', en '.$txtEtapa.', cuyas faltas son:'.$txtFalta.":";

		$vista = View::make('plantillas.ajaxEjecutarReporteAutos')
			  	     ->with('quejas', $quejas)
					 ->with('inicio', $inicio)
				   ->render();

		// Retorna parámetros para el botón del excel
		$cadenaVectorEtapas = implode(';', $etapa); 
		$cadenaVectorFaltas = implode(';', $falta);

		$vistaResumenReporte = View::make('plantillas.ajaxResumenReporteAutos')
								   ->with('texto', $texto)
								   ->with('totalQuejas', $totalQuejas)
								   ->with('idAbogado', Input::get("abogado"))
								   ->with('vigencia', $vigencia)
								   ->with('vigenciaAuto', $vigenciaAuto)
								   ->with('estado', $estado)
								   ->with('cadenaVectorEtapas', $cadenaVectorEtapas)
								   ->with('cadenaVectorFaltas', $cadenaVectorFaltas)
					   			 ->render();
		//------------------

		return Response::json(array('vista'               => $vista,
									'vistaResumenReporte' => $vistaResumenReporte,
									'totalQuejas'         => $totalQuejas));
	}

	public function reporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa, $inicio, $limite)
	{
		$quejas = DB::table('radicado')
				   ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'presuntosHechos', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'falta', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'vigencia', 'idRadicado', 'idEtapa')
  		  		     ->join('acumulaqueja', function($join) {
				  $join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				       ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				  })
				     ->join('abogadoasignado', function($join) {
			      $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			           ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			        ->where('abogadoasignado.actual', '=', 'SI');
			      })
				     ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				 ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				     ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				 ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				     ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');

					if ($documentoAbogado != 0) 
					{
						$quejas->where('abogado.Persona_documentoPersona', '=', $documentoAbogado);		
					}

					if ($estado != 2) 
					{
						$quejas->where('radicado.activo', '=', $estado);
					}

					if (!in_array(0, $vigencia))
					{
						$quejas->whereIn('radicado.vigencia', $vigencia);
					}

					if (!in_array(0, $etapa))
					{
						$quejas->whereIn('radicado.Etapa_idEtapa', $etapa);
					}

					if (count($dependencia) > 0) 
					{
						if (!in_array(0, $dependencia))
						{
							$quejas->whereIn('queja.dependencia_idDependencia', $dependencia);
						}
					}

					if (!in_array(0, $falta))
					{
						$quejas->whereIn('radicado.faltas_idFalta', $falta);
					}

					$quejas->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2);//2 Radicado acumulado

					$quejas->groupBy('radicado.idRadicado');
					$quejas->groupBy('radicado.vigencia');
					$quejas->orderBy('queja.idQueja', 'desc');

					//si es -1 es porque se va a generar el excel con todos los registros ( No requiere tomar sólo unas filas)
					if ($limite > 0) 
					{
						$quejas->skip($inicio);
						$quejas->take($limite);
					}

			$result = $quejas->get();

		return $result;
	}

	public function reporteAutos($falta, $estado, $documentoAbogado, $vigencia, $vigenciaAuto, $etapa, $inicio, $limite)
	{
		$quejas = DB::table('auto')				
				   ->select('radicado.idRadicado', 'radicado.vigencia', 'idAuto', 'vigenciaAuto', 'observacionAuto', 'etp.nombreEtapa AS etapaProceso', 'eta.nombreEtapa AS etapaAuto', 'fechaAsignacion', 'falta', 'fechaAuto')
			     ->leftJoin('radicado', function($join) {
						$join->on('auto.Radicado_idRadicado', '=', 'radicado.idRadicado')
							 ->on('auto.Radicado_vigencia', '=', 'radicado.vigencia');
						  })
				->leftJoin('abogadoasignado', function($join) {
				 $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				      ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
					->where('abogadoasignado.actual', '=', 'SI');
				})
				->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				->leftJoin('etapa AS etp', 'etp.idEtapa', '=', 'radicado.Etapa_idEtapa')
				->leftJoin('etapa AS eta', 'eta.idEtapa', '=', 'auto.Etapa_idEtapa')
				->leftJoin('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');

					if ($documentoAbogado != 0) 
					{
						$quejas->where('abogado.Persona_documentoPersona', '=', $documentoAbogado);		
					}

					if ($estado != 2)
					{
						$quejas->where('radicado.activo', '=', $estado);
					}

					if ($vigencia != 0) 
					{
						$quejas->where('radicado.vigencia', '=', $vigencia);
					}

					if ($vigenciaAuto != 0) 
					{
						$quejas->where('auto.vigenciaAuto', '=', $vigenciaAuto);
					}

					if (!in_array(0, $etapa))
					{
						$quejas->whereIn('auto.Etapa_idEtapa', $etapa);
					}
					
					if (!in_array(0, $falta))
					{
						$quejas->whereIn('radicado.faltas_idFalta', $falta);
					}

					//$quejas->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2);//2 Radicado acumulado

					//$quejas->groupBy('radicado.idRadicado');
					//$quejas->groupBy('radicado.vigencia');	
					$quejas->orderBy('auto.idAuto');

					//si es -1 es porque se va a generar el excel con todos los registros ( No requiere tomar sólo unas filas)
					if ($limite > 0) 
					{
						$quejas->skip($inicio);
						$quejas->take($limite);
					}

			$result = $quejas->get();

		return $result;
	}

	public function totalReporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa)
	{
		$quejas = DB::table('radicado')
				   ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar')
  		  		     ->join('acumulaqueja', function($join) {
				  $join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				       ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				  })
				     ->join('abogadoasignado', function($join) {
			      $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			           ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			        ->where('abogadoasignado.actual', '=', 'SI');
			      })
				     ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				 ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				     ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				 ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				     ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');

					if ($documentoAbogado != 0) 
					{
						$quejas->where('abogado.Persona_documentoPersona', '=', $documentoAbogado);		
					}

					if ($estado != 2)
					{
						$quejas->where('radicado.activo', '=', $estado);
					}

					if (!in_array(0, $vigencia))
					{
						$quejas->whereIn('radicado.vigencia', $vigencia);
					}

					if (!in_array(0, $etapa))
					{
						$quejas->whereIn('radicado.Etapa_idEtapa', $etapa);
					}

					if (!in_array(0, $dependencia))
					{
						$quejas->whereIn('queja.dependencia_idDependencia', $dependencia);
					}

					if (!in_array(0, $falta))
					{
						$quejas->whereIn('radicado.faltas_idFalta', $falta);
					}

					$quejas->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2);//2 Radicado acumulado

					$quejas->groupBy('radicado.idRadicado');
					$quejas->groupBy('radicado.vigencia');					

			$result = $quejas->get();

		return count($result);
	}	
		
	public function totalReporteAutos($falta, $estado, $documentoAbogado, $vigencia, $vigenciaAuto, $etapa)
	{

		$quejas = DB::table('auto')				
				   ->select('etp.nombreEtapa AS etapaProceso', 'eta.nombreEtapa AS etapaAuto', 'fechaAsignacion')
			     ->leftJoin('radicado', function($join) {
						$join->on('auto.Radicado_idRadicado', '=', 'radicado.idRadicado')
							 ->on('auto.Radicado_vigencia', '=', 'radicado.vigencia');
						  })
				->leftJoin('abogadoasignado', function($join) {
				 $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
				      ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
					->where('abogadoasignado.actual', '=', 'SI');
				})
				->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				->leftJoin('etapa AS etp', 'etp.idEtapa', '=', 'radicado.Etapa_idEtapa')
				->leftJoin('etapa AS eta', 'eta.idEtapa', '=', 'auto.Etapa_idEtapa')
				->leftJoin('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado');

					if ($documentoAbogado != 0) 
					{
						$quejas->where('abogado.Persona_documentoPersona', '=', $documentoAbogado);		
					}

					if ($estado != 2)
					{
						$quejas->where('radicado.activo', '=', $estado);
					}

					if ($vigencia != 0) 
					{
						$quejas->where('radicado.vigencia', '=', $vigencia);
					}

					if ($vigenciaAuto != 0) 
					{
						$quejas->where('auto.vigenciaAuto', '=', $vigenciaAuto);
					}

					if (!in_array(0, $etapa))
					{
						$quejas->whereIn('auto.Etapa_idEtapa', $etapa);
					}
					
					if (!in_array(0, $falta))
					{
						$quejas->whereIn('radicado.faltas_idFalta', $falta);
					}

					//$quejas->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2);//2 Radicado acumulado

					//$quejas->groupBy('radicado.idRadicado');
					//$quejas->groupBy('radicado.vigencia');	

			$result = $quejas->get();

		return count($result);
	}
	
	public function actionReporteExcel($cadenaVectorDependencias, $cadenaVectorFaltas, $estado, $idAbogado, $cadenaVectorVigencias, $cadenaVectorEtapas)
	{
		$documentoAbogado = Util::traerDocumentoAbogadoId($idAbogado);
        $dependencia = explode(';', $cadenaVectorDependencias);
		$falta = explode(';', $cadenaVectorFaltas);
		$etapa = explode(';', $cadenaVectorEtapas);
		$vigencia = explode(';', $cadenaVectorVigencias);
		$inicio = 0;
		$limite = -1;

		$totalQuejas = $this->totalReporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa);		
		$quejas = $this->reporte($dependencia, $falta, $estado, $documentoAbogado, $vigencia, $etapa, $inicio, $limite);
		

		//Texto para el estado de los procesos
		if ($estado == 0) 
		{
			$txtEstado = 'inactivos';
		} 
		else if ($estado == 1) 
		{
			$txtEstado = 'activos';
		}
		else
		{
			$txtEstado = 'activos e inactivos';
		}

		//Texto para la vigencia
		if (in_array(0, $vigencia))
		{
			$txtVigencia = 'todas las vigencias';
		} 
		else 
		{
			$vi = '';
			for ($i = 0; $i < count($vigencia); $i++) 
			{ 
				$vi .= $vigencia[$i].", ";
			}

			if (count($vigencia) == 1) 
			{
				$txtVigencia = 'la vigencia '.trim($vi, ', '); 
			} 
			else 
			{
				$txtVigencia = 'las vigencias: '.trim($vi, ', '); 
			}
		}

		//Texto para el abogado
		if ($documentoAbogado == 0) 
		{
			$txtAbogado = 'todos los abogados';
		} 
		else 
		{
			$txtAbogado = ucwords(json_decode(Util::traerNombreAbogadoId($idAbogado)));
		}

		//Texto para las etapas		
		if (in_array(0, $etapa))
		{
			$txtEtapa = 'todas las etapas';
		} 
		else 
		{
			$et = '';
			for ($i = 0; $i < count($etapa); $i++) 
			{ 
				$et .= ucwords(Util::traerNombreEtapaId($etapa[$i])).", ";
			}

			if (count($etapa) == 1) 
			{
				$txtEtapa = 'la etapa de '.trim($et, ', '); 
			} 
			else 
			{
				$txtEtapa = 'las etapas de '.trim($et, ', '); 
			}
		}

		//Texto para las dependencias		
		if (in_array(0, $dependencia))
		{
			$txtDependencia = 'todas las dependencias';
		} 
		else 
		{
			$dep = '';
			for ($i = 0; $i < count($dependencia); $i++) 
			{ 
				$dep .= Util::traerNombreDependenciaId($dependencia[$i]).", ";
			}

			$txtDependencia = 'la dependencia '.trim($dep, ', '); 
		}
		
		$tituloExcel = $totalQuejas.' procesos '.$txtEstado.', a cargo de '.$txtAbogado.', de '.$txtVigencia.', en '.$txtEtapa.', de '.$txtDependencia;

        Excel::create($tituloExcel, function($excel) use ($tituloExcel, $quejas) {

        // Set the title
        $excel->setTitle('Consolidado');
        // Chain the setters
        $excel->setCreator('Oficina de Control Disciplinario Interno');
        $excel->setCompany('Alcaldía de Manizales');

        // Call them separately
        $excel->setDescription($tituloExcel);
        $excel->sheet('Informe', function($sheet) use ($tituloExcel, $quejas) {
        
        $sheet->cells('D1:D2', function($cells)
        {
            $cells->setAlignment('center');
        });
        
        $sheet->fromArray(array(
            array($tituloExcel, '')), null, 'C1', false, false);

            $sheet->loadView('procesos.excelReporte')
                      ->with('quejas', $quejas)
                      ->with('tituloExcel', $tituloExcel);
            });
        })->export('xlsx');		
	}

	public function actionReporteExcelAutos($cadenaVectorFaltas, $estado, $idAbogado, $vigencia, $vigenciaAuto, $cadenaVectorEtapas)
	{
		$documentoAbogado = Util::traerDocumentoAbogadoId($idAbogado);
		$falta = explode(';', $cadenaVectorFaltas);
		$etapa       = explode(';', $cadenaVectorEtapas);
		$inicio = 0;
		$limite = -1;
		$totalQuejas = $this->totalReporteAutos($falta, $estado, $documentoAbogado, $vigencia, $vigenciaAuto, $etapa);		
		$quejas = $this->reporteAutos($falta, $estado, $documentoAbogado, $vigencia, $vigenciaAuto, $etapa, $inicio, $limite);		

		//Texto para el estado de los procesos
		if ($estado == 0) 
		{
			$txtEstado = 'inactivos';
		} 
		else if ($estado == 1) 
		{
			$txtEstado = 'activos';
		}
		else
		{
			$txtEstado = 'activos e inactivos';
		}

		//Texto para la vigencia
		if ($vigencia == 0) 
		{
			$txtVigencia = 'todas las vigencias de procesos';
		} 
		else 
		{
			$txtVigencia = 'la vigencia de procesos '.$vigencia;
		}

		//Texto para la vigencia de auto
		if ($vigenciaAuto == 0) 
		{
			$txtVigenciaAuto = 'todas las vigencias de autos';
		} 
		else 
		{
			$txtVigenciaAuto = 'la vigencia de autos '.$vigenciaAuto;
		}

		//Texto para el abogado
		if ($documentoAbogado == 0) 
		{
			$txtAbogado = 'todos los abogados';
		} 
		else 
		{
			$txtAbogado = ucwords(json_decode(Util::traerNombreAbogadoId($idAbogado)));
		}

		//Texto para las etapas		
		if (in_array(0, $etapa))
		{
			$txtEtapa = 'todas las etapas';
		} 
		else 
		{
			$et = '';
			for ($i = 0; $i < count($etapa); $i++) 
			{ 
				$et .= ucwords(Util::traerNombreEtapaId($etapa[$i])).", ";
			}

			if (count($etapa) == 1) 
			{
				$txtEtapa = 'la etapa de '.trim($et, ', '); 
			} 
			else 
			{
				$txtEtapa = 'las etapas de '.trim($et, ', '); 
			}
		}
		
		$tituloExcel = 'Autos - '.$totalQuejas.' procesos '.$txtEstado.', a cargo de '.$txtAbogado.', de '.$txtVigencia.', de '.$txtVigenciaAuto.', en '.$txtEtapa;

        Excel::create($tituloExcel, function($excel) use ($tituloExcel, $quejas) {

        // Set the title
        $excel->setTitle('Consolidado autos');
        // Chain the setters
        $excel->setCreator('Oficina de Control Disciplinario Interno');
        $excel->setCompany('Alcaldía de Manizales');

        // Call them separately
        $excel->setDescription($tituloExcel);
        $excel->sheet('Informe Autos', function($sheet) use ($tituloExcel, $quejas) {
        
        $sheet->cells('D1:D2', function($cells)
        {
            $cells->setAlignment('center');
        });
        
        $sheet->fromArray(array(
            array($tituloExcel, '')), null, 'C1', false, false);

            $sheet->loadView('procesos.excelReporteAutos')
                      ->with('quejas', $quejas)
                      ->with('tituloExcel', $tituloExcel);
            });
        })->export('xlsx');		
	}

	public function actionBuscarProceso()
	{
		list($vigencia, $idRadicado) = explode("-", Input::get("numeroProcesoBuscar"));

		$idRadicado = (int)$idRadicado;

		$proceso = DB::table('radicado')
		->select('vigencia', 'idRadicado', 'fechaHechos', 'radicado.activo as activoProceso')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  //->where('radicado.activo', '=', 1)
				  ->where('radicado.idRadicado', '=', $idRadicado)
				  ->where('radicado.vigencia', '=', $vigencia)
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		//Trael el id de la etapa actual
		$etapas = DB::table('etapasproceso')
		           ->select('Etapa_idEtapa', 'tiposEtapa_idTipoEtapa')
		             ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				    ->where('Radicado_idRadicado', '=', $idRadicado)
				    ->where('Radicado_vigencia', '=', $vigencia)
				    ->where('actual', '=', 1)//1 actual
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

		$idEtapa = 0;
		$fase = 0;

  		if(count($etapas) > 0)
  		{
  			$idEtapa = $etapas->Etapa_idEtapa;
			$fase = $etapas->tiposEtapa_idTipoEtapa;
  		}

		$observaciones =  DB::table('observacionesradicado')
						   ->select('fechaObservacion', 'EstadoRadicado_idEstadoRadicado', 'horaObservacion', 'descEstadoRadicado', 'nombre', 'observacion')
						  ->join('estadoradicado', 'observacionesradicado.EstadoRadicado_idEstadoRadicado', '=', 'estadoradicado.idEstadoRadicado')
						 ->join('persona', 'observacionesradicado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						  ->where('observacionesradicado.Radicado_idRadicado', '=', $idRadicado)
						  ->where('observacionesradicado.Radicado_vigencia', '=', $vigencia)
						  ->where('estadoradicado.lineaTiempo', '=', 1)// visible en la línea de tiempo
						  ->orderBy('fechaObservacion')
						  ->get();						
						  
		$archivos = DB::table('archivo')
					  ->join('etapa', 'archivo.Etapa_idEtapa', '=', 'etapa.idEtapa')
					  ->join('tipoarchivo', 'archivo.TipoArchivo_idTipoArchivo', '=', 'tipoarchivo.idTipoArchivo')
					  ->where('archivo.Radicado_idRadicado', '=', $idRadicado)
					  ->where('archivo.Radicado_vigencia', '=', $vigencia)
					  //->where('archivo.vistoBueno', '=', 'SI')
					  ->Where('archivo.vistoBueno', '=', 'N/A')
					  ->orderBy('archivo.idArchivo')
					  ->get();

		$bitacoras =  DB::table('observacionesradicado')
						  ->join('estadoradicado', 'observacionesradicado.EstadoRadicado_idEstadoRadicado', '=', 'estadoradicado.idEstadoRadicado')
						  ->join('persona', 'observacionesradicado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						  ->where('observacionesradicado.Radicado_idRadicado', '=', $idRadicado)
						  ->where('observacionesradicado.Radicado_vigencia', '=', $vigencia)
						  ->orderBy('observacionesradicado.fechaObservacion')
						  ->get();

		//Trae la lista de etapas por las que ha pasado el proceso -------------------------
		$etp = DB::table('etapasproceso')
			    ->select('Etapa_idEtapa')
				->where('Radicado_idRadicado', '=', $idRadicado)
				->where('Radicado_vigencia', '=', $vigencia)
				->where('Etapa_idEtapa', '!=', 14)//Todas las etapas excepto las finalizadas
                ->get();

        $etapasProceso = json_decode(json_encode($etp),TRUE);

		$lista_etapas = DB::table('etapa')
							   ->whereIn('idEtapa', $etapasProceso)
	   						   ->orderBy('nombreEtapa', 'desc')
	   						   ->lists('nombreEtapa','idEtapa');

		$documentoAbogado = Util::traerDocumentoAbogadoAsignado($vigencia, $idRadicado);

		return View::make('plantillas.ajaxModuloBuscarProceso')
  			  	   ->with('fase', $fase)
				   ->with('proceso', $proceso)
				   ->with('documentoAbogado', $documentoAbogado)
  			  	   ->with('idEtapa', $idEtapa)
  			  	   ->with('observaciones', $observaciones)
  			  	   ->with('archivos', $archivos)
  			  	   ->with('bitacoras', $bitacoras)
  			  	   ->with('lista_etapas', $lista_etapas)
  			  	   ->with('menuActivo', "activos");
	}

	public function actionBuscarNombreQuejoso()
    {
		$q = "+".preg_replace( '/\s(\w+)/', ' +\\1', Input::get('quejosoBuscar'));

		$quejas = DB::table('radicado')
		->select('vigencia', 'idRadicado', 'idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'falta')
			->join('acumulaqueja', function($join) {
		$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
			->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
		})
		->join('abogadoasignado', function($join) {
		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
		->where('abogadoasignado.actual', '=', 'SI');
		})
		->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
		->join('quejoso', 'queja.idQueja', '=', 'quejoso.Queja_idQueja')
		->join('persona', 'quejoso.Persona_documentoPersona', '=', 'persona.documentoPersona')
	->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
		->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
	->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
	->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
	->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
		->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
	
		//->where('quejoso.Persona_documentoPersona', Input::get('quejosoBuscar'))
		//->where('persona.nombre', Input::get('quejosoBuscar'))
		->whereRaw("MATCH(nombre) AGAINST(? IN BOOLEAN MODE)", array($q))
		->orWhere("documentoPersona", Input::get('quejosoBuscar'))
		->groupBy('radicado.idRadicado')
		->groupBy('radicado.vigencia')
		->orderBy('queja.idQueja', 'desc')
		->get();

		return View::make('plantillas.ajaxModuloBuscarTabla')
				   ->with('criterio',  Input::get('quejosoBuscar'))
				   ->with('quejas', $quejas);
	}

	public function actionBuscarNombrePresunto()
    {
		$q = "+".preg_replace( '/\s(\w+)/', ' +\\1', Input::get('presuntoBuscar'));

		$quejas = DB::table('radicado')
		->select('vigencia', 'idRadicado', 'idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'falta')
			->join('acumulaqueja', function($join) {
		$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
			->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
		})
		->join('abogadoasignado', function($join) {
		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
		->where('abogadoasignado.actual', '=', 'SI');
		})
		->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
		->join('presuntoresponsable', 'queja.idQueja', '=', 'presuntoresponsable.Queja_idQueja')
		->join('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
		->join('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
	->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
		->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
	->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
	->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
	->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
		->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
		//->where('quejoso.Persona_documentoPersona', Input::get('quejosoBuscar'))
		//->where('persona.nombre', Input::get('quejosoBuscar'))
		->whereRaw("MATCH(nombre) AGAINST(? IN BOOLEAN MODE)", array($q))
		->orWhere("documentoPersona", Input::get('presuntoBuscar'))
		->groupBy('radicado.idRadicado')
		->groupBy('radicado.vigencia')
		->orderBy('queja.idQueja', 'desc')
		->get();

		return View::make('plantillas.ajaxModuloBuscarTabla')
				   ->with('criterio',  Input::get('presuntoBuscar'))
				   ->with('quejas', $quejas);
	}
	
	public function actionBuscarPalabraClave()
    {
		$q = "+".preg_replace( '/\s(\w+)/', ' +\\1', Input::get('palabraClave'));

		$quejas = DB::table('radicado')
		->select('vigencia', 'idRadicado', 'idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'fechaAsignacion', 'presuntosHechos', 'presuntoLugar', 'falta')
			->join('acumulaqueja', function($join) {
		$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
			->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
		})
		->join('abogadoasignado', function($join) {
		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
		->where('abogadoasignado.actual', '=', 'SI');
		})
		->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
		->join('presuntoresponsable', 'queja.idQueja', '=', 'presuntoresponsable.Queja_idQueja')
		->join('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
		->join('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
		->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
		->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
		->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
	->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
	->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
		->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
		//->where('quejoso.Persona_documentoPersona', Input::get('quejosoBuscar'))
		//->where('persona.nombre', Input::get('quejosoBuscar'))
		->whereRaw("MATCH(presuntosHechos) AGAINST(? IN BOOLEAN MODE)", array($q))
		->groupBy('radicado.idRadicado')
		->groupBy('radicado.vigencia')
		->orderBy('queja.idQueja', 'desc')
		->get();

		return View::make('plantillas.ajaxModuloBuscarTabla')
				   ->with('criterio',  Input::get('palabraClave'))
				   ->with('quejas', $quejas);
	}

	public function actionCargarLineaTiempo()
	{
		return View::make('plantillas.ajaxLineaTiempo')
				   ->with('vigencia',  Input::get('vigencia'))
				   ->with('idRadicado',  Input::get('idRadicado'));
	}

	public function actionModalCambiarFecha()
	{
		$etapa = DB::table('etapasproceso')
		          ->select('idEtapa', 'nombreEtapa', 'fechaEtapa')   
				    ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				   ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
				   ->where('Radicado_vigencia', '=', Input::get('vigencia'))
				   ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
				 ->orderBy('idEtapaProceso', 'desc')
				   ->first();

		return View::make('plantillas.ajaxModalCambiarFecha')
				   ->with('etapa',  $etapa)
				   ->with('fase',  Input::get('fase'))
				   ->with('vigencia', Input::get('vigencia'))
				   ->with('idRadicado',  Input::get('idRadicado'))
				   ->with('actuacion',  Input::get('actuacion'));
	}

	public function actionModalCambiarFechaHechos()
	{
		$radicado = DB::table('radicado')
				 	 ->select('fechaHechos')
					  ->where('vigencia', Input::get('vigencia'))
					  ->where('idRadicado', Input::get('idRadicado'))
					  ->first();

		if ($radicado->fechaHechos == null) 
		{
			$fechaHechos = '';
		} 
		else 
		{
			$fechaHechos = $radicado->fechaHechos;
		}
		
		return View::make('plantillas.ajaxModalCambiarFechaHechos')
				   ->with('fechaHechos', $fechaHechos)
				   ->with('vigencia', Input::get('vigencia'))
				   ->with('idRadicado', Input::get('idRadicado'));
	}

	public function actionModalCambiarFaltasComunes()
	{
		$lista_faltas = DB::table('faltas')
						->orderBy('falta', 'asc')
	  					  ->lists('falta','idFalta');

		$radicado = DB::table('radicado')
				 	 ->select('faltas_idFalta')
					  ->where('vigencia', Input::get('vigencia'))
					  ->where('idRadicado', Input::get('idRadicado'))
					  ->first();

		$falta = null;

		if ($radicado->faltas_idFalta != null) 
		{
			$falta = $radicado->faltas_idFalta;
		}
		
		return View::make('plantillas.ajaxModalCambiarFaltasComunes')
				   ->with('lista_faltas', $lista_faltas)
				   ->with('falta', $falta)
				   ->with('vigencia', Input::get('vigencia'))
				   ->with('idRadicado', Input::get('idRadicado'));
	}


	public function actionCambiarFechaHechos()
	{
		$radicado = DB::table('radicado')
				 	 ->select('fechaHechos')
					  ->where('vigencia', Input::get('vigencia'))
					  ->where('idRadicado', Input::get('idRadicado'))
					  ->first();

		if ($radicado->fechaHechos == null) 
		{
			$fechaHechos = 'No establecida';
		} 
		else 
		{
			$fechaHechos = $radicado->fechaHechos;
		}

		DB::table('radicado')
		  ->where('vigencia', Input::get('vigencia'))
		  ->where('idRadicado', Input::get('idRadicado'))
		 ->update(['fechaHechos' => Input::get('fechaHechos')]);

		// ===== LOGS ===== //  	
		$accion = 4;//4 Establece la fecha de los hechos
		$descripcion = "En el proceso: ".Input::get('vigencia')."-".Input::get('idRadicado').", cambió la fecha de los hechos de: ".$fechaHechos." a ".Util::formatearFechaCorta(Input::get('fechaHechos'));
		Util::almacenaLog($accion, $descripcion);
		//-----------

		//Almacena ObservacionesRadicado -- Estado: (MODIFICA LA FECHA DE LOS HECHOS)
		$observacionRadicado = new ObservacionRadicado;
		$observacionRadicado->EstadoRadicado_idEstadoRadicado = 66; //Estado 66: "MODIFICA LA FECHA DE LOS HECHOS"
		$observacionRadicado->Radicado_idRadicado = Input::get('idRadicado');
		$observacionRadicado->Radicado_vigencia = Input::get('vigencia'); // Almacena el año actual
		$observacionRadicado->Persona_documentoPersona = Session::get('documentoUsuario');
		$observacionRadicado->observacion = $descripcion;
		$observacionRadicado->fechaObservacion = date("Y-m-d");// Fecha actual
		$observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
		$observacionRadicado->save();
	}

	public function actionCambiarFecha()
	{
		//Para los logs, averigua la fecha actual de la etapa
		$fechaActual = Util::traerFechaEtapa(Input::get('vigencia'), Input::get('idRadicado'), Input::get('idEtapa'));

		//Calcula la fecha final luego de establecer la nueva fecha
		$fechaFinal = Util::calcularFechaFinalEtapaModif(Input::get('fechaEtapa'), Input::get('idEtapa'));

		//Actualiza a la nueva fecha inicial y final
		$etapa = DB::table('etapasproceso')
				   ->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
				   ->where('Radicado_vigencia', '=', Input::get('vigencia'))
				   ->where('Etapa_idEtapa', '=', Input::get('idEtapa'))
				  ->update(['fechaEtapa'      => Input::get('fechaEtapa'),
							'fechaFinalEtapa' => $fechaFinal]);
							  
		// ===== LOGS ===== //  	
		$accion = 3;//3 Modifica la fecha de una etapa
		$descripcion = "En el proceso: ".Input::get('vigencia')."-".Input::get('idRadicado').", etapa: ".Util::traerNombreEtapaId(Input::get('idEtapa')).", modificó la fecha inicial de: ".$fechaActual." a ".Util::formatearFechaCorta(Input::get('fechaEtapa'));
		Util::almacenaLog($accion, $descripcion);
		//-----------

		//Almacena ObservacionesRadicado -- Estado: (MODIFICA LA FECHA DE UNA ETAPA)
		$observacionRadicado = new ObservacionRadicado;
		$observacionRadicado->EstadoRadicado_idEstadoRadicado = 65; //Estado 65: "MODIFICA LA FECHA DE UNA ETAPA"
		$observacionRadicado->Radicado_idRadicado = Input::get('idRadicado');
		$observacionRadicado->Radicado_vigencia = Input::get('vigencia'); // Almacena el año actual
		$observacionRadicado->Persona_documentoPersona = Session::get('documentoUsuario');
		$observacionRadicado->observacion = $descripcion;
		$observacionRadicado->fechaObservacion = date("Y-m-d");// Fecha actual
		$observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
		$observacionRadicado->save();
	}

	public function actionCargarWidgetProceso()
	{
		return View::make('plantillas.ajaxWidgetProceso')
				   ->with('vigencia',  Input::get('vigencia'))
				   ->with('idRadicado',  Input::get('idRadicado'));
	}
	
	public function actionCargarWidgetPrescripcion()
	{
		return View::make('plantillas.ajaxWidgetPrescripcion')
				   ->with('vigencia',  Input::get('vigencia'))
				   ->with('idRadicado',  Input::get('idRadicado'))
				   ->with('edicion', 1);
	}
	
	public function actionCargarWidgetFalta()
	{
		return View::make('plantillas.ajaxWidgetFalta')
				   ->with('vigencia',  Input::get('vigencia'))
				   ->with('idRadicado',  Input::get('idRadicado'))
				   ->with('edicion', 1);
	}

	public function actionCambiarFalta()
	{
		//Actualiza el id de la falta
		DB::table('radicado')
  		  ->where('idRadicado', '=', Input::get('idRadicado'))
		  ->where('vigencia', '=', Input::get('vigencia'))
		 ->update(['faltas_idFalta' => Input::get('falta')]);
					  
		// ===== LOGS ===== //  	
		$accion = 8;//8 Establece falta al proceso
		$descripcion = "En el proceso: ".Input::get('vigencia')."-".Input::get('idRadicado').",  establece falta con el id: ".Input::get('falta');
		Util::almacenaLog($accion, $descripcion);
		//-----------
	}

	public function actionAcumularProcesoAProceso()
	{
		return View::make('procesos.acumularProcesoAProceso')
				   ->with('menuActivo', 'quejas');
	}

	public function actionBuscarProcesoAcumular()
	{
		list($vigencia, $idRadicado) = explode("-", Input::get("numeroProcesoBuscar"));

		$idRadicado = (int)$idRadicado;

		$proceso = DB::table('radicado')
					->select('vigencia', 'idRadicado', 'fechaHechos', 'radicado.activo as activoProceso', 'abogado.Persona_documentoPersona as documentoAbogado', 'nombre', 'documentoPersona')
  				      ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				  //->where('radicado.activo', '=', 1)
				  ->where('radicado.idRadicado', '=', $idRadicado)
				  ->where('radicado.vigencia', '=', $vigencia)
				->groupBy('radicado.idRadicado')
			    ->groupBy('radicado.vigencia')
				  ->first();

		$numerosQuejas = Util::traerQuejasProceso($vigencia, $idRadicado);

		return View::make('plantillas.ajaxBuscarProcesoAcumular')
  			  	   ->with('proceso', $proceso)
				   ->with('numerosQuejas', $numerosQuejas)
				   ->with('tipo', Input::get("tipo"));
	}

	public function actionAcumularProcesoProceso()
	{	
		//Origen
		list($vigenciaOrigen, $idRadicadoOrigen) = explode("-", Input::get("procesoOrigen"));
		$idRadicadoOrigen = (int)$idRadicadoOrigen;
		$quejaOrigen = Util::traerPrimeraQuejaProceso($vigenciaOrigen, $idRadicadoOrigen);

		//Destino
		list($vigenciaDestino, $idRadicadoDestino) = explode("-", Input::get("procesoDestino"));
		$idRadicadoDestino = (int)$idRadicadoDestino;
		$quejaDestino = Util::traerPrimeraQuejaProceso($vigenciaDestino, $idRadicadoDestino);

		$usuario = Session::get('documentoUsuario');

		//1. En la tabla acumulaqueja agregar un registro con el número de la queja que se va a acumular al número del proceso que recibe la acumulación:
        $acumulaQueja = new AcumulaQueja;
        $acumulaQueja->Queja_idQueja = $quejaOrigen;
        $acumulaQueja->Radicado_idRadicado = $idRadicadoDestino;
        $acumulaQueja->Radicado_Vigencia = $vigenciaDestino;
        $acumulaQueja->Persona_documentoPersona = $usuario;
        $acumulaQueja->fechaAcumula = Input::get("fechaAcumulacion");
        $acumulaQueja->horaAcumula = date('g:i a'); // Hora actual
        $acumulaQueja->save();

		//2. En la tabla acumularadicado agregar un registro con el radicado que se va a acumular al radicado que recibe la acumulación de la siguiente manera:
		$acumulaRadicado = new AcumulaRadicado;
		$acumulaRadicado->Radicado_idRadicado = $idRadicadoOrigen;
		$acumulaRadicado->Radicado_vigencia = $vigenciaOrigen; 
		$acumulaRadicado->AcumulaQueja_Radicado_idRadicado = $idRadicadoDestino;
		$acumulaRadicado->AcumulaQueja_Radicado_vigencia = $vigenciaDestino; 
		$acumulaRadicado->AcumulaQueja_Queja_idQueja = $quejaOrigen;
		$acumulaRadicado->Persona_documentoPersona = $usuario;
		$acumulaRadicado->fechaAcumula = Input::get("fechaAcumulacion");
		$acumulaRadicado->horaAcumula = date('g:i a'); // Hora actual
		$acumulaRadicado->save();

		//3. En la tabla etapasproceso poner en 0 el actual de todos los registros del radicado origen.  Agregar un registro con el proceso que se va a acumular (Etapa_idEtapa 18 ACUMULADO) actual 1:
		DB::table('etapasproceso')
		  ->where('Radicado_idRadicado', $idRadicadoOrigen)
	      ->where('Radicado_vigencia', $vigenciaOrigen)
         ->update(['actual' => 0]);// (Actual = 0)

		$etapaProceso = new EtapaProceso;
		$etapaProceso->Radicado_idRadicado = $idRadicadoOrigen;
		$etapaProceso->Radicado_vigencia = $vigenciaOrigen; 
		$etapaProceso->Etapa_idEtapa = 18;//18 Acumulado
		$etapaProceso->fechaEtapa = Input::get("fechaAcumulacion");
		$etapaProceso->observacion = Input::get("motivo");
		$etapaProceso->actual = 1;
		$etapaProceso->fechaFinalEtapa = Input::get("fechaAcumulacion");
		$etapaProceso->save();

		//4. En la tabla observacionesradicado agregar un registro en el radicado que se va a acumular con el EstadoRadicado_idEstadoRadicado 2 (Proceso Acumulado) y agregando la respectiva observación:
		$observacionRadicadoOrigen = new ObservacionRadicado;
		$observacionRadicadoOrigen->EstadoRadicado_idEstadoRadicado = 2; //Estado 2: Proceso Acumulado
		$observacionRadicadoOrigen->Radicado_idRadicado = $idRadicadoOrigen;
		$observacionRadicadoOrigen->Radicado_vigencia = $vigenciaOrigen;
		$observacionRadicadoOrigen->Persona_documentoPersona = $usuario;
		$observacionRadicadoOrigen->observacion = Input::get("motivo");
		$observacionRadicadoOrigen->fechaObservacion = Input::get("fechaAcumulacion");
		$observacionRadicadoOrigen->horaObservacion = date('g:i a'); // Hora actual
		$observacionRadicadoOrigen->save();

		$observacionRadicadoDestino = new ObservacionRadicado;
		$observacionRadicadoDestino->EstadoRadicado_idEstadoRadicado = 49; //Estado 49: Proceso Acumulado a este proceso
		$observacionRadicadoDestino->Radicado_idRadicado = $idRadicadoDestino;
		$observacionRadicadoDestino->Radicado_vigencia = $vigenciaDestino;
		$observacionRadicadoDestino->Persona_documentoPersona = $usuario;
		$observacionRadicadoDestino->observacion = Input::get("motivo");
		$observacionRadicadoDestino->fechaObservacion = Input::get("fechaAcumulacion");
		$observacionRadicadoDestino->horaObservacion = date('g:i a'); // Hora actual
		$observacionRadicadoDestino->save();

		//5. En la tabla radicado modificar el estado del radicado que se va a acumular  a (2 Proceso acumulado): OJO ACTIVO EN 0
		DB::table('radicado')
		  ->where('idRadicado', $idRadicadoOrigen)
          ->where('vigencia', $vigenciaOrigen)
         ->update(['EstadoRadicado_idEstadoRadicado' => 2, 'activo' => 0]);// Estado 2: Proceso Acumulado


		//6. En la tabla abogadoasignado actualizar el actual de todos los registros.  Agregar un registro con el radicado que se va a acumular, el id del abogado que recibe y la anotación
		 DB::table('abogadoasignado')
	       ->where('Radicado_vigencia', $vigenciaOrigen)
           ->where('Radicado_idRadicado', $idRadicadoOrigen)
          ->update(['actual' => "NO"]);

		$idAbogadoDestino = Util::traerIdAbogadoAsignado($vigenciaDestino, $idRadicadoDestino);

		$abogadoAsignado = new AbogadoAsignado;
		$abogadoAsignado->Radicado_idRadicado = $idRadicadoOrigen;
		$abogadoAsignado->Radicado_vigencia = $vigenciaOrigen;
		$abogadoAsignado->Abogado_idAbogado = $idAbogadoDestino;
		$abogadoAsignado->fechaAsignacion = Input::get("fechaAcumulacion");
		$abogadoAsignado->observacion = Input::get('motivo');
		$abogadoAsignado->actual = "SI";
		$abogadoAsignado->save();

		return 4;
	}

	public function actionDiagrama($vigencia, $idRadicado, $fase)
	{
		$ultimaEtapa = DB::table('etapasproceso')
		                ->select('Etapa_idEtapa', 'tiposEtapa_idTipoEtapa')
						  ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
 						 ->where('Radicado_idRadicado', '=', $idRadicado)
						 ->where('Radicado_vigencia', '=', $vigencia)
						 ->where('actual', 1)
					   ->orderBy('idEtapaProceso', 'desc')
						 ->first();

		$etapaActual = 0;
		$faseActual = 0;

  		if(count($ultimaEtapa) > 0)
  		{
			$etapaActual = $ultimaEtapa->Etapa_idEtapa;
			$faseActual = $ultimaEtapa->tiposEtapa_idTipoEtapa;
  		} 

		//Si se desconoce la fase actual del proceso
		if ($fase == 0) 
		{
			$fase = $faseActual;
		}

		if ($fase == 1) 
		{
			$pasoInhbit = Util::verificarPasoEtapas($vigencia, $idRadicado, 9);
			$pasoIndPre = Util::verificarPasoEtapas($vigencia, $idRadicado, 1);
			$pasoInvDis = Util::verificarPasoEtapas($vigencia, $idRadicado, 2);
			$pasoProUno = Util::verificarPasoEtapas($vigencia, $idRadicado, 3);
			$pasoProDos = Util::verificarPasoEtapas($vigencia, $idRadicado, 27);
			$pasoCieInv = Util::verificarPasoEtapas($vigencia, $idRadicado, 24);
			$pasoPliCar = Util::verificarPasoEtapas($vigencia, $idRadicado, 5);
			$pasoNotPli = Util::verificarPasoEtapas($vigencia, $idRadicado, 28);
			$pasoRemJuz = Util::verificarPasoEtapas($vigencia, $idRadicado, 29);
			$pasoArchiv = Util::verificarPasoEtapas($vigencia, $idRadicado, 10);
			
			return View::make('procesos.diagrama')
  					   ->with('etapaActual', $etapaActual)
					   ->with('vigencia', $vigencia)
					   ->with('idRadicado', $idRadicado)
					   ->with('pasoInhbit', $pasoInhbit)
					   ->with('pasoIndPre', $pasoIndPre)
					   ->with('pasoInvDis', $pasoInvDis)
					   ->with('pasoProUno', $pasoProUno)
					   ->with('pasoProDos', $pasoProDos)
					   ->with('pasoCieInv', $pasoCieInv)
					   ->with('pasoPliCar', $pasoPliCar)
					   ->with('pasoNotPli', $pasoNotPli)
					   ->with('pasoRemJuz', $pasoRemJuz)
					   ->with('pasoArchiv', $pasoArchiv);
		}
		else if ($fase == 2)
		{
			$pasoDecPruJuz = Util::verificarPasoEtapas($vigencia, $idRadicado, 6);
			$pasoFalPrimIns = Util::verificarPasoEtapas($vigencia, $idRadicado, 8);
			$pasoAvoConJuz = Util::verificarPasoEtapas($vigencia, $idRadicado, 13);
			$pasoSolVarPli = Util::verificarPasoEtapas($vigencia, $idRadicado, 17);
			$pasoTrasAleg = Util::verificarPasoEtapas($vigencia, $idRadicado, 25);
			$pasoConRecApe = Util::verificarPasoEtapas($vigencia, $idRadicado, 26);
			$pasoAutVarPliCarJuz = Util::verificarPasoEtapas($vigencia, $idRadicado, 30);
			$pasoRemSegIns = Util::verificarPasoEtapas($vigencia, $idRadicado, 31);
			$pasoRemExpCdi = Util::verificarPasoEtapas($vigencia, $idRadicado, 32);
			$pasoRecExpVarCar = Util::verificarPasoEtapas($vigencia, $idRadicado, 33);
			$pasoAutVarPliCar = Util::verificarPasoEtapas($vigencia, $idRadicado, 34);
			$pasoNoVarPliCar = Util::verificarPasoEtapas($vigencia, $idRadicado, 35);
			$pasoRemFunJuz = Util::verificarPasoEtapas($vigencia, $idRadicado, 36);
			$pasoTraVarPliCar = Util::verificarPasoEtapas($vigencia, $idRadicado, 37);
			$pasoPruVarJuz = Util::verificarPasoEtapas($vigencia, $idRadicado, 38);
			$pasoPruVar = Util::verificarPasoEtapas($vigencia, $idRadicado, 39);
			$pasoNulPliCar = Util::verificarPasoEtapas($vigencia, $idRadicado, 40);

			return View::make('procesos.diagrama-2')
					   ->with('vigencia', $vigencia)
					   ->with('idRadicado', $idRadicado)
					   ->with('etapaActual', $etapaActual)
					   ->with('pasoDecPruJuz', $pasoDecPruJuz)
					   ->with('pasoFalPrimIns', $pasoFalPrimIns)
					   ->with('pasoAvoConJuz', $pasoAvoConJuz)
					   ->with('pasoSolVarPli', $pasoSolVarPli)
					   ->with('pasoTrasAleg', $pasoTrasAleg)
					   ->with('pasoConRecApe', $pasoConRecApe)
					   ->with('pasoAutVarPliCar', $pasoAutVarPliCar)
					   ->with('pasoRemSegIns', $pasoRemSegIns)
					   ->with('pasoRemExpCdi', $pasoRemExpCdi)
					   ->with('pasoRecExpVarCar', $pasoRecExpVarCar)
					   ->with('pasoAutVarPliCarJuz', $pasoAutVarPliCarJuz)
					   ->with('pasoNoVarPliCar', $pasoNoVarPliCar)
					   ->with('pasoRemFunJuz', $pasoRemFunJuz)
					   ->with('pasoTraVarPliCar', $pasoTraVarPliCar)
					   ->with('pasoPruVarJuz', $pasoPruVarJuz)
					   ->with('pasoPruVar', $pasoPruVar)
					   ->with('pasoNulPliCar', $pasoNulPliCar);
		}
	}

	public function actionTraerFase()
	{
		//Trael el id de la etapa actual
		$etapas = DB::table('etapasproceso')
		           ->select('Etapa_idEtapa', 'tiposEtapa_idTipoEtapa')
		             ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
			   	    ->where('Radicado_vigencia', Input::get('vigencia'))
					->where('Radicado_idRadicado', Input::get('idRadicado'))
				    ->where('actual', '=', 1)//1 actual
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

		$idEtapa = 0;
		$faseActual = 0;

  		if(count($etapas) > 0)
  		{
  			$idEtapa = $etapas->Etapa_idEtapa;
			$faseActual = $etapas->tiposEtapa_idTipoEtapa;
  		} 

		//Si se desconoce la fase actual del proceso
		if (Input::get('fase') == 0) 
		{
			$fase = $faseActual;
		}
		else
		{
			$fase = Input::get('fase');
		}

		switch ($fase) 
		{
			case '1':
				$titulo = "Fase de Instrucción";
				break;
			case '2':
				$titulo = "Fase de Juzgamiento";
				break;
			case '3':
				$titulo = "Segunda Instancia";
			    break;
			default:
				$titulo = "F..";
				break;
		}

		$etapasFaseLinea = DB::table('etapasproceso')
				   ->select('actual', 'activo', 'idEtapa', 'nombreEtapa', 'nombreCorto', 'fechaEtapa')
				     ->join('radicado', function($join) {
						$join->on('etapasproceso.Radicado_idRadicado', '=', 'radicado.idRadicado')
						 ->on('etapasproceso.Radicado_vigencia', '=', 'radicado.vigencia');
					})
				 ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				->where('tiposEtapa_idTipoEtapa', $fase)
				->where('Radicado_idRadicado', '=', Input::get('idRadicado'))
				->where('Radicado_vigencia', '=', Input::get('vigencia'))
              ->orderBy('idEtapaProceso', 'asc')
			   	  ->get();
				
				/*

		$etapasFaseLinea = array();

		foreach ($etapasFase as $etapa) 
		{
			$completa = Util::verificarProgresoEtapas(Input::get('vigencia'), Input::get('idRadicado'), $etapa);
			$fechaInicio = Util::traerFechaEtapa(Input::get('vigencia'), Input::get('idRadicado'), $etapa);
			$nombreEtapa = Util::traerNombreCortoEtapaId($etapa);

			$datos = array("completa"    => $completa,
						   "fechaInicio" => $fechaInicio,
						   "nombreEtapa" => $nombreEtapa);

            array_push($etapasFaseLinea, $datos);
		}
		*/
		/*
		echo "<pre>";
		print_r($etapasFaseLinea);
		echo "</pre>";
		*/

		$tiposEtapa = DB::table('tiposetapa')
						  ->get();		

		$etapaActual = Util::traerNombreEtapaId($idEtapa);	

		//Siguientes etapas permitidas
		$etp = DB::table('etapa')
			    ->select('siguienteEtapa')
				 ->where('idEtapa', $idEtapa)
                 ->first();

		$array = explode('-', $etp->siguienteEtapa);

		$etapasSiguientes = json_decode(json_encode($array),TRUE);

		$etapas_siguiente = DB::table('etapa')
							->whereIn('idEtapa', $etapasSiguientes)
							->orderBy('nombreEtapa', 'asc')
 							  ->lists('nombreEtapa','idEtapa');

		return View::make('plantillas.ajaxFase')		
		           ->with('fase', Input::get('fase'))
				   ->with('titulo', $titulo)
				   ->with('etapasFaseLinea', $etapasFaseLinea)
				   ->with('tiposEtapa', $tiposEtapa)
				   ->with('idEtapa', $idEtapa)
				   ->with('etapas_siguiente', $etapas_siguiente)				   
				   ->with('etapaActual', $etapaActual)
				   ->with('actuacion', Input::get('actuacion'))
				   ->with('vigencia', Input::get('vigencia'))
				   ->with('idRadicado', Input::get('idRadicado'));
	}


}