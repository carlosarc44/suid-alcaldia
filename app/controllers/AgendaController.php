<?php

class AgendaController extends \BaseController 
{
	//## ADICIONAR ##
	public function actionAgendarTarea()
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
				  ->where('radicado.vigencia', '=', date('Y'))
				  ->orderBy('radicado.idRadicado', 'asc')
				  ->lists('idRadicado','idRadicado');

  		//Retorna la vista    	   
    	return View::make('plantillas.ajaxAdicionarEvento')
			    	->with("fechaInicio", Input::get("fechaInicio"))
			    	->with("fechaFinal", Input::get("fechaFinal"))
			    	->with("fechaSeleccionada", Input::get("fechaSeleccionada"))
			    	->with('lista_procesos', $lista_procesos);
	}

	public function actionGuardarAgendarTarea()
	{
		$idUsuario = Session::get('documentoUsuario');

		//Se crea una instancia de Tarea
        $tarea = new Tarea;
        $tarea->Radicado_idRadicado = Input::get("radProceso");
        $tarea->Radicado_vigencia = Input::get("vigProceso"); // Almacena el año actual        
        $tarea->asuntoTarea = Input::get("asuntoTarea");
        $tarea->lugarTarea = Input::get("lugarTarea");
        $tarea->descripcionTarea = Input::get("descripcionTarea");
        $tarea->fechaInicioTarea = Input::get("fechaInicio");
        $tarea->fechaFinTarea = Input::get("fechaFinal");
        $tarea->fechaProgramaTarea = date("Y-m-d H:i:s");// Fecha actual
        $tarea->todoElDiaTarea = 0;
        $tarea->color = Input::get("color");
        $tarea->Persona_documentoPersona = $idUsuario;
		$tarea->save();
    	return;
	}
	
	public function actionEditarAgendarTarea()
	{
		$idUsuario = Session::get('documentoUsuario');

		DB::table('tarea')
          	->where('Id', Input::get('idTarea'))
          	->update([
          			'Radicado_idRadicado' => Input::get('radProceso'),
					'Radicado_vigencia' => Input::get('vigProceso'),
					'asuntoTarea' => Input::get('asuntoTarea'),
					'lugarTarea' => Input::get('lugarTarea'),
					'descripcionTarea' => Input::get('descripcionTarea'),
					'color' => Input::get('color')
   			]);

    	return;
	}

	
	public function actionMostrarEditarTarea()
	{
		$idUsuario = Session::get('documentoUsuario');

		$tarea = Tarea::find(Input::get("idTarea"));

		$lista_procesos = DB::table('radicado')
				  ->join('abogadoasignado', function($join) 
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('abogado.Persona_documentoPersona', '=', $idUsuario)
				  ->where('radicado.vigencia', '=', $tarea->Radicado_vigencia)
				  ->orderBy('radicado.idRadicado', 'asc')
				  ->lists('idRadicado','idRadicado');



  		//Retorna la vista    	   
    	return View::make('plantillas.ajaxEditarEvento')
			    	->with("tarea", $tarea)
			    	->with('lista_procesos', $lista_procesos);
	}

	public function actionFinalizarTarea()
	{
		DB::table('tarea')
          	->where('Id', Input::get('idTarea'))
          	->update(['finalizadaTarea' => Input::get("valor")]);

    	$idUsuario = Session::get('documentoUsuario');
    	
		$fechaHoy = date("Y-m-d");

		$tareas = Tarea::where('Persona_documentoPersona', '=', $idUsuario)
			               ->where(DB::raw('substr(fechaInicioTarea, -19, 10)'), '=', $fechaHoy)
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

		return View::make('plantillas.ajaxPorcentaje')
		           ->with('cantTareas', count($tareas))
		           ->with('porcentaje', $porcentaje);
	}

	public function EnviarEmail2($job, $data)
    {
       	$mensaje = $data['message'];
       	$mensaje2 = $data['message2'];

        Mail::send('hello', array(), function ($m) use($mensaje, $mensaje2) 
		{
			$m->subject($mensaje." ".$mensaje2);
			$m->from(array('notificaciones@manizales.gov.co' => 'Notificaciones'));
			$m->to('carlos.ramirez@manizales.gov.co');
		});	

    	$job->delete();
    }
}