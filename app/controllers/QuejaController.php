<?php
class QuejaController extends \BaseController
{
	public function actionMostrarRadicarQueja()
	{
	   	//Trae todos los tipos de recepción de queja y los retorna en un array
	    $lista_tiposRecepcion = DB::table('tiporecepcionqueja')
	   						   ->orderBy('descTipoRecepcionQueja', 'asc')
								  ->lists('descTipoRecepcionQueja','idTipoRecepcionQueja');
								  
		$lista_dependencias = DB::table('dependencia')
	   						   ->orderBy('nombreDependencia', 'asc')
	   						   ->lists('nombreDependencia','idDependencia');

	   	//Trae todos los tipos de implicados
	    $lista_tiposImplicados = DB::table('tipoimplicado')
	   						   ->orderBy('descTipoImplicado', 'asc')
	   						   ->lists('descTipoImplicado','idTipoImplicado');

	   	//Trae todos los tipos de quejosos
	    $lista_tiposQuejosos = DB::table('tipoquejoso')
	   						   ->orderBy('descTipoQuejoso', 'asc')
	   						   ->lists('descTipoQuejoso','idTipoQuejoso');

	   	return View::make('quejas.radicarQueja')
				   ->with('lista_tiposRecepcion', $lista_tiposRecepcion)
				   ->with('lista_dependencias', $lista_dependencias)
  			  	   ->with('lista_tiposImplicados', $lista_tiposImplicados)
  			  	   ->with('lista_tiposQuejosos', $lista_tiposQuejosos)
  			  	   ->with('menuActivo', 'quejas');
	}

	public function actionMostrarRadicarInforme()
	{
	   	//Trae todos los tipos de recepción de queja y los retorna en un array
	    $lista_tiposRecepcion = DB::table('tiporecepcionqueja')
	   						   ->orderBy('descTipoRecepcionQueja', 'asc')
	   						   ->lists('descTipoRecepcionQueja','idTipoRecepcionQueja');

	   	//Trae todos los tipos de implicados
	    $lista_tiposImplicados = DB::table('tipoimplicado')
	   						   ->orderBy('descTipoImplicado', 'asc')
	   						   ->lists('descTipoImplicado','idTipoImplicado');

	   	//Trae todos los tipos de quejosos
	    $lista_tiposQuejosos = DB::table('tipoquejoso')
	   						   ->orderBy('descTipoQuejoso', 'asc')
	   						   ->lists('descTipoQuejoso','idTipoQuejoso');

	   	return View::make('quejas.radicarInforme')
  			  	   ->with('lista_tiposRecepcion', $lista_tiposRecepcion)
  			  	   ->with('lista_tiposImplicados', $lista_tiposImplicados)
  			  	   ->with('lista_tiposQuejosos', $lista_tiposQuejosos)
  			  	   ->with('menuActivo', 'quejas');
	}

	public function actionAgregarQuejoso()
	{
		  return View::make('plantillas.ajaxAgregarQuejoso')
		      	     ->with('idQueja', Input::get("idQueja"));
	}

	public function actionAgregarPresuntoResponsable()
	{
		  return View::make('plantillas.ajaxAgregarPresuntoResponsable')
		      	     ->with('idQueja', Input::get("idQueja"));
	}

	public function actionBuscarDocQuejoso()
    {
		$q = "+".preg_replace( '/\s(\w+)/', ' +\\1', Input::get('docQuejoso'));

		$personas = Persona::whereRaw("MATCH(nombre) AGAINST(? IN BOOLEAN MODE)", array($q))
							->orWhere("documentoPersona", Input::get('docQuejoso'))
							->orderBy(DB::raw('documentoPersona+nombre'), 'asc')
			  				    ->get();

		return View::make('plantillas.ajaxBuscarDocQuejoso')
				   ->with('docQuejoso', $q)
				   ->with('personas', $personas);
	}
	
	public function actionBuscarDocPersona()
    {
		$q = "+".preg_replace( '/\s(\w+)/', ' +\\1', Input::get('docPersona'));

		$personas = Persona::whereRaw("MATCH(nombre) AGAINST(? IN BOOLEAN MODE)", array($q))
							->orWhere("documentoPersona", Input::get('docPersona'))
							->orderBy(DB::raw('documentoPersona+nombre'), 'asc')
			  				    ->get();

		return View::make('plantillas.ajaxBuscarDocPersona')
				   ->with('docPersona', $q)
				   ->with('personas', $personas);
	}

	public function actionBuscarDocPresuntoResponsable()
    {
		$q = "+".preg_replace( '/\s(\w+)/', ' +\\1', Input::get('docPresuntoResponsable'));

		$personas = DB::table('funcionario')
				  	   ->leftJoin('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
					   ->leftJoin('dependencia', 'Funcionario.Dependencia_idDependencia', '=', 'Dependencia.idDependencia')
					   ->leftJoin('cargo', 'Funcionario.Cargo_idCargo', '=', 'Cargo.idCargo')
				   ->whereRaw("MATCH(nombre) AGAINST(? IN BOOLEAN MODE)", array($q))
					->orWhere("documentoPersona", Input::get('docPresuntoResponsable'))
					->orderBy(DB::raw('documentoPersona+nombre'), 'asc')
				        ->get();

		return View::make('plantillas.ajaxBuscarDocPresuntoResponsable')
				   ->with('docPresuntoResponsable', $q)
				   ->with('personas', $personas);
	} 
	
	public function actionSeleccionadoQuejoso()
    {
		$quejoso = new Quejoso;
		$quejoso->Queja_idQueja = Input::get("idQueja");
		$quejoso->Persona_documentoPersona = Input::get("documentoPersona");
		$quejoso->save();

		DB::table('queja')
		  ->where('idQueja', Input::get('idQueja'))
		 ->update(['anonimo' => 0]);//1 Quejoso Anónimo

		return;
	} 

	public function actionSeleccionadoPresuntoResponsable()
    {
		$idFuncionario = Util::traerIdFuncionario(Input::get("documentoPersona"));

		$presuntoResponsable = new PresuntoResponsable;
		$presuntoResponsable->Queja_idQueja = Input::get("idQueja");
		$presuntoResponsable->Funcionario_idFuncionario = $idFuncionario;
		$presuntoResponsable->save();

		return;
	} 
	
	public function actionQuejososQueja()
    {
		$quejosos = Util::traerQuejososPorQueja(Input::get("idQueja"));

		$vista1 = View::make('plantillas.ajaxQuejososQueja1')
					  ->with('quejosos', $quejosos)
					  ->with('idQueja', Input::get("idQueja"))
					  ->with('fijarOficio', Input::get("fijarOficio"))					  
					->render();

		$vista2 = View::make('plantillas.ajaxQuejososQueja2')
		  			  ->with('quejosos', $quejosos)
					  ->with('idQueja', Input::get("idQueja"))
					->render();
					
		$vista3 = View::make('plantillas.ajaxQuejososQueja3')
		  			  ->with('quejosos', $quejosos)
					  ->with('idQueja', Input::get("idQueja"))
					->render();
					
		$vista4 = View::make('plantillas.ajaxQuejososQueja4')
		   			  ->with('quejosos', $quejosos)
					  ->with('idQueja', Input::get("idQueja"))
					->render();

		return Response::json(array('vista1' => $vista1,
									'vista2' => $vista2,
									'vista3' => $vista3,
									'vista4' => $vista4));
	} 

	public function actionPresuntosResponsablesQueja()
    {
		$presuntosresponsables = DB::table('presuntoresponsable')
								->leftJoin('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
								->leftJoin('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
								->leftJoin('dependencia', 'funcionario.Dependencia_idDependencia', '=', 'dependencia.idDependencia')
								->leftJoin('cargo', 'funcionario.Cargo_idCargo', '=', 'cargo.idCargo')
					  			   ->where('Queja_idQueja', '=', Input::get("idQueja"))
									 ->get();

		$vista1 = View::make('plantillas.ajaxPresuntosResponsablesQueja1')
					  ->with('presuntosresponsables', $presuntosresponsables)
					  ->with('idQueja', Input::get("idQueja"))
					  ->with('fijarOficio', Input::get("fijarOficio"))
					->render();

		$vista2 = View::make('plantillas.ajaxPresuntosResponsablesQueja2')
		  			  ->with('presuntosresponsables', $presuntosresponsables)
					  ->with('idQueja', Input::get("idQueja"))
					->render();
			
		$vista3 = View::make('plantillas.ajaxPresuntosResponsablesQueja3')
					  ->with('presuntosresponsables', $presuntosresponsables)
					  ->with('idQueja', Input::get("idQueja"))
					->render();
					
		$vista4 = View::make('plantillas.ajaxPresuntosResponsablesQueja4')
					  ->with('presuntosresponsables', $presuntosresponsables)
					  ->with('idQueja', Input::get("idQueja"))
				    ->render();

		return Response::json(array('vista1' => $vista1,
									'vista2' => $vista2,
									'vista3' => $vista3,
									'vista4' => $vista4));
	} 
	
	public function actionVerQuejoso()
    {
		$quejoso = DB::table('quejoso')
				    ->select('nombre', 'documentoPersona', 'direccionCorrespondencia', 'ciudadCorrespondencia', 'telefono', 'telefono2', 'email')
			  	      ->join('persona', 'quejoso.Persona_documentoPersona', '=', 'persona.documentoPersona')
					 ->where('documentoPersona', '=', Input::get("documentoPersona"))
					 ->first();

		$departamentos = DB::table('departamento')
		 				 ->orderBy('nombreDepartamento', 'asc')
						     ->get();

		return View::make('plantillas.ajaxVerQuejoso')
				   ->with('quejoso', $quejoso)
				   ->with('departamentos', $departamentos);
	}
	
	public function actionEditarPersona()
    {
		$persona = DB::table('persona')
				    ->select('nombre', 'documentoPersona', 'direccionCorrespondencia', 'ciudadCorrespondencia', 'telefono', 'telefono2', 'email')
					 ->where('documentoPersona', '=', Input::get("documentoPersona"))
					 ->first();

		$departamentos = DB::table('departamento')
		 				 ->orderBy('nombreDepartamento', 'asc')
						     ->get();

		return View::make('plantillas.ajaxEditarPersona')
				   ->with('idQueja', Input::get("idQueja"))
				   ->with('persona', $persona)
				   ->with('departamentos', $departamentos);
	}

	public function actionVerPresuntoResponsable()
    {
		$presuntoresponsable = DB::table('funcionario')
							  ->leftJoin('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
							  ->leftJoin('dependencia', 'Funcionario.Dependencia_idDependencia', '=', 'Dependencia.idDependencia')
							  ->leftJoin('cargo', 'Funcionario.Cargo_idCargo', '=', 'Cargo.idCargo')
							     ->where("documentoPersona", Input::get('documentoPersona'))
							     ->first();

		$departamentos = DB::table('departamento')
		 				 ->orderBy('nombreDepartamento', 'asc')
							 ->get();
							 
		$cargos = DB::table('cargo')
				  ->orderBy('nombreCargo', 'asc')
					  ->get();

		$dependencias = DB::table('dependencia')
					    ->orderBy('nombreDependencia', 'asc')
						    ->get();

		return View::make('plantillas.ajaxVerPresuntoResponsable')
				   ->with('presuntoresponsable', $presuntoresponsable)
				   ->with('cargos', $cargos)
				   ->with('dependencias', $dependencias)
				   ->with('departamentos', $departamentos);
	} 

	public function actionModificarQuejoso()
    {
		//1 Quejoso nuevo (No es una modificación)
		if (Input::get('nuevo') ==  1) 
		{
			if (Input::get('documentoPersona') == '') 
			{
				$documento = Uuid::generate();
			} 
			else 
			{
				$documento = Input::get('documentoPersona');

				//Validación para documento que ya existe
				$persona = DB::table('persona')
						     ->where('documentoPersona', Input::get('documentoPersona'))
							 ->first();

				if (count($persona) > 0) 
				{
					$mensaje = 'Ya se encuentra registrado: '.$persona->nombre.' con este número de documento';
					return Response::json(array('error' => 1, 'mensaje' => $mensaje));
				}
			}
			
			//Guarda la persona
			$persona = new Persona;
			$persona->documentoPersona = $documento;
			$persona->nombre = Input::get('nombre');
			$persona->direccionResidencia = Input::get('direccionCorrespondencia');
			$persona->ciudadResidencia = Input::get('ciudadCorrespondencia');
			$persona->direccionCorrespondencia = Input::get('direccionCorrespondencia');
			$persona->ciudadCorrespondencia = Input::get('ciudadCorrespondencia');
			$persona->telefono = Input::get('telefono');
			$persona->telefono2 = Input::get('telefono2');
			$persona->email = Input::get('email');
			$persona->save();

			//Guarda el quejoso
			$quejoso = new Quejoso;
			$quejoso->Queja_idQueja = Input::get("idQueja");
			$quejoso->Persona_documentoPersona = $documento;
			$quejoso->save();
		} 
		else 
		{
			/*
				abogado 
				acumulaqueja
				acumularadicado
				archivo
				archivogenerado
				funcionario
				notificacion
				observacionesqueja
				observacionesradicado
				oficio
				permisostemporales
				quejasacumuladas
				quejoso
				registro
				solicitudauto
				tarea
				usuario
			*/

			//Si se va a cambiar el documento, actualiza el nuevo documento en otras tablas
			if (Input::get('documentoPersona') != Input::get('documentoPersonaField')) 
			{
				//Valida que el documento ingresado no pertenezca a otro usuario
				$persona = DB::table('persona')
						     ->where('documentoPersona', Input::get('documentoPersonaField'))
							 ->first();

				if (count($persona) > 0) 
				{
					$mensaje = 'Ya se encuentra registrado: '.$persona->nombre.' con este número de documento';
					return Response::json(array('error' => 1, 'mensaje' => $mensaje));
				}

				//Actualiza los datos de la persona incluído el campo documento
				DB::table('persona')
				->where('documentoPersona', Input::get('documentoPersona'))
				->update([
					'documentoPersona'         => Input::get('documentoPersonaField'),
					'nombre'                   => Input::get('nombre'),
					'direccionResidencia'      => Input::get('direccionCorrespondencia'),
					'ciudadResidencia'         => Input::get('ciudadCorrespondencia'),
					'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
					'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
					'telefono'                 => Input::get('telefono'),
					'telefono2'                => Input::get('telefono2'),
					'email'                    => Input::get('email')
				]);

				//Actualiza el funcionario
				DB::table('funcionario')
  				  ->where('Persona_documentoPersona', Input::get('documentoPersona'))
				 ->update(['Persona_documentoPersona'=> Input::get('documentoPersonaField')]);

				//Actualiza el quejoso
				DB::table('quejoso')
  				  ->where('Persona_documentoPersona', Input::get('documentoPersona'))
				 ->update(['Persona_documentoPersona'=> Input::get('documentoPersonaField')]);
			} 
			else //Si no se va a ctualizar el documento, sólo los demás campos:
			{
				//Actualiza los datos de la persona
				DB::table('persona')
				->where('documentoPersona', Input::get('documentoPersona'))
				->update([
					'nombre'                   => Input::get('nombre'),
					'direccionResidencia'      => Input::get('direccionCorrespondencia'),
					'ciudadResidencia'         => Input::get('ciudadCorrespondencia'),
					'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
					'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
					'telefono'                 => Input::get('telefono'),
					'telefono2'                => Input::get('telefono2'),
					'email'                    => Input::get('email')
				]);
			}						

			//Datos para el log
			$nuevoQuejoso = ['nombre'                   => Input::get('nombre'), 
							 'documentoPersona'         => Input::get('documentoPersonaField'),
							 'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
							 'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
							 'telefono'                 => Input::get('telefono'),
							 'telefono2'                => Input::get('telefono2'),
							 'email'                    => Input::get('email')];

			// ===== LOGS ===== //  	
			$accion = 5;//5 Modifica datos del quejoso
			$descripcion = "En la queja: ".Input::get('idQueja').", se modificaron los datos del quejoso: ".Input::get('quejoso')." por ".json_encode($nuevoQuejoso);
			Util::almacenaLog($accion, $descripcion);
			//-----------
		}
		
		$mensaje = 'Registro guardado con éxito';
		return Response::json(array('error' => 0, 'mensaje' => $mensaje));
	} 

	public function actionModificarPresuntoResponsable()
    {
		if (Input::get('documentoPersona') == '') 
		{
			$documento = Uuid::generate();
		} 
		else 
		{				
			$documento = Input::get('documentoPersonaField');				
		}

		//Datos para el log
		$nuevoPresuntoResponsable = [   
			'nombre'                    => Input::get('nombre'), 
			'documentoPersona'          => $documento,
			'direccionCorrespondencia'  => Input::get('direccionCorrespondencia'),
			'ciudadCorrespondencia'     => Input::get('ciudadCorrespondencia'),
			'telefono'                  => Input::get('telefono'),
			'telefono2'                 => Input::get('telefono2'),
			'email'                     => Input::get('email'),
			'Cargo_idCargo'             => Input::get('cargo'),
			'Dependencia_idDependencia' => Input::get('dependencia')
		];

		if (Input::get('nuevo') ==  1) //0 Presunto Responsable nuevo
		{
			//Validación para documento que ya existe
			$persona = DB::table('persona')
		 				 ->where('documentoPersona', $documento)
						 ->first();

			//Si no existe la persona			 	
			if (count($persona) == 0) 
			{
				//Guarda la persona
				$persona = new Persona;
				$persona->documentoPersona = $documento;
				$persona->nombre = Input::get('nombre');
				$persona->direccionResidencia = Input::get('direccionCorrespondencia');
				$persona->ciudadResidencia = Input::get('ciudadCorrespondencia');
				$persona->direccionCorrespondencia = Input::get('direccionCorrespondencia');
				$persona->ciudadCorrespondencia = Input::get('ciudadCorrespondencia');
				$persona->telefono = Input::get('telefono');
				$persona->telefono2 = Input::get('telefono2');
				$persona->email = Input::get('email');
				$persona->save();
			}						

			//Guarda el funcionario
			$funcionario = new Funcionario;
			$funcionario->Persona_documentoPersona = $documento;
			$funcionario->Cargo_idCargo = Input::get('cargo');
			$funcionario->Dependencia_idDependencia = Input::get('dependencia');
			$funcionario->save();

			//Guarda el presunto responsable
			$presuntoResponsable = new PresuntoResponsable;
			$presuntoResponsable->Queja_idQueja = Input::get("idQueja");
			$presuntoResponsable->Funcionario_idFuncionario = $funcionario->idFuncionario;
			$presuntoResponsable->save();
			
			// ===== LOGS ===== //  	
			$accion = 7;//7 Agrega un nuevo presunto responsable
			$descripcion = "En la queja: ".Input::get('idQueja').", se agrega: ".json_encode($nuevoPresuntoResponsable);
			Util::almacenaLog($accion, $descripcion);
		} 
		else 
		{
			//Si se va a cambiar el documento, actualiza el nuevo documento en otras tablas
			if (Input::get('documentoPersona') != Input::get('documentoPersonaField')) 
			{
				//1- Eliminar el presunto responsable actual
					$idFuncionario = Util::traerIdFuncionario(Input::get("documentoPersona"));

					DB::table('presuntoresponsable')
					  ->where('Queja_idQueja', Input::get('idQueja'))
					  ->where('Funcionario_idFuncionario', $idFuncionario)
					 ->delete();

				//2- Eliminar el funcionario actual (Si no se encuentra como presunto responsable en otras quejas)
					$preResp = DB::table('presuntoresponsable')
				   			     ->where('Funcionario_idFuncionario', $idFuncionario)
								   ->get();
						
					if(count($preResp) == 0)
					{
						//Elimina el funcionario
						DB::table('funcionario')
						->where('idFuncionario', $idFuncionario)
						->delete();
					}

				//3- Eliminar la persona actual (Si no se encuentra como quejoso en otras quejas)
					$quejosos = DB::table('quejoso')
							      ->where('Persona_documentoPersona', Input::get('documentoPersona'))
							        ->get();
		
					if(count($quejosos) == 0)
					{
						DB::table('persona')
						  ->where('documentoPersona', Input::get('documentoPersona'))
						 ->delete();
					}


				//4- Validar que el documento ingresado no pertenezca a otro usuario
				$persona = DB::table('persona')
						     ->where('documentoPersona', Input::get('documentoPersonaField'))
							 ->first();

				//Si no existe la persona			 	
				if (count($persona) == 0) 
				{
					//Guarda la persona
					$persona = new Persona;
					$persona->documentoPersona = Input::get('documentoPersonaField');
					$persona->nombre = Input::get('nombre');
					$persona->direccionResidencia = Input::get('direccionCorrespondencia');
					$persona->ciudadResidencia = Input::get('ciudadCorrespondencia');
					$persona->direccionCorrespondencia = Input::get('direccionCorrespondencia');
					$persona->ciudadCorrespondencia = Input::get('ciudadCorrespondencia');
					$persona->telefono = Input::get('telefono');
					$persona->telefono2 = Input::get('telefono2');
					$persona->email = Input::get('email');
					$persona->save();					
				}
				else //Si ya existe la persona, actualiza sus datos
				{
					//Actualiza los datos de la persona incluído el campo documento
					DB::table('persona')
					->where('documentoPersona', Input::get('documentoPersonaField'))
					->update([
						'nombre'                   => Input::get('nombre'),
						'direccionResidencia'      => Input::get('direccionCorrespondencia'),
						'ciudadResidencia'         => Input::get('ciudadCorrespondencia'),
						'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
						'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
						'telefono'                 => Input::get('telefono'),
						'telefono2'                => Input::get('telefono2'),
						'email'                    => Input::get('email')
						]);
				}

				//Valida si el funcionario ya está creado
				$idFuncionario = Util::traerIdFuncionario(Input::get("documentoPersonaField"));

				//Si no existe el funcionario 	
				if ($idFuncionario == 0) 
				{
					//Crea el funcionario
					$funcionario = new Funcionario;
					$funcionario->Persona_documentoPersona = Input::get("documentoPersonaField");
					$funcionario->Cargo_idCargo = Input::get('cargo');
					$funcionario->Dependencia_idDependencia = Input::get('dependencia');
					$funcionario->save();

					$idFuncNuevo = $funcionario->idFuncionario;
				}
				else // Si existe el funcionario
				{
					//Actualiza el funcionario
					DB::table('funcionario')
					  ->where('idFuncionario', $idFuncionario)
					 ->update([ 'Dependencia_idDependencia' => Input::get('dependencia'),
								'Cargo_idCargo'             => Input::get('cargo'),
								'correoFuncionario'         => Input::get('email')
							]);

					$idFuncNuevo = $idFuncionario;
				}

				//Si el presunto responsable ya está asociado a la queja no lo inserta
				$preResp = DB::table('presuntoresponsable')
				   			 ->where('Queja_idQueja', Input::get("idQueja"))
							 ->where('Funcionario_idFuncionario', $idFuncionario)
							   ->get();
						
				if(count($preResp) == 0)
				{
					//Guarda el presunto responsable
					$presuntoResponsable = new PresuntoResponsable;
					$presuntoResponsable->Queja_idQueja = Input::get("idQueja");
					$presuntoResponsable->Funcionario_idFuncionario = $idFuncNuevo;
					$presuntoResponsable->save();
				}
			} 
			else //Si no se va a ctualizar el documento, sólo los demás campos:
			{
				//Actualiza los datos de la persona
				DB::table('persona')
				  ->where('documentoPersona', Input::get('documentoPersona'))
				 ->update(['nombre'                   => Input::get('nombre'),
					   	   'direccionResidencia'      => Input::get('direccionCorrespondencia'),
						   'ciudadResidencia'         => Input::get('ciudadCorrespondencia'),
						   'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
						   'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
						   'telefono'                 => Input::get('telefono'),
						   'telefono2'                => Input::get('telefono2'),
						   'email'                    => Input::get('email')
						]);
			}		
			
			//Actualiza el funcionario
			DB::table('funcionario')
			  ->where('Persona_documentoPersona', Input::get("documentoPersona"))
		     ->update(['Dependencia_idDependencia' => Input::get('dependencia'),
					   'Cargo_idCargo'             => Input::get('cargo'),
					   'correoFuncionario'         => Input::get('email')
				     ]);

			//Datos para el log
			$nuevoPresuntoResponsable = [   'nombre'                    => Input::get('nombre'), 
											'documentoPersona'          => Input::get('documentoPersonaField'),
											'direccionCorrespondencia'  => Input::get('direccionCorrespondencia'),
											'ciudadCorrespondencia'     => Input::get('ciudadCorrespondencia'),
											'telefono'                  => Input::get('telefono'),
											'telefono2'                 => Input::get('telefono2'),
											'email'                     => Input::get('email'),
											'Cargo_idCargo'             => Input::get('cargo'),
											'Dependencia_idDependencia' => Input::get('dependencia')
			                            ];

			// ===== LOGS ===== //  	
			$accion = 6;//6 Modifica datos del presunto responsable
			$descripcion = "En la queja: ".Input::get('idQueja').", se modificaron los datos del presunto responsable: ".Input::get('presuntoResponsable')." por ".json_encode($nuevoPresuntoResponsable);
			Util::almacenaLog($accion, $descripcion);
		}

		$mensaje = 'Registro guardado con éxito';
		return Response::json(array('error' => 0, 'mensaje' => $mensaje));
	} 
	
	public function actionQuitarQuejoso()
    {
		DB::table('quejoso')
		  ->where('Queja_idQueja', Input::get('idQueja'))
		  ->where('Persona_documentoPersona', Input::get('documentoPersona'))
         ->delete();
		
		return;
	}

	public function actionQuitarPresuntoResponsable()
    {
		//1- Eliminar el presunto responsable actual
		$idFuncionario = Util::traerIdFuncionario(Input::get("documentoPersona"));

		DB::table('presuntoresponsable')
		  ->where('Queja_idQueja', Input::get('idQueja'))
		  ->where('Funcionario_idFuncionario', $idFuncionario)
		 ->delete();

		//2- Eliminar el funcionario actual (Si no se encuentra como presunto responsable en otras quejas)
		$preResp = DB::table('presuntoresponsable')
					 ->where('Funcionario_idFuncionario', $idFuncionario)
					   ->get();
			
		if(count($preResp) == 0)
		{
			//Elimina el funcionario
			DB::table('funcionario')
			  ->where('idFuncionario', $idFuncionario)
			 ->delete();
		}

		//3- Eliminar la persona actual (Si no se encuentra como quejoso en otras quejas)
		$quejosos = DB::table('quejoso')
					  ->where('Persona_documentoPersona', Input::get('documentoPersona'))
						->get();

		if(count($quejosos) == 0)
		{
			DB::table('persona')
			  ->where('documentoPersona', Input::get('documentoPersona'))
			 ->delete();
		}

		return;
	}

	public function actionNuevoQuejoso()
	{
		$departamentos = DB::table('departamento')
		 				 ->orderBy('nombreDepartamento', 'asc')
						     ->get();

		return View::make('plantillas.ajaxNuevoQuejoso')
				   ->with('departamentos', $departamentos);
	} 

	public function actionNuevoPresuntoResponsable()
	{
		$departamentos = DB::table('departamento')
		 				 ->orderBy('nombreDepartamento', 'asc')
							 ->get();
							 
		$cargos = DB::table('cargo')
				  ->orderBy('nombreCargo', 'asc')
					  ->get();

		$dependencias = DB::table('dependencia')
					    ->orderBy('nombreDependencia', 'asc')
						    ->get();

		return View::make('plantillas.ajaxNuevoPresuntoResponsable')
				   ->with('cargos', $cargos)
				   ->with('dependencias', $dependencias)
				   ->with('departamentos', $departamentos);
	} 






	public function actionMostrarAgregarInformante()
	{
	   	$entidades = DB::table('entidad')
	   				   ->where('idEntidad', '>', 2)
	   				   ->orderBy('nombreEntidad')
					   ->get();

  		return View::make('plantillas.ajaxSeleccionarInformante')
  			  	   ->with('entidades', $entidades);
	}

	public function actionMostrarAgregarPR()
	{
	   	$funcionarios = DB::table('Funcionario')
		->join('Persona', 'Funcionario.Persona_documentoPersona', '=', 'Persona.documentoPersona')
		->join('Dependencia', 'Funcionario.Dependencia_idDependencia', '=', 'Dependencia.idDependencia')
		->join('Cargo', 'Funcionario.Cargo_idCargo', '=', 'Cargo.idCargo')
		->get();

  		return View::make('plantillas.ajaxSeleccionarPR')
  			  	   ->with('funcionarios', $funcionarios);
	}

	public function actionGuardarQueja()
	{
        $queja                                 = new Queja;
        $queja->OrigenQueja_idOrigenQueja      = Input::get("origenQueja");
        $queja->EstadoQueja_idEstadoQueja      = 1;//1 Queja Radicada
		$queja->TipoRecepcionQueja_idTipoQueja = Input::get("tipoRecepcion");
		$queja->dependencia_idDependencia      = Input::get("dependenciaQueja");
        $queja->fechaQueja                     = Input::get("fechaQueja");
        $queja->fechaRecepcionQueja            = Input::get("fechaRecepcion");
        $queja->numeroOficio                   = Input::get("numeroOficio");
        $queja->presuntosHechos                = Input::get("presuntosHechos");
        $queja->presuntoLugar                  = Input::get("presuntoLugar");
		$queja->save();

		return $queja->idQueja;
	}

	public function actionValidarEditarQueja()
	{
		DB::table('queja')
          ->where('idQueja', Input::get('idQueja'))
         ->update([
                    'OrigenQueja_idOrigenQueja'      => Input::get('origenQueja'),
					'TipoRecepcionQueja_idTipoQueja' => Input::get('tipoRecepcion'),
					'dependencia_idDependencia'      => Input::get('dependenciaQueja'),
                    'fechaQueja'                     => Input::get('fechaQueja'),
                    'fechaRecepcionQueja'            => Input::get('fechaRecepcion'),
                    'numeroOficio'                   => Input::get('numeroOficio'),
                    'presuntosHechos'                => Input::get('presuntosHechos'),
                    'presuntoLugar'                  => Input::get('presuntoLugar')
				]);
					
       return;
	}

	public function actionQuejasEnviar()
	{
	   	$quejas = DB::table('queja')
						->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
						->where('queja.EstadoQueja_idEstadoQueja', '=', 1)//1 Queja radicada
						->orderBy('queja.idQueja', 'desc')
						->get();

  		return View::make('quejas.quejasEnviar')
  			  	   ->with('quejas', $quejas)
  			  	   ->with('menuActivo', 'quejas');
	}

	public function actionMostrarConProceso()
	{
  		return View::make('quejas.quejasConProceso')
  			  	   ->with('menuActivo', 'quejas');
	}

	public function actionEstadosQuejas()
	{
		$lista_estados = DB::table('estadoqueja')
                         ->orderBy('nombreCorto', 'desc')
	   					   ->lists('nombreCorto','idEstadoQueja');

  		return View::make('quejas.estadosQuejas')
  			  	   ->with('menuActivo', 'director')
  			  	   ->with('lista_estados', $lista_estados);
					   
	}

	public function actionTraerQuejasConProceso($vigencia)
	{
	   	$quejas = DB::table('queja')
   	                ->leftJoin('acumulaqueja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
   	                ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->leftJoin('abogadoasignado', function($join)
					 	{
				       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'acumulaqueja.Radicado_idRadicado')
				            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'acumulaqueja.Radicado_vigencia')
				            	 ->where('abogadoasignado.actual', '=', 'SI');
				    	})
					->leftJoin('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
					->leftJoin('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')

					//->where(DB::raw('substr(queja.fechaQueja, -10, 4)'), '=', $vigencia)
					//->where(DB::raw('substr(acumulaqueja.fechaAcumula, -10, 4)'), '=', $vigencia)
					->where(DB::raw('substr(abogadoasignado.fechaAsignacion, -10, 4)'), '=', $vigencia)
					//->orderBy('acumulaqueja.Queja_idQueja', 'asc')
					->get();
					//echo count($quejas); return;

  		return View::make('plantillas.ajaxQuejasConProceso')
  			  	   ->with('quejas', $quejas);
	}
	
	public function actionConsultarRemisionesCompetencia()
	{
		$remisiones = DB::table('remisioncompetencia')
		             ->leftJoin('tiporemision', 'remisioncompetencia.TipoRemision_idTipoRemision', '=', 'tiporemision.idTipoRemision')
		                 ->join('queja', 'remisioncompetencia.Queja_idQueja', '=', 'queja.idQueja')
				     ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					 ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				 ->whereBetween('fechaRemisionCompetencia', [Input::get('fechaInicio'), Input::get('fechaFin')])
				          ->get();

		return View::make('plantillas.ajaxRemisionesCompetencia')
				   ->with('remisiones', $remisiones);
	}

	public function actionConsultarEstadosQueja()
	{
		$quejas = DB::table('queja')
		 		 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
   	             ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja');

		if (!in_array(0, Input::get('estado')))
		{
			$quejas->whereIn('queja.EstadoQueja_idEstadoQueja', Input::get('estado'));
		}

		$quejas->whereBetween('queja.fechaRecepcionQueja', [Input::get('fechaInicio'), Input::get('fechaFin')]);
		$result = $quejas->get();

		// Retorna parámetros para el botón del excel
		$cadenaVectorEstados = implode(';', Input::get('estado')); 

		$vistaConsultarEstadosQueja = View::make('plantillas.ajaxConsultarEstadosQueja')
									  	  ->with('quejas', $result)
										  ->with('fechaInicio', Input::get('fechaInicio'))
										  ->with('fechaFin', Input::get('fechaFin'))
										  ->with('cadenaVectorEstados', $cadenaVectorEstados)	
										->render();

		//Gráfica 1
		$tipos1   = array();
		$colores1 = array();
		$valores1 = array();

		//Consulta todos los estados queja
		$listaEstadosQueja = DB::table('estadoqueja')
						      ->select('idEstadoQueja', 'nombreCorto')
    					         ->get();

		foreach ($listaEstadosQueja as $le) 
		{
			//Consulta la cantidad de quejas en cada uno de los estados
			$quejasGrafica = DB::table('queja')
							  ->select('idQueja')
							->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
							->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
							->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja');
			$quejasGrafica->where('queja.EstadoQueja_idEstadoQueja', $le->idEstadoQueja);
			$quejasGrafica->whereBetween('queja.fechaRecepcionQueja', [Input::get('fechaInicio'), Input::get('fechaFin')]);
			$resultGrafica = $quejasGrafica->count();
						
			array_push($tipos1,  $le->nombreCorto);
			array_push($colores1, sprintf("#%06x",rand(0,16777215)));
			array_push($valores1, $resultGrafica);
		}
				   
		return Response::json(array('vistaConsultarEstadosQueja' => $vistaConsultarEstadosQueja,
												   'fechaInicio' => Util::formatearFechaCorta(Input::get('fechaInicio')),
												      'fechaFin' => Util::formatearFechaCorta(Input::get('fechaFin')),
														'tipos1' => $tipos1,
											  		  'colores1' => $colores1,
													  'valores1' => $valores1));
	}

	public function actionExcelEstadosQueja($fechaInicio, $fechaFin, $cadenaVectorEstados)
	{
        $estados = explode(';', $cadenaVectorEstados);

		$quejas = DB::table('queja')
		 		 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
   	             ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja');

		if (!in_array(0, $estados))
		{
			$quejas->whereIn('queja.EstadoQueja_idEstadoQueja', $estados);
		}

		$quejas->whereBetween('queja.fechaRecepcionQueja', [$fechaInicio, $fechaFin]);
		$result = $quejas->get();

		//Texto para las etapas		
		if (in_array(0, $estados))
		{
			$txtEtapa = 'todas los estados';
		} 
		else 
		{
			$est = '';
			for ($i = 0; $i < count($estados); $i++) 
			{ 
				$est .= ucwords(Util::traerNombreCortoEstadoId($estados[$i])).", ";
			}

			if (count($estados) == 1) 
			{
				$txtEtapa = 'el estado: '.trim($est, ', '); 
			} 
			else 
			{
				$txtEtapa = 'los estados: '.trim($est, ', '); 
			}
		}

		$tituloExcel = count($result).' quejas desde el: '.$fechaInicio.' hasta el: '.$fechaFin;

        Excel::create($tituloExcel, function($excel) use ($tituloExcel, $result) {
			// Set the title
			$excel->setTitle('Informe estados queja');
			// Chain the setters
			$excel->setCreator('Oficina de Control Disciplinario Interno');
			$excel->setCompany('Alcaldía de Manizales');

			// Call them separately
			$excel->setDescription($tituloExcel);
			$excel->sheet('Informe', function($sheet) use ($tituloExcel, $result) {
			
			$sheet->cells('D1:D2', function($cells)
			{
				$cells->setAlignment('center');
			});
			
			$sheet->fromArray(array(
				array($tituloExcel, '')), null, 'C1', false, false);

				$sheet->loadView('quejas.excelEstadosQueja')
						->with('quejas', $result)
						->with('tituloExcel', $tituloExcel);
				});
        })->export('xlsx');		
	}

	public function actionMostrarTodas()
	{
  		return View::make('quejas.quejasTodas')
  			  	   ->with('menuActivo', 'quejas');
	}

	public function actionRemisionesPorCompetencia()
	{
  		return View::make('quejas.remisionesCompetencia')
  			  	   ->with('menuActivo', 'director');
	}

	public function actionTraerQuejasTodas($vigencia)
	{
		$quejas = DB::table('queja')
		 		 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
   	             ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					//->where('queja.EstadoQueja_idEstadoQueja', '!=', )
				    ->where(DB::raw('substr(queja.fechaRecepcionQueja, -10, 4)'), '=', $vigencia)
					  ->get();

  		return View::make('plantillas.ajaxQuejasTodas')
  			  	   ->with('quejas', $quejas);
	}

	public function actionMisQuejas()
	{
		/*
	   	$quejas = DB::table('queja')
   	                ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
					->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					//->where('queja.EstadoQueja_idEstadoQueja', '!=', )
					->where(DB::raw('substr(queja.fechaRecepcionQueja, -10, 4)'), '=', Input::get('vigencia'))
					->get();
*/
		$usuario = Session::get('documentoUsuario');

		  $quejas = DB::table('radicado')
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
					   ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				   ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				   ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				       ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				      ->where('radicado.activo', '=', 1)
					  //->where('radicado.Etapa_idEtapa', '=', $etapa)
					  ->where('abogado.Persona_documentoPersona', '=', $usuario)
				    ->groupBy('radicado.idRadicado')
					->groupBy('radicado.vigencia')
				        ->get();

		
  		return View::make('plantillas.ajaxQuejasTodas')
  			  	   ->with('quejas', $quejas);
	}

	public function actionQuejasReparto()
	{
	   	$quejas = DB::table('queja')
						->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
						->where('queja.EstadoQueja_idEstadoQueja', '=', 5)//5 Queja enviada a reparto
						->orderBy('queja.idQueja', 'desc')
						->get();

		$abogados = DB::table('abogado')
						->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						->where('abogado.activo', 1)//1 Activo
						->where('abogado.juzgamiento', 0)//0 No prtenecen a la secretaría jurídica - Fase Juzgamiento
						->orderBy('persona.nombre', 'asc')
						->get();

  		return View::make('quejas.quejasReparto')
  			  	    ->with('quejas', $quejas)
  			  	    ->with('abogados', $abogados)
  			  	    ->with('menuActivo', 'quejas');
	}

	public function actionEnviarSeleccionadas()
	{
		$numeros = json_decode(Input::get('jsonSeleccionados'), true);
		$cant = count($numeros);

	   	for($i = 0; $i < $cant; $i++)
	    {
	      	//Pone el campo último en 0 para todos los registros
			DB::table('queja')
              ->where('idQueja', $numeros[$i])
              ->update(['EstadoQueja_idEstadoQueja' => 5]);//5 Queja en Reparto
			//--------------------------------------------------

	      	//Estado------------------------------------------------------------
	      	$observacionQueja = new ObservacionQueja;
	      	$observacionQueja->EstadoQueja_idEstadoQueja = 5;//5 Queja en reparto
	      	$observacionQueja->Queja_idQueja = $numeros[$i];
	      	$observacionQueja->Persona_documentoPersona = Session::get('documentoUsuario');
	      	$observacionQueja->observacion = "Envía a reparto la queja número ".$numeros[$i];
	      	$observacionQueja->fechaObservacion = date("Y-m-d");//Obtiene la fecha actual
	      	$observacionQueja->horaObservacion = date("H:i:s");//Obtiene la hora actual
	      	$observacionQueja->save();
	      	//------------------------------------------------------------------
	   }

	   $quejas = DB::table('queja')
						->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
						->where('queja.EstadoQueja_idEstadoQueja', '=', 1)//1 Queja radicada
						->orderBy('queja.idQueja', 'desc')
						->get();

		return View::make('plantillas.ajaxQuejasEnviar')
				   ->with('quejas', $quejas);
	}

	public function actionAnonimo()
	{
		DB::table('queja')
		  ->where('idQueja', Input::get('idQueja'))
		 ->update(['anonimo' => 1]);//1 Quejoso Anónimo

		return 1;
	}

	public function actionPorDeterminar()
	{
		DB::table('queja')
		  ->where('idQueja', Input::get('idQueja'))
		 ->update(['porDeterminar' => 1]);//1 Presunto responsable por determinar

		return 1;
	}

	public function actionVerQueja()
	{
		$queja = DB::table('queja')
					->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->join('tiporecepcionqueja', 'queja.TipoRecepcionQueja_idTipoQueja', '=', 'tiporecepcionqueja.idTipoRecepcionQueja')
				->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				   ->where('queja.idQueja', '=', Input::get('idQueja'))
				   ->first();
		
		$quejosos = Util::traerQuejososPorQueja(Input::get('idQueja'));
		$presuntos = Util::traerPresuntosResponsablesPorQueja(Input::get('idQueja'));

		return View::make('plantillas.ajaxVerQueja')
				   ->with('multiples', Input::get('multiples'))
				   ->with('queja', $queja)
				   ->with('quejosos', $quejosos)
				   ->with('presuntos', $presuntos);
	}

	public function actionCaratula($idQueja)
	{
		$templateWord = new \PhpOffice\PhpWord\TemplateProcessor('plantillas/portadas/portadaQueja.docx');

		$queja = DB::table('queja')
					->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->join('tiporecepcionqueja', 'queja.TipoRecepcionQueja_idTipoQueja', '=', 'tiporecepcionqueja.idTipoRecepcionQueja')
					->where('queja.idQueja', '=', $idQueja)
					->orderBy('queja.idQueja', 'desc')
					->get();
		$quejosos = Util::traerQuejososPorQueja($idQueja);

		$presuntos = DB::table('presuntoresponsable')
						->join('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
						->join('dependencia', 'funcionario.Dependencia_idDependencia', '=', 'dependencia.idDependencia')
					    ->join('cargo', 'funcionario.Cargo_idCargo', '=', 'cargo.idCargo')
						->join('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
					   ->where('Queja_idQueja', '=', $idQueja)
						 ->get();

		//Datos Oficio
		$numQueja =  $queja[0]->idQueja;
		$fechaQueja =  $queja[0]->fechaQueja;
		$fechaRecepcion =  $queja[0]->fechaRecepcionQueja;
		$numeroOficio =  $queja[0]->numeroOficio;
		//----------------------------------------------

		//Presuntos Responsables
		if (count($presuntos) > 0)
		{
			if (count($presuntos) == 1)
			{
				$presuntoResponsable = $presuntos[0]->nombre;
			}
			else
			{
				$presuntoResponsable = $presuntos[0]->nombre." y ".(count($presuntos)-1)." más.";
			}

			$dependenciaResponsable =  $presuntos[0]->nombreDependencia;
			$cargoResponsable =  $presuntos[0]->nombreCargo;
		}
		else
		{
			$presuntoResponsable = "Por determinar";
			$dependenciaResponsable = "Por determinar";
			$cargoResponsable = "Por determinar";
		}

		//----------------------------------------------------------------------------------

		//Quejoso
		if (count($quejosos) > 0)
		{
			if (count($quejosos) == 1)
			{
				$quejoso = $quejosos[0]->nombre;
			}
			else
			{
				$quejoso = $quejosos[0]->nombre." y ".(count($quejosos)-1)." más.";
			}
		}
		else
		{
			$quejoso = "Anónimo";
		}
		//----------------------------------------------------------------------------------

		//Presuntos hechos
		$presuntaFecha = 'Por determinar';
		$presuntoLugar = $queja[0]->presuntoLugar;
		$presuntosHechos = $queja[0]->presuntosHechos;
		//----------------------------------------------

		// --- Asignamos valores a la plantilla
		$templateWord->setValue('numQueja', $numQueja);
		$templateWord->setValue('fechaQueja', date_format(date_create($fechaQueja), "d/m/Y"));
		$templateWord->setValue('fechaRecepcion', date_format(date_create($fechaRecepcion), "d/m/Y"));
		$templateWord->setValue('numeroOficio', $numeroOficio);
		$templateWord->setValue('presuntoResponsable', $presuntoResponsable);
		$templateWord->setValue('dependenciaResponsable', $dependenciaResponsable);
		$templateWord->setValue('cargoResponsable', $cargoResponsable);
		$templateWord->setValue('quejoso', $quejoso);
		$templateWord->setValue('presuntaFecha', $presuntaFecha);
		$templateWord->setValue('presuntoLugar', $presuntoLugar);
		$templateWord->setValue('presuntosHechos', $presuntosHechos);

		// --- Guardamos el documento
		$templateWord->saveAs('Portada queja '.$numQueja.'.docx');

		header("Content-Disposition: attachment; filename=Portada queja ".$numQueja.".docx; charset=iso-8859-1");
		echo file_get_contents('Portada queja '.$numQueja.'.docx');

		Unlink('Portada queja '.$numQueja.'.docx');
	}

	public function actionEditarQueja()
	{
		//PENDIENTE IMPLEMENTAR
		$queja = DB::table('queja')
					->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
					->join('tiporecepcionqueja', 'queja.TipoRecepcionQueja_idTipoQueja', '=', 'tiporecepcionqueja.idTipoRecepcionQueja')
				   ->where('queja.idQueja', '=', Input::get('idQueja'))
				   ->first();

	    $lista_origenes = DB::table('origenqueja')
	   					  ->orderBy('nombreOrigenQueja', 'asc')
						    ->lists('nombreOrigenQueja','idOrigenQueja');

		$lista_tiposRecepcion = DB::table('tiporecepcionqueja')
	   	 					    ->orderBy('descTipoRecepcionQueja', 'asc')
								  ->lists('descTipoRecepcionQueja','idTipoRecepcionQueja');
								  
		$lista_dependencias = DB::table('dependencia')
	   						   ->orderBy('nombreDependencia', 'asc')
	   						   ->lists('nombreDependencia','idDependencia');
		
			return View::make('plantillas.ajaxEditarQueja')
					   ->with('multiples', Input::get('multiples'))
					   ->with('queja', $queja)
					   ->with('lista_origenes', $lista_origenes)
					   ->with('lista_tiposRecepcion', $lista_tiposRecepcion)
					   ->with('lista_dependencias', $lista_dependencias);		
	}

	public function actionAcumularQueja($idQueja)
	{
		$queja = DB::table('queja')
						->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
						->join('tiporecepcionqueja', 'queja.TipoRecepcionQueja_idTipoQueja', '=', 'tiporecepcionqueja.idTipoRecepcionQueja')
						->where('queja.idQueja', '=', $idQueja)
						->first();

		return View::make('quejas.acumularQueja')
					   ->with('queja', $queja)
					   ->with('menuActivo', 'quejas');
	}

	public function actionRemitirQueja($idQueja)
	{
		$idUsuario = Session::get('documentoUsuario');

		$queja = DB::table('queja')
						->join('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
						->join('tiporecepcionqueja', 'queja.TipoRecepcionQueja_idTipoQueja', '=', 'tiporecepcionqueja.idTipoRecepcionQueja')
						->where('queja.idQueja', '=', $idQueja)
						->get();

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

		//Trae todos los departamentos y los retorna en un array
	    $lista_departamentos = DB::table('departamento')
	   						   ->orderBy('nombreDepartamento', 'asc')
	   						   ->lists('nombreDepartamento','idDepartamento');

	   	$entidades = DB::table('entidadremision')
					   ->get();

		return View::make('quejas.remitirQueja')
					   ->with('queja', $queja)
					   ->with('menuActivo', 'quejas')
					   ->with('numeroOficio', $numeroOficio + 1)
					   ->with('remisionCompetencia', $remisionCompetencia + 1)
					   ->with('iniciales', $iniciales)
					   ->with('lista_departamentos', $lista_departamentos)
					   ->with('entidades', $entidades);
	}

	public function actionGuardarAcumularQueja()
	{
		$fechaHoy = date("Y-m-d");
		$usuario = Session::get('documentoUsuario');

		// Divido el string de nombre de personas por los espacios
		$proceso = explode("-", Input::get('proceso'));

		$vigencia = $proceso[0]; // idProceso
		$idProceso = $proceso[1]; // vigencia

		//Almacena AcumulaQueja
        $acumulaQueja = new AcumulaQueja;
        $acumulaQueja->Queja_idQueja = Input::get('idQueja');
        $acumulaQueja->Radicado_idRadicado = $idProceso;
        $acumulaQueja->Radicado_Vigencia = $vigencia;
        $acumulaQueja->Persona_documentoPersona = $usuario;
        $acumulaQueja->fechaAcumula = $fechaHoy;// Fecha actual
        $acumulaQueja->horaAcumula = date('g:i a'); // Hora actual
        $acumulaQueja->save();

        //Actualiza el estado de la queja
		DB::table('queja')
          ->where('idQueja', Input::get('idQueja'))
          ->update(['EstadoQueja_idEstadoQueja' => 3]);// Estado 3 (Queja Acumulada)
		//------------------------------------------------

        //Almacena ObservacionesQueja
        $observacionQueja = new ObservacionQueja;
        $observacionQueja->EstadoQueja_idEstadoQueja = 6;// 6 Queja con Radicado asignado
        $observacionQueja->Queja_idQueja = Input::get('idQueja');//Queja almacenada en el vector
        $observacionQueja->Persona_documentoPersona = $usuario;
        $observacionQueja->observacion = "Se acumuló la queja ".Input::get('idQueja')." al radicado ".$vigencia."-".$idProceso;
        $observacionQueja->fechaObservacion = $fechaHoy;// Fecha actual
        $observacionQueja->horaObservacion = date('g:i a'); // Hora actual
        $observacionQueja->save();

        //Almacena ObservacionesRadicado
        $observacionRadicado  = new ObservacionRadicado;
        $observacionRadicado->EstadoRadicado_idEstadoRadicado = 46;// 46 QUEJA ACUMULADA A ESTE PROCESO
        $observacionRadicado->Radicado_idRadicado = $idProceso;
        $observacionRadicado->Radicado_vigencia = $vigencia; // Almacena el año actual
        $observacionRadicado->Persona_documentoPersona = $usuario;
        $observacionRadicado->observacion = "Se acumuló a este proceso la queja número: ".Input::get('idQueja');
        $observacionRadicado->fechaObservacion = $fechaHoy;
        $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
        $observacionRadicado->save();

        $quejasAcumulada = new QuejaAcumulada;
        $quejasAcumulada->Queja_idQueja = Input::get('idQueja');
        $quejasAcumulada->Radicado_idRadicado = $idProceso;
        $quejasAcumulada->Radicado_vigencia = $vigencia;
        $quejasAcumulada->fechaAcumula = $fechaHoy;
        $quejasAcumulada->personaEntrega = $usuario;
        $quejasAcumulada->personaRecibe = Util::traerDocumentoAbogadoAsignado($vigencia, $idProceso);
        $quejasAcumulada->motivo = Input::get('motivo');
        $quejasAcumulada->save();

        //** Parámetro Valorar **
        $parametro = Parametro::find(20); // 20 (Tiempo en días hábiles que tiene el profesional para valorar un proceso)
        $diasValorar = $parametro->valorParametro;
        //Calcula la fecha final en la que debe valorar el proceso
        $fechaValorar = Util::obtenerFechaFinalHabiles($fechaHoy, $diasValorar, 1); // 1 días hábiles
        //** ------------------ **

        //****** Programa la tarea para el Profesional asignado (Valorar el Proceso que se acumuló)
        $tarea = new Tarea;
        $tarea->Radicado_idRadicado = $idProceso;
        $tarea->Radicado_vigencia = $vigencia; // Almacena el año actual
        $tarea->asuntoTarea = "Revisar queja acumulada";
        $tarea->lugarTarea = "Oficina de Control Disciplinario Interno";
        $tarea->descripcionTarea = "Revisar el proceso ".$vigencia."-".$idProceso." dentro del cuál se encuentra una nueva queja con número ".Input::get('idQueja')." que fué acumulada por el siguiente motivo: ".Input::get('motivo');
        $tarea->fechaInicioTarea = $fechaValorar." ".date('H:i:s', strtotime("17:00:00"));
        $tarea->fechaFinTarea = $fechaValorar." ".date('H:i:s', strtotime("18:00:00"));
        $tarea->fechaProgramaTarea = $fechaHoy." ".date('H:i:s');// Fecha actual
        $tarea->todoElDiaTarea = 0;
        $tarea->color = 6;
        $tarea->finalizadaTarea = 0;
        $tarea->Persona_documentoPersona = Util::traerDocumentoAbogadoAsignado($vigencia, $idProceso);
        $tarea->save();

        //Segunda tarea:  Proferir auto mediante el cual se acumula una queja o informe
        $tarea = new Tarea;
        $tarea->Radicado_idRadicado = $idProceso;
        $tarea->Radicado_vigencia = $vigencia; // Almacena el año actual
        $tarea->asuntoTarea = "Proferir auto - acumulación queja";
        $tarea->lugarTarea = "Oficina de Control Disciplinario Interno";
        $tarea->descripcionTarea = "Proferir Auto por medio del cual se acumula una queja o informe por conexidad procesal en el proceso ".$vigencia."-".$idProceso." dentro del cuál se acumuló una nueva queja número ".Input::get('idQueja');
        $tarea->fechaInicioTarea = $fechaValorar." ".date('H:i:s', strtotime("17:00:00"));
        $tarea->fechaFinTarea = $fechaValorar." ".date('H:i:s', strtotime("18:00:00"));
        $tarea->fechaProgramaTarea = $fechaHoy." ".date('H:i:s');// Fecha actual
        $tarea->todoElDiaTarea = 0;
        $tarea->color = 6;
        $tarea->finalizadaTarea = 0;
        $tarea->Persona_documentoPersona = Util::traerDocumentoAbogadoAsignado($vigencia, $idProceso);
        $tarea->save();

        //Tercera tarea:  Comunicar contenido de auto mediante el cual se acumula una queja o informe
        $tarea = new Tarea;
        $tarea->Radicado_idRadicado = $idProceso;
        $tarea->Radicado_vigencia = $vigencia; // Almacena el año actual
        $tarea->asuntoTarea = "Enviar comunicaciones - auto acumulación queja";
        $tarea->lugarTarea = "Oficina de Control Disciplinario Interno";
        $tarea->descripcionTarea = "Enviar comunicaciones informando contenido del auto por medio del cual se acumula por conexidad procesal la queja ".Input::get('idQueja');
        $tarea->fechaInicioTarea = $fechaValorar." ".date('H:i:s', strtotime("17:00:00"));
        $tarea->fechaFinTarea = $fechaValorar." ".date('H:i:s', strtotime("18:00:00"));
        $tarea->fechaProgramaTarea = $fechaHoy." ".date('H:i:s');// Fecha actual
        $tarea->todoElDiaTarea = 0;
        $tarea->color = 6;
        $tarea->finalizadaTarea = 0;
        $tarea->Persona_documentoPersona = Util::traerDocumentoAbogadoAsignado($vigencia, $idProceso);
        $tarea->save();

        return 1;

	}


	public function actionGuardarReparto()
	{
		$fechaHoy = date("Y-m-d");
		$usuario = Session::get('documentoUsuario');

		$abogados = DB::table('abogado')
						->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
						->where('abogado.activo', 1)//1 Activo
						->where('abogado.juzgamiento', 0)//0 No prtenecen a la secretaría jurídica - Fase Juzgamiento
						->orderBy('persona.nombre', 'asc')
						->get();

		//Recorre la cantidad de abogados para decodificar el formato json de los vectores
		for ($i = 1; $i <= count($abogados); $i++)
		{
			//Decodifica formato el json
			${'vector'.$i} = json_decode(Input::get('json'.$i), true);

			//Si se encontró al menos una queja
			if (count(${'vector'.$i}) > 0)
			{
				for ($j = 0; $j < count(${'vector'.$i}); $j++)
				{
					//Almacena Radicado
	                $radicado = new Radicado;
	                $radicado->vigencia = date('Y'); // Almacena el año actual
	                $radicado->EstadoRadicado_idEstadoRadicado = 5; //Valor 5 (Radicado Asignado a Abogado)
	                $radicado->Etapa_idEtapa = 11; // 11 Etapa Valoración
	                $radicado->fechaRadicado = $fechaHoy;
	                $radicado->horaRadicado = date('g:i a');
	                $radicado->save();

	                //radicado insertado
	                $idRadicadoInsertado = $radicado->idRadicado;

	                //Almacena ObservacionesRadicado -- Estado:  (Radicado Generado)
	                $observacionRadicado  = new ObservacionRadicado;
	                $observacionRadicado->EstadoRadicado_idEstadoRadicado = 1;// 1 NÚMERO DE RADICADO ASIGNADO AL PROCESO
	                $observacionRadicado->Radicado_idRadicado = $idRadicadoInsertado;
	                $observacionRadicado->Radicado_vigencia = date('Y'); // Almacena el año actual
	                $observacionRadicado->Persona_documentoPersona = $usuario;
	                $observacionRadicado->observacion = "Se le asignó al proceso el radicado ".date('Y')."-".$idRadicadoInsertado;
	                $observacionRadicado->fechaObservacion = $fechaHoy;
	                $observacionRadicado->horaObservacion = date('g:i a');// Hora actual
	                $observacionRadicado->save();

	                //Almacena AcumulaQueja
	                $acumulaQueja = new AcumulaQueja;
	                $acumulaQueja->Queja_idQueja = ${'vector'.$i}[$j];//Queja almacenada en el vector
	                $acumulaQueja->Radicado_idRadicado = $idRadicadoInsertado;
	                $acumulaQueja->Radicado_Vigencia = date('Y'); // Almacena el año actual
	                $acumulaQueja->Persona_documentoPersona = $usuario;
	                $acumulaQueja->fechaAcumula = $fechaHoy;// Fecha actual
	                $acumulaQueja->horaAcumula = date('g:i a'); // Hora actual
	                $acumulaQueja->save();

	                //Almacena AcumulaRadicado
                    $acumulaRadicado = new AcumulaRadicado;
                    $acumulaRadicado->Radicado_idRadicado = $idRadicadoInsertado;
                    $acumulaRadicado->Radicado_vigencia = date('Y'); // Almacena el año actual
                    $acumulaRadicado->AcumulaQueja_Radicado_idRadicado = $idRadicadoInsertado;// Mismo valor del Radicado
                    $acumulaRadicado->AcumulaQueja_Radicado_vigencia = date('Y'); // Mismo valor de la vigencia del Radicado
                    $acumulaRadicado->AcumulaQueja_Queja_idQueja = ${'vector'.$i}[$j];//Queja almacenada en el vector
                    $acumulaRadicado->Persona_documentoPersona = $usuario;
                    $acumulaRadicado->fechaAcumula = $fechaHoy;// Fecha actual
                    $acumulaRadicado->horaAcumula = date('g:i a'); // Hora actual
                    $acumulaRadicado->save();

                    //Almacena AbogadoAsignado
                    $abogadoAsignado = new AbogadoAsignado;
                    $abogadoAsignado->Radicado_idRadicado = $idRadicadoInsertado;
                    $abogadoAsignado->Radicado_vigencia = date('Y'); // Almacena el año actual
                    $abogadoAsignado->Abogado_idAbogado = $abogados[$i-1]->idAbogado;// id del abogado de cada iteración
                    $abogadoAsignado->fechaAsignacion = $fechaHoy;// Fecha actual
                    $abogadoAsignado->observacion = "En reparto se delegó al profesional ".$abogados[$i-1]->nombre." el conocimiento del proceso";
                    $abogadoAsignado->actual = "SI";
                    $abogadoAsignado->save();

                    //Actualiza el estado de la queja
					DB::table('queja')
			          ->where('idQueja', ${'vector'.$i}[$j])
			          ->update(['EstadoQueja_idEstadoQueja' => 6]);// Estado 6 (Queja con Radicado asignado)
					//------------------------------------------------

					//Agrega la nueva etapa: VALORACIÓN
                    $etapaProceso = new EtapaProceso;
                    $etapaProceso->Radicado_idRadicado = $idRadicadoInsertado;
                    $etapaProceso->Radicado_vigencia = date('Y'); // Almacena el año actual
                    $etapaProceso->Etapa_idEtapa = 11;//11 Etapa VALORACIÓN
                    $etapaProceso->fechaEtapa = $fechaHoy;// Fecha actual
                    $etapaProceso->observacion = "El proceso pasó a valoración por parte del profesional.";
                    $etapaProceso->actual = 1;
                    $etapaProceso->fechaFinalEtapa = Util::calcularFechaFinalEtapa(11);//11 Valoración
                    $etapaProceso->save();

                    //Almacena ObservacionesRadicado -- Estado: (Radicado asignado a Abogado)
                    $observacionRadicado = new ObservacionRadicado;
                    $observacionRadicado->EstadoRadicado_idEstadoRadicado = 5; //(Radicado asignado a Abogado)
                    $observacionRadicado->Radicado_idRadicado = $idRadicadoInsertado;
                    $observacionRadicado->Radicado_vigencia = date('Y'); // Almacena el año actual
                    $observacionRadicado->Persona_documentoPersona = $usuario;
                    $observacionRadicado->observacion = "Se asignó el profesional: ".$abogados[$i-1]->nombre.", para el conocimiento del proceso ".date('Y')."-".$idRadicadoInsertado;
                    $observacionRadicado->fechaObservacion = $fechaHoy;// Fecha actual
                    $observacionRadicado->horaObservacion = date('g:i a'); // Hora actual
                    $observacionRadicado->save();

                    //Almacena ObservacionesQueja - Se crea número de radicado para la queja
                    $observacionQueja = new ObservacionQueja;
                    $observacionQueja->EstadoQueja_idEstadoQueja = 6;// 6 Queja con Radicado asignado
                    $observacionQueja->Queja_idQueja = ${'vector'.$i}[$j];//Queja almacenada en el vector
                    $observacionQueja->Persona_documentoPersona = $usuario;
                    $observacionQueja->observacion = "Se generó el número de radicado ".date('Y')."-".$idRadicadoInsertado." para la queja ".${'vector'.$i}[$j];
                    $observacionQueja->fechaObservacion = $fechaHoy;// Fecha actual
                    $observacionQueja->horaObservacion = date('g:i a'); // Hora actual
                    $observacionQueja->save();

                    // ----------  Programa la tarea para la secretaria (Escanear la Queja)  -------------------------------

                    //** Parámetro Escanear **
                    $parametro = Parametro::find(19); // 19 (Tiempo en días hábiles que tiene la secretaria para escanear y adjuntar la queja a un proceso)
                    $diasEscanear = $parametro->valorParametro;
                    //Calcula la fecha final en la que debe escanear la queja
                    $fechaEscanear = Util::obtenerFechaFinalHabiles($fechaHoy, $diasEscanear, 1); // 1 días hábiles
                    //** ------------------ **

                    //Busca todos los usuarios cuyo perfil sea secretaria
                    $operadores = DB::table('usuario')
                    				->where('Perfil_idPerfil', '=', 3)// 3 Perfil Secretaria
                    				->get();

                    //Si encontró al menos una secretaria:
                    if(count($operadores) > 0)
                    {
                    	//Recorre cada uno de los usuarios encontrados
                    	foreach ($operadores as $operador)
                    	{
                    		//Almacena Tarea para la Secretaria
	                        $tarea = new Tarea;
	                        $tarea->Radicado_idRadicado = $idRadicadoInsertado;
	                        $tarea->Radicado_vigencia = date('Y'); // Almacena el año actual
	                        $tarea->asuntoTarea = "Escanear Queja";
	                        $tarea->lugarTarea = "Oficina de Control Disciplinario Interno";
	                        $tarea->descripcionTarea = "Escanear y adjuntar a mas tardar el ".Util::formatearFecha($fechaEscanear).", la queja o informe ".${'vector'.$i}[$j]." a la cual le correspondió el numero de proceso ".date('Y')."-".$idRadicadoInsertado;
	                        $tarea->fechaInicioTarea = $fechaEscanear." ".date('H:i:s', strtotime("17:00:00"));
	                        $tarea->fechaFinTarea = $fechaEscanear." ".date('H:i:s', strtotime("18:00:00"));
	                        $tarea->fechaProgramaTarea = $fechaHoy." ".date('H:i:s');// Fecha actual
	                        $tarea->todoElDiaTarea = 0;
	                        $tarea->color = 6;
	                        $tarea->finalizadaTarea = 0;
	                        $tarea->Persona_documentoPersona = $operador->Persona_documentoPersona;
	                        $tarea->save();
                    	}
                    }

                    // ----------  Programa la tarea para el profesional (Valorar el proceso)  -------------------------------

                    //** Parámetro Valorar **
                    $parametro = Parametro::find(20); // 20 (Tiempo en días hábiles que tiene el profesional para valorar un proceso)
                    $diasValorar = $parametro->valorParametro;
                    //Calcula la fecha final en la que debe escanear la queja
                    $fechaValorar = Util::obtenerFechaFinalHabiles($fechaHoy, $diasValorar, 1); // 1 días hábiles
                    //** ------------------ **

                    //Almacena Tarea para la Secretaria
                    $tarea = new Tarea;
                    $tarea->Radicado_idRadicado = $idRadicadoInsertado;
                    $tarea->Radicado_vigencia = date('Y'); // Almacena el año actual
                    $tarea->asuntoTarea = "Valorar Proceso";
                    $tarea->lugarTarea = "Oficina de Control Disciplinario Interno";
                    $tarea->descripcionTarea = "Valorar a mas tardar el ".Util::formatearFecha($fechaValorar).", el proceso ".date('Y')."-".$idRadicadoInsertado;
                    $tarea->fechaInicioTarea = $fechaValorar." ".date('H:i:s', strtotime("17:00:00"));
	                $tarea->fechaFinTarea = $fechaValorar." ".date('H:i:s', strtotime("18:00:00"));
	                $tarea->fechaProgramaTarea = $fechaHoy." ".date('H:i:s');// Fecha actual
                    $tarea->todoElDiaTarea = 0;
                    $tarea->color = 6;
                    $tarea->finalizadaTarea = 0;
                    $tarea->Persona_documentoPersona = $abogados[$i-1]->documentoPersona;
                    $tarea->save();
	            }//for
			}//if
		}//for

	}

	public function actionModificarPersona()
    {
		/*
			TABLAS QUE INVOLUCRAN PERSONAS:
			abogado 
			acumulaqueja
			acumularadicado
			archivo
			archivogenerado
			funcionario
			notificacion
			observacionesqueja
			observacionesradicado
			oficio
			permisostemporales
			quejasacumuladas
			quejoso
			registro
			solicitudauto
			tarea
			usuario
		*/

		//Si se va a cambiar el documento, actualiza el nuevo documento en otras tablas
		if (Input::get('documentoPersona') != Input::get('documentoPersonaField')) 
		{
			//Valida que el documento ingresado no pertenezca a otro usuario
			$persona = DB::table('persona')
						 ->where('documentoPersona', Input::get('documentoPersonaField'))
						 ->first();

			if (count($persona) > 0) 
			{
				$mensaje = 'Ya se encuentra registrado: '.$persona->nombre.' con este número de documento';
				return Response::json(array('error' => 1, 'mensaje' => $mensaje));
			}

			//Actualiza los datos de la persona incluído el campo documento
			DB::table('persona')
			->where('documentoPersona', Input::get('documentoPersona'))
			->update([
				'documentoPersona'         => Input::get('documentoPersonaField'),
				'nombre'                   => Input::get('nombre'),
				'direccionResidencia'      => Input::get('direccionCorrespondencia'),
				'ciudadResidencia'         => Input::get('ciudadCorrespondencia'),
				'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
				'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
				'telefono'                 => Input::get('telefono'),
				'telefono2'                => Input::get('telefono2'),
				'email'                    => Input::get('email')
			]);

			//Actualiza el funcionario
			DB::table('funcionario')
				->where('Persona_documentoPersona', Input::get('documentoPersona'))
				->update(['Persona_documentoPersona'=> Input::get('documentoPersonaField')]);

			//Actualiza el quejoso
			DB::table('quejoso')
				->where('Persona_documentoPersona', Input::get('documentoPersona'))
				->update(['Persona_documentoPersona'=> Input::get('documentoPersonaField')]);
		} 
		else //Si no se va a ctualizar el documento, sólo los demás campos:
		{
			//Actualiza los datos de la persona
			DB::table('persona')
			->where('documentoPersona', Input::get('documentoPersona'))
			->update([
				'nombre'                   => Input::get('nombre'),
				'direccionResidencia'      => Input::get('direccionCorrespondencia'),
				'ciudadResidencia'         => Input::get('ciudadCorrespondencia'),
				'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
				'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
				'telefono'                 => Input::get('telefono'),
				'telefono2'                => Input::get('telefono2'),
				'email'                    => Input::get('email')
			]);
		}						

		//Datos para el log
		$nuevaPersona = [   'nombre'                   => Input::get('nombre'), 
							'documentoPersona'         => Input::get('documentoPersonaField'),
							'direccionCorrespondencia' => Input::get('direccionCorrespondencia'),
							'ciudadCorrespondencia'    => Input::get('ciudadCorrespondencia'),
							'telefono'                 => Input::get('telefono'),
							'telefono2'                => Input::get('telefono2'),
							'email'                    => Input::get('email')];

		// ===== LOGS ===== //  	
		$accion = 9;//9 Modifica datos de una persona
		$descripcion = "Se modificaron los datos de la persona: ".Input::get('persona')." por ".json_encode($nuevaPersona);
		Util::almacenaLog($accion, $descripcion);
		//-----------
		
		$mensaje = 'Los datos de la persona se modificaron con éxito con éxito';
		return Response::json(array('error' => 0, 'mensaje' => $mensaje));
	} 

}