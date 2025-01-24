<?php
//Incluye las funciones
//include app_path().'\views\includes\funciones.php'; 

class UsuarioController extends \BaseController 
{		

	public function actionCredenciales($documento)
	{
		$password = Hash::make($documento);

		echo $password;
	}


	//## CAMBIAR PASSWORD ##	
	public function actionPassword()
	{
		$usuario = Usuario::find(Session::get('idUsuario'));

		$passwordAnterior = Input::get('passwordAnterior');
		$passwordNuevo = Input::get('passwordNuevo');
		$idUsuario = Session::get('idUsuario');

		if($passwordNuevo == $usuario->Persona_documentoPersona)
		{
			$retorno = 1; //1 Contraseña es la misma que el documento del usuario
			return $retorno;
		}

		if(!Hash::check($passwordAnterior, $usuario->password))
        {
            $retorno = 2; //2 Contraseña anterior ingresada no coincide con la almacenada
			return $retorno;
        }
        else
        {
            //Actualiza los datos de la contraseña
	     	$usu = DB::table('usuario')
				   	  ->where('idUsuario', '=', $idUsuario)
		    	      ->update(array('password' => Hash::make($passwordNuevo)));

		    $retorno = 3; //3 la Contraseña se modificó correctamente
			return $retorno;
        }			
	}
	
	//## RESTABLECER PASSWORD ##
	public function actionPasswordReset()
	{
		$idUsuario = Input::get('idUsuario');
		$usuario = Usuario::find($idUsuario);
		
        //Actualiza los datos de la contraseña
     	$usu = DB::table('usuarios')
			   	  ->where('idUsuario', '=', $idUsuario)
	    	      ->update(array('password' => Hash::make($usuario->documentoUsuario),
	    	      				 'cambioClave' => 1));

	    $retorno = 1; //1 la Contraseña se restableció correctamente
		return $retorno;      			
	}
	
	//## INACTIVAR USUARIO ##
	public function actionInactivar()
	{
		$idUsuario = Input::get('idUsuario');
		
        //Actualiza los datos de la contraseña
     	$usu = DB::table('usuarios')
			   	  ->where('idUsuario', '=', $idUsuario)
	    	      ->update(array('activoUsuario' => 0));

	    $retorno = 1; //1 la Contraseña se restableció correctamente
		return $retorno;      			
	}

	//## CAMBIAR PASSWORD INICIAL##
	public function actionPasswordIni()
	{		
		$rol = Session::get('rolUsuario');
		$idUsuario = Session::get('idUsuario');

		//Si se recibe petición por POST
		if ($_POST) 
		{ 
			DB::update('update usuarios set password = ?, cambioClave = ? where idUsuario = ?', 
			array(Hash::make(Input::get('nuevaClave')), 0, Input::get('idUsuario')));
		
			return Redirect::to('welcome'); 
		}		
	}

	//Restaurar Ejecutar
	public function actionRestaurarEjecutar($idUsuario)
	{
		$usuarios = Usuario::where('superUsuario','=',0)
	    				   ->get();
	    //Trae la cantidad de usuarios configurada para la entidad
		$entidad = Entidad::find(1);

		if(count($entidad) > 0)
		{	
			$numeroUsuarios = $entidad->numeroUsuarios;
		}
		else
		{
			$numeroUsuarios = 0;
		}

		//Valida el límite de usuarios contratado (Si es menor permite restaurar el usuario)
	   if(count($usuarios) < $numeroUsuarios)
	   {
			//Cambia el estado al indicador
			DB::update('update usuarios set activoUsuario = ? where idUsuario = ?', array(1, $idUsuario));

			$usuario = Usuario::find($idUsuario);

			// ===== ALMACENA LA BITÁCORA CORRESPONDIENTE A LA ACCIÓN ===== //					
			$accion = 24;//24 Reactiva Usuario
			$descripcion = "Reactiva y restaura el usuario: ".$usuario->nombresUsuario;
			$procesoBitacora = 0;	
			//Llama la función para almacenar la bitácora
			almacenaBitacora($accion, $descripcion, $procesoBitacora);
			// =========================BITACORA=========================== //

			//Trae los usuarios inactivos
		    $usuarios = Usuario::where('activoUsuario', '=', 0)
		    						->get();
			//Retorna la vista    	   
	    	return View::make('plantillas.ajaxUsuariosPapelera')
	    	   			->with('usuarios', $usuarios);	
	    }
	    else
    	{
    		//Almacena la sesión temporal para mostrar el mensaje
			Session::flash('message', $entidad->nombreEntidad.' tiene contratado un plan de '.$numeroUsuarios.' usuarios.  No es posible restaurar el usuario porque ya se alcanzó el límite permitido.  <br>Póngase en contacto con Clarity Systems Group S.A.S para ampliar este límite.');
    		Session::flash('class','error');

    		//Trae los usuarios inactivos
		    $usuarios = Usuario::where('activoUsuario', '=', 0)
		    						->get();
			//Retorna la vista    	   
	    	return View::make('plantillas.ajaxUsuariosPapelera')
	    	   			->with('usuarios', $usuarios);	
    	}
	}
}